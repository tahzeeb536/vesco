<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;

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
        ])->findOrFail($record);
    }

}
