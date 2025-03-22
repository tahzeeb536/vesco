<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;
use App\Models\Employee;
use App\Models\AdvanceSalary;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Notifications\Notification;

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
            TextColumn::make('id')
                ->label('ID')
                ->sortable(),
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
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

}
