<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\AdvanceSalaryBalance;

class AdvanceSalaryBalanceWidget extends BaseWidget
{
    public $employee;

    public function mount($record)
    {
        $this->employee = $record;
    }

    protected function getStats(): array
    {
        $employeeId = $this->employee->id;

        $balance = AdvanceSalaryBalance::where('employee_id', $employeeId)->first();

        return [
            Stat::make('Total Loan Amount', $balance->total_amount ?? 0),

            Stat::make('Paid Amount', $balance->paid_amount ?? 0),
            
            Stat::make('Monthly Deduction', $balance->monthly_deduction ?? 0),

            Stat::make('Remaining Balance', $balance->remaining_amount ?? 0)
                ->color($balance && $balance->remaining_amount > 0 ? 'danger' : 'success'),
        ];
    }
}
