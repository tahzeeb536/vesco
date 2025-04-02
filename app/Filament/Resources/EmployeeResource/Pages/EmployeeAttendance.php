<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;
use App\Models\Attendance;
use Filament\Actions;
use Carbon\Carbon;

class EmployeeAttendance extends Page
{
    protected static string $resource = EmployeeResource::class;
    protected static string $view = 'filament.resources.employee-resource.pages.employee-attendance';

    public $employee;
    public $attendanceData = [];
    public $selectedMonth;
    public $selectedYear;

    public function mount($record): void
    {
        // Load the employee (consider route model binding if preferred)
        $this->employee = Employee::findOrFail($record);

        // Get selected month and year from query parameters or default to current month/year
        $this->selectedMonth = request()->get('month', date('n'));
        $this->selectedYear  = request()->get('year', date('Y'));

        // Fetch attendance data from the attendances table for the selected month/year
        $this->attendanceData = $this->fetchAttendanceData($this->selectedMonth, $this->selectedYear);
    }

    protected function fetchAttendanceData($month, $year)
    {
        // Get total days in the month
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days = range(1, $totalDays);

        $standardHours = 8;

        // Initialize default arrays (adjust defaults as needed)
        $status = array_fill(0, $totalDays, '-');
        $hours  = array_fill(0, $totalDays, 0);
        $late   = array_fill(0, $totalDays, 0);
        $ot     = array_fill(0, $totalDays, 0);
        
        $dayNames = [];

        foreach ($days as $day) {
            $date = Carbon::createFromDate($year, $month, $day);
            $dayNames[] = $date->format('D');
        }


        // Fetch the attendances for the employee for the given month and year
        $attendances = Attendance::where('employee_id', $this->employee->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
            // Process each attendance record
        foreach ($attendances as $attendance) {
            // Get day number (1-indexed)
            $dayIndex = Carbon::parse($attendance->date)->day - 1;
            $workedHours = $attendance->hours_worked + ($attendance->minutes_worked / 60);
            $status[$dayIndex] = $attendance->status;
            $hours[$dayIndex]  = $workedHours;
            $ot[$dayIndex]     = $attendance->overtime_hours + ($attendance->overtime_minutes / 60);

            if ($workedHours < $standardHours) {
                $late[$dayIndex] = $standardHours - $workedHours;
            }

        }

        $absentCount = count(array_filter($status, function($s) {
            return $s !== 'Present';
        }));


        return compact('days', 'dayNames', 'status', 'hours', 'late', 'ot', 'absentCount');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('profile')
                ->label('Profile')
                ->url(fn () => EmployeeResource::getUrl('view', ['record' => $this->employee->id]))
                ->color('primary'),
            Actions\Action::make('Loans')
                ->url(fn () => EmployeeResource::getUrl('laon', ['record' => $this->employee->id])),
            Actions\Action::make('TempAdvance')
                ->url(fn () => EmployeeResource::getUrl('temp_loan', ['record' => $this->employee->id])),
            Actions\Action::make('Account Statement')
                ->url(fn () => EmployeeResource::getUrl('account-statement', ['record' => $this->employee->id])),
        ];
    }
}
