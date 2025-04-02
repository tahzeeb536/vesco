<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\TempLoan;
use Filament\Forms;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;
use Filament\Actions;

class TempLoans extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    // Declare a public property to hold the Employee record.
    public Employee $record;

    protected static string $resource = EmployeeResource::class;
    protected static string $view = 'filament.resources.employee-resource.pages.temp-loans';

    // Use the mount method to bind the passed record from the route.
    public function mount(Employee $record): void
    {
        $this->record = $record;
    }

    public function getTitle(): string
    {
        return 'Temp Advance';
    }

    /**
     * Filter the TempLoan records by the current employee.
     */
    protected function getTableQuery(): Builder
    {
        return TempLoan::query()->where('employee_id', $this->record->id);
    }

    /**
     * Define the table columns.
     */
    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('employee.name')
                ->label('Employee Name'),
            TextColumn::make('date')
                ->date() // formats the date value
                ->sortable(),
            TextColumn::make('details')
                ->limit(50)
                ->label('Details'),
            TextColumn::make('amount')
                ->sortable()
                ->label('Amount'),
        ];
    }


    /**
     * Define row actions (edit, delete, etc.) if needed.
     */
    protected function getTableActions(): array
    {
        return [
            // Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    /**
     * Define header actions including the "New Temp Loan" button with a popup modal form.
     */
    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('New Temp Advance')
                ->modalHeading('Create Temp Advance')
                ->modalButton('Create')
                ->form([
                    Forms\Components\TextInput::make('amount')
                        ->label('Amount')
                        ->numeric()
                        ->required(),
                    Forms\Components\DatePicker::make('date')
                        ->label('Loan Date')
                        ->required(),
                    Forms\Components\Textarea::make('details')
                        ->label('Details')
                        ->required(),
                    
                ])
                ->action(function (array $data): void {
                    TempLoan::create(array_merge($data, [
                        'employee_id' => $this->record->id,
                    ]));

                    Notification::make()
                        ->title('Temp advance created successfully')
                        ->success()
                        ->send();
                }),
        ];
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
        ];
    }
}
