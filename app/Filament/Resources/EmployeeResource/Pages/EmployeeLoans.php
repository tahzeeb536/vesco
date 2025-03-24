<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;
use App\Models\AdvanceSalary;
use App\Models\AdvanceSalaryBalance;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Actions;

class EmployeeLoans extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.resources.employee-resource.pages.employee-loans';

    public Employee $record;

    public function mount(Employee $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return 'Employee Loans';
    }

    protected function getTableQuery(): Builder
    {
        return AdvanceSalary::query()->where('employee_id', $this->record->id);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('employee.name')
                ->label('Employee'),
            TextColumn::make('advance_date')
                ->date()
                ->sortable(),
            TextColumn::make('name')
                ->label('Name'),
            TextColumn::make('remarks')
                ->label('Remarks'),
            TextColumn::make('amount')
                ->sortable()
                ->label('Amount'),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('edit')
                ->label('Edit')
                ->form(function (AdvanceSalary $record) {
                    return [
                        Forms\Components\DatePicker::make('advance_date')
                            ->required()
                            ->label('Advance Date')
                            ->default($record->advance_date),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Name')
                            ->default($record->name),

                        Forms\Components\TextInput::make('remarks')
                            ->label('Remarks')
                            ->default($record->remarks),

                        Forms\Components\TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->label('Amount')
                            ->default($record->amount),
                    ];
                })
                ->action(function (array $data, AdvanceSalary $record): void {
                    $oldAmount = $record->amount;
                    $record->update($data);

                    $difference = $data['amount'] - $oldAmount;

                    $balance = $record->employee->advance_salary_balance;
                    if ($balance) {
                        $balance->increment('total_amount', $difference);
                        $balance->increment('remaining_amount', $difference);
                    }

                    Notification::make()
                        ->title('Loan Updated Successfully!')
                        ->success()
                        ->send();
                })
                ->modalHeading('Edit Loan')
                ->modalButton('Update'),
            
            Tables\Actions\DeleteAction::make(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
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
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->required(),
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
                ->label('New Loan'),
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
                })
                ->label('Update Monthly Deduction'),
            Actions\Action::make('profile')
                ->label('Profile')
                ->url(fn () => EmployeeResource::getUrl('view', ['record' => $this->record->id]))
                ->color('primary'),
            Actions\Action::make('Attendance')
                ->url(fn () => EmployeeResource::getUrl('attendance', ['record' => $this->record->id])),
            
            Actions\Action::make('TempAdvance')
                ->url(fn () => EmployeeResource::getUrl('temp_loan', ['record' => $this->record->id])),
        ];
    }

    public function getLoanStats(): array
    {
        $balance = AdvanceSalaryBalance::where('employee_id', $this->record->id)->first();

        return [
            [
                'label' => 'Total Loan Amount',
                'value' => number_format($balance->total_amount ?? 0, 2),
            ],
            [
                'label' => 'Paid Amount',
                'value' => number_format($balance->paid_amount ?? 0, 2),
            ],
            [
                'label' => 'Monthly Deduction',
                'value' => number_format($balance->monthly_deduction ?? 0, 2),
            ],
            [
                'label' => 'Remaining Balance',
                'value' => number_format($balance->remaining_amount ?? 0, 2),
                'color' => ($balance && $balance->remaining_amount > 0) ? 'text-danger-600' : 'text-success-600',
            ],
        ];
    }



}
