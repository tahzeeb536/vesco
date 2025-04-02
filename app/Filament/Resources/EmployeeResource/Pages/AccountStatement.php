<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;
use App\Models\EmployeeStatement;
use Filament\Actions;
use Filament\Forms;
use Carbon\Carbon;
use Filament\Notifications\Notification;

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
            
            Actions\Action::make('addEntry')
                ->label('Add Entry')
                ->modalHeading('Add Employee Statement')
                ->modalButton('Add')
                ->form([
                    Forms\Components\DatePicker::make('datetime')
                        ->label('Date')
                        ->required(),
                    Forms\Components\TextInput::make('details')
                        ->label('Details')
                        ->required(),
                    Forms\Components\Select::make('transaction_type')
                        ->label('Transaction Type')
                        ->options([
                            'deposit' => 'Deposit',
                            'withdraw' => 'Withdraw',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->required(),
                ])
                ->action(function(array $data): void {
                    // Attach the current employee's id to the new statement.
                    $data['employee_id'] = $this->record->id;

                    if ($data['transaction_type'] === 'deposit') {
                        $data['deposit'] = $data['amount'];
                        $data['withdraw'] = 0;
                    } else {
                        $data['withdraw'] = $data['amount'];
                        $data['deposit'] = 0;
                    }

                    $data['type'] = $data['transaction_type'];
                    
                    $datetime = Carbon::parse($data['datetime']);
                    $data['month'] = $datetime->month;
                    $data['year'] = $datetime->year;

                    unset($data['transaction_type'], $data['amount']);


                    EmployeeStatement::create($data);

                    // Refresh the record so that new statement shows up in the listing.
                    $this->record->refresh();

                    // Optionally notify the user.
                    Notification::make()
                        ->title('Success')
                        ->body('The adjustment has been saved.')
                        ->success()
                        ->send();
                }),
        ];
    }

}
