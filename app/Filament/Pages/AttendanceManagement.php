<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use App\Models\Employee;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Notifications\Notification;
use Carbon\Carbon;
use Filament\Actions\Action;

class AttendanceManagement extends Page implements HasTable
{
    use InteractsWithTable, HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Daily Attendance';    
    protected static ?string $navigationGroup = 'Employees';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.attendance-management';

    public $selectedEmployeeId;
    public $selectedDate;
    public $attendances = [];


    public function mount()
    {
        $this->selectedDate = now()->format('Y-m-d');
        $this->loadAttendanceData();
    }

    public function loadAttendanceData()
    {
        $this->attendances = Employee::where('status', 1)
            ->with(['attendance' => function ($query) {
                $query->where('date', $this->selectedDate);
            }])
            ->get()
            ->map(function ($employee) {
                $attendance = $employee->attendance->first();

                return [
                    'employee_id' => $employee->id,
                    'name' => $employee->name,
                    'name_urdu' => $employee->name_urdu,
                    'status' => $attendance->status ?? 'Present',
                    'clock_in' => $attendance->clock_in ?? '08:00',
                    'break_out' => $attendance->break_out ?? '',
                    'break_in' => $attendance->break_in ?? '',
                    'clock_out' => $attendance->clock_out ?? '17:00',
                    'hours_worked' => $attendance->hours_worked ?? 8,
                    'minutes_worked' => $attendance->minutes_worked ?? 0,
                    'overtime_hours' => $attendance->overtime_hours ?? 0,
                    'overtime_minutes' => $attendance->overtime_minutes ?? 0,
                    'saved' => $attendance ? true : false,
                ];
            })->toArray();
    }

    public function saveAllAttendance()
    {
        foreach ($this->attendances as $attendanceData) {

            $overtimeDecimal = $attendanceData['overtime'] ?? 0;

            if (!is_numeric($overtimeDecimal)) {
                $overtimeDecimal = 0;
            }

            $overtimeHours = floor($overtimeDecimal);
            $overtimeMinutes = round(($overtimeDecimal - $overtimeHours) * 60);

            Attendance::updateOrCreate(
                [
                    'employee_id' => $attendanceData['employee_id'],
                    'date' => $this->selectedDate,
                ],
                [
                    'status' => $attendanceData['status'],
                    'clock_in' => $attendanceData['clock_in'],
                    'break_out' => $attendanceData['break_out'],
                    'break_in' => $attendanceData['break_in'],
                    'clock_out' => $attendanceData['clock_out'],
                    'hours_worked' => $attendanceData['hours_worked'],
                    'minutes_worked' => $attendanceData['minutes_worked'],
                    'overtime_hours' => (int) $overtimeHours,
                    'overtime_minutes' => (int) $overtimeMinutes,
                ]
            );
        }

        Notification::make()
            ->title('Attendance Saved for All Employees')
            ->success()
            ->send();
    }


    protected function table(Table $table): Table
    {
        return $table->query(
            Employee::query()->where('status', 1)->with(['attendance' => function ($query) {
                $query->where('date', $this->selectedDate);
            }])
        )
        ->description("Date: " . $this->selectedDate );
    }

    public function updated($property, $value)
    {
        // Listen to any change in these fields
        if (preg_match('/^attendances\.(\d+)\.(clock_in|clock_out|break_out|break_in)$/', $property, $matches)) {
            $index = (int) $matches[1];
            $this->calculateWorkingTime($index);
        }
    }



    public function calculateWorkingTime($index)
    {
        $attendance = $this->attendances[$index];

        $clockIn = $attendance['clock_in'] ?? null;
        $clockOut = $attendance['clock_out'] ?? null;
        $breakOut = $attendance['break_out'] ?? null;
        $breakIn = $attendance['break_in'] ?? null;

        if (!$clockIn || !$clockOut) {
            return;
        }

        try {
            $in = Carbon::createFromFormat('H:i', $clockIn);
            $out = Carbon::createFromFormat('H:i', $clockOut);

            // Guard clause: invalid case where out is before in
            if ($out->lessThanOrEqualTo($in)) {
                $this->resetWorkedTime($index);
                return;
            }

            // Total duration between clock in and clock out
            $totalMinutes = $in->diffInMinutes($out);

            // Fixed lunch break: always deduct 60 minutes
            $deductedBreak = 60;

            // If break_out is set and break_in is NOT set, assume user left
            if ($breakOut && !$breakIn) {
                // Worked only from clock_in to break_out
                $in = Carbon::createFromFormat('H:i', $clockIn);
                $breakStart = Carbon::createFromFormat('H:i', $breakOut);

                if ($breakStart->greaterThan($in)) {
                    $totalMinutes = $in->diffInMinutes($breakStart);

                    $this->attendances[$index]['hours_worked'] = intdiv($totalMinutes, 60);
                    $this->attendances[$index]['minutes_worked'] = $totalMinutes % 60;

                    $this->attendances[$index]['overtime_hours'] = 0;
                    $this->attendances[$index]['overtime_minutes'] = 0;
                } else {
                    $this->resetWorkedTime($index);
                }

                return; // Exit here
            }


            // Calculate additional break time if set
            if ($breakOut && $breakIn) {
                $breakStart = Carbon::createFromFormat('H:i', $breakOut);
                $breakEnd = Carbon::createFromFormat('H:i', $breakIn);

                if ($breakEnd->greaterThan($breakStart)) {
                    $breakDuration = $breakStart->diffInMinutes($breakEnd);

                    // Subtract the fixed lunch hour once; the rest is extra
                    $extraBreak = max(0, $breakDuration - 60);
                    $deductedBreak += $extraBreak;
                }
            }

            // Calculate net worked time
            $netMinutes = max(0, $totalMinutes - $deductedBreak);

            $this->attendances[$index]['hours_worked'] = intdiv($netMinutes, 60);
            $this->attendances[$index]['minutes_worked'] = $netMinutes % 60;

            // Optional: calculate overtime if over 8 hours (480 minutes)
            $overtime = max(0, $netMinutes - 480);
            $this->attendances[$index]['overtime_hours'] = intdiv($overtime, 60);
            $this->attendances[$index]['overtime_minutes'] = $overtime % 60;

        } catch (\Exception $e) {
            $this->resetWorkedTime($index);
        }
    }

    private function resetWorkedTime($index)
    {
        $this->attendances[$index]['hours_worked'] = 0;
        $this->attendances[$index]['minutes_worked'] = 0;
        $this->attendances[$index]['overtime_hours'] = 0;
        $this->attendances[$index]['overtime_minutes'] = 0;
    }


}
