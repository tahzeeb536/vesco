<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use App\Models\AdvanceSalary;
use App\Models\AdvanceSalaryBalance;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Update Monthly Deduction')
                ->form([
                    Forms\Components\TextInput::make('monthly_deduction')
                        ->numeric()
                        ->required()
                        ->label('Monthly Deduction Amount'),
                ])
                ->action(function(array $data) {
                    $balance = $this->record->advance_salary_balance;
                    if (!$balance) {
                        $balance = $this->record->advance_salary_balance()->create([
                            'total_amount' => 0,
                            'paid_amount' => 0,
                            'monthly_deduction' => 0,
                            'remaining_amount' => 0,
                        ]);
                    }
                    $balance->update([
                        'monthly_deduction' => $data['monthly_deduction'],
                    ]);
                }),
            Actions\Action::make('Pay Advance')
                ->form([
                    Forms\Components\TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->label('Amount'),
                    Forms\Components\TextInput::make('monthly_deduction')
                        ->numeric()
                        ->required()
                        ->label('Monthly Deduction Amount'),
                    Forms\Components\Textarea::make('remarks')
                        ->label('Remarks'),
                ])
                ->action(function (array $data) {
                    // Create a new Advance Salary record
                    $this->record->advance_salaries()->create([
                        'amount' => $data['amount'],
                        'advance_date' => now(),
                        'remarks' => $data['remarks'],
                    ]);

                    // Update Advance Salary Balance
                    $balance = $this->record->advance_salary_balance;
                    if (!$balance) {
                        $balance = $this->record->advance_salary_balance()->create([
                            'total_amount' => 0,
                            'paid_amount' => 0,
                            'monthly_deduction' => 0,
                            'remaining_amount' => 0,
                        ]);
                    }

                    $balance->increment('total_amount', $data['amount']);
                    $balance->increment('remaining_amount', $data['amount']);
                    $balance->update([
                        'monthly_deduction' => $data['monthly_deduction'],
                    ]);
                })
                ->label('Pay Advance'),
            
            Actions\EditAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            EmployeeResource\Widgets\AdvanceSalaryBalanceWidget::class,
            EmployeeResource\Widgets\AdvanceSalariesWidget::class,
        ];
    }
}
