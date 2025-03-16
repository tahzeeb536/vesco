<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;
use Filament\Actions;

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

        
        $this->attendanceData = [
            'days'   => range(1, 30),
            'status' => array_fill(0, 28, 'P'),
            'hours'  => array_fill(0, 28, 8),
            'late'   => array_fill(0, 28, 0),
            'ot'     => [3, 0, 3, 4, 3, 6, 5, 4, 0, 3, 4, 6, 4, 4, 6, 0, 3, 3, 3, 3, 3, 4, 0, 3, 2, 3, 3, 3],
        ];
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('profile')
                ->label('Profile')
                ->url(fn () => EmployeeResource::getUrl('view', ['record' => $this->employee->id]))
                ->color('primary'),
        ];
    }

}
