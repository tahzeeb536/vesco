<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;
use Filament\Actions;

class AccountStatement extends Page
{
    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.resources.employee-resource.pages.account-statement';

    public $record;

    public function mount($record): void
    {
        $this->record = Employee::with([
            'advance_salaries',
            'advance_salary_balance',
            'salaries',
            'advance_salary_deductions',
            'temp_loans',
            'employee_statements'
        ])->findOrFail($record);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('profile')
                ->label('Profile')
                ->url(fn () => EmployeeResource::getUrl('view', ['record' => $this->record->id]))
                ->color('primary'),
            Actions\Action::make('Attendance')
                ->url(fn () => EmployeeResource::getUrl('attendance', ['record' => $this->record->id])),
            Actions\Action::make('Loans')
                ->url(fn () => EmployeeResource::getUrl('laon', ['record' => $this->record->id])),
            Actions\Action::make('TempAdvance')
                ->url(fn () => EmployeeResource::getUrl('temp_loan', ['record' => $this->record->id])),
        ];
    }

}
