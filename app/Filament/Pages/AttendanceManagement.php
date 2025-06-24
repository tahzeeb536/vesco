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

            // $breakOut = ($attendanceData['break_out'] ?? '') && $attendanceData['break_out'] !== '00:00:00'
            // ? $attendanceData['break_out']
            // : null;

            // $breakIn = ($attendanceData['break_in'] ?? '') && $attendanceData['break_in'] !== '00:00:00'
            // ? $attendanceData['break_in']
            // : null;

            // $status = $attendanceData['status'];

            // $hoursWorked = in_array($status, ['Absent', 'Leave']) ? 0 : ($attendanceData['hours_worked'] ?? 0);
            // $minutesWorked = in_array($status, ['Absent', 'Leave']) ? 0 : ($attendanceData['minutes_worked'] ?? 0);
            // $overtimeHours = in_array($status, ['Absent', 'Leave']) ? 0 : ($attendanceData['overtime_hours'] ?? 0);
            // $overtimeMinutes = in_array($status, ['Absent', 'Leave']) ? 0 : ($attendanceData['overtime_minutes'] ?? 0);

            $status = $attendanceData['status'];

            $isAbsentOrLeave = in_array($status, ['Absent', 'Leave']);

            $clockIn = $isAbsentOrLeave ? null : ($attendanceData['clock_in'] ?? null);
            $clockOut = $isAbsentOrLeave ? null : ($attendanceData['clock_out'] ?? null);
            $breakOut = $isAbsentOrLeave ? null : (($attendanceData['break_out'] ?? '') ?: null);
            $breakIn = $isAbsentOrLeave ? null : (($attendanceData['break_in'] ?? '') ?: null);

            $hoursWorked = $isAbsentOrLeave ? 0 : ($attendanceData['hours_worked'] ?? 0);
            $minutesWorked = $isAbsentOrLeave ? 0 : ($attendanceData['minutes_worked'] ?? 0);
            $overtimeHours = $isAbsentOrLeave ? 0 : ($attendanceData['overtime_hours'] ?? 0);
            $overtimeMinutes = $isAbsentOrLeave ? 0 : ($attendanceData['overtime_minutes'] ?? 0);


            Attendance::updateOrCreate(
                [
                    'employee_id' => $attendanceData['employee_id'],
                    'date' => $this->selectedDate,
                ],
                [
                    'status' => $attendanceData['status'],
                    'clock_in' => $attendanceData['clock_in'],
                    'break_out' => $breakOut,
                    'break_in' => $breakIn,
                    'clock_out' => $attendanceData['clock_out'],
                    'hours_worked' => $hoursWorked,
                    'minutes_worked' => $minutesWorked,
                    'overtime_hours' => $overtimeHours,
                    'overtime_minutes' => $overtimeMinutes,
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
        // Time fields trigger calculation
        if (preg_match('/^attendances\.(\d+)\.(clock_in|clock_out|break_out|break_in)$/', $property, $matches)) {
            $index = (int) $matches[1];
            $this->calculateWorkingTime($index);
        }

        // Status change: if changed to "Present", recalculate
        if (preg_match('/^attendances\.(\d+)\.status$/', $property, $matches)) {
            $index = (int) $matches[1];
            $status = $this->attendances[$index]['status'];

            if ($status === 'Present') {
                $this->calculateWorkingTime($index);
            } else {
                $this->resetWorkedTime($index);
            }
        }
    }


    public function calculateWorkingTime($index)
    {
        $attendance = $this->attendances[$index];

        $clockIn = $this->formatTimeToHM($attendance['clock_in'] ?? null);
        $clockOut = $this->formatTimeToHM($attendance['clock_out'] ?? null);
        $breakOut = $this->formatTimeToHM($attendance['break_out'] ?? null);
        $breakIn = $this->formatTimeToHM($attendance['break_in'] ?? null);

        if (!$clockIn || !$clockOut) {
            return;
        }

        try {
            $in = Carbon::createFromFormat('H:i', $clockIn);
            $out = Carbon::createFromFormat('H:i', $clockOut);

            if ($out->lessThanOrEqualTo($in)) {
                $this->resetWorkedTime($index);
                return;
            }

            $totalMinutes = $in->diffInMinutes($out);

            // Always deduct fixed 1-hour lunch break (1 PM - 2 PM)
            $deductedBreak = 60;

            // Extra break (outside fixed 1–2 PM) should also be deducted
            if ($breakOut && $breakIn) {
                $breakStart = Carbon::createFromFormat('H:i', $breakOut);
                $breakEnd = Carbon::createFromFormat('H:i', $breakIn);

                if ($breakEnd->greaterThan($breakStart)) {
                    $breakDuration = $breakStart->diffInMinutes($breakEnd);

                    // Define fixed lunch break window
                    $lunchStart = Carbon::createFromTime(13, 0); // 1:00 PM
                    $lunchEnd = Carbon::createFromTime(14, 0);   // 2:00 PM

                    // Calculate overlap with lunch
                    $overlapStart = $breakStart->copy()->max($lunchStart);
                    $overlapEnd = $breakEnd->copy()->min($lunchEnd);

                    $lunchOverlapMinutes = 0;
                    if ($overlapEnd->greaterThan($overlapStart)) {
                        $lunchOverlapMinutes = $overlapStart->diffInMinutes($overlapEnd);
                    }

                    // Deduct only the part of break outside the 1–2 PM lunch
                    $extraBreak = $breakDuration - $lunchOverlapMinutes;
                    $deductedBreak += $extraBreak;
                }
            }


            // If user only has break_out but no break_in (left early)
            if ($breakOut && !$breakIn) {
                $breakStart = Carbon::createFromFormat('H:i', $breakOut);
                if ($breakStart->greaterThan($in)) {
                    $totalMinutes = $in->diffInMinutes($breakStart);
                } else {
                    $totalMinutes = 0;
                }

                $this->attendances[$index]['hours_worked'] = intdiv($totalMinutes, 60);
                $this->attendances[$index]['minutes_worked'] = $totalMinutes % 60;
                $this->attendances[$index]['overtime_hours'] = 0;
                $this->attendances[$index]['overtime_minutes'] = 0;
                return;
            }

            // Final working minutes
            $netMinutes = max(0, $totalMinutes - $deductedBreak);

            $this->attendances[$index]['hours_worked'] = intdiv($netMinutes, 60);
            $this->attendances[$index]['minutes_worked'] = $netMinutes % 60;

            $overtime = max(0, $netMinutes - 480);
            $this->attendances[$index]['overtime_hours'] = intdiv($overtime, 60);
            $this->attendances[$index]['overtime_minutes'] = $overtime % 60;

        } catch (\Exception $e) {
            $this->resetWorkedTime($index);
        }
    }

    private function formatTimeToHM($time)
    {
        if (!$time) return null;
        if (strlen($time) === 5) return $time; // Already in HH:MM
        return substr($time, 0, 5); // Trim to HH:MM
    }

    private function resetWorkedTime($index)
    {
        $this->attendances[$index]['hours_worked'] = 0;
        $this->attendances[$index]['minutes_worked'] = 0;
        $this->attendances[$index]['overtime_hours'] = 0;
        $this->attendances[$index]['overtime_minutes'] = 0;
    }


}
