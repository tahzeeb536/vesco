<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;

class EmployeeAttendance extends Page
{
    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.resources.employee-resource.pages.employee-attendance';

    public $employee;
    public $attendanceData;

    public function mount($record): void
    {
        // Load the employee (you might consider using route model binding if preferred)
        $this->employee = Employee::findOrFail($record);

        // Retrieve attendance data for the employee.
        // In a real application, you might have an Attendance model related to Employee.
        // For this example, we’ll simulate the data with static arrays.
        $this->attendanceData = [
            'days'   => range(1, 30),
            'status' => array_fill(0, 28, 'P'),
            'hours'  => array_fill(0, 28, 8),
            'late'   => array_fill(0, 28, 0),
            'ot'     => [3, 0, 3, 4, 3, 6, 5, 4, 0, 3, 4, 6, 4, 4, 6, 0, 3, 3, 3, 3, 3, 4, 0, 3, 2, 3, 3, 3],
        ];
    }


}
