<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\AdvanceSalary;
use Filament\Tables\Columns\TextColumn;

class AdvanceSalariesWidget extends BaseWidget
{

    public $employee;

    protected int | string | array $columnSpan = 'full';

    public function mount($record)
    {
        $this->employee = $record;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Employee Loans')
            ->query(
                AdvanceSalary::query()->where('employee_id', $this->employee->id)->orderBy('id', 'desc')
            )
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                TextColumn::make('advance_date')->label('Date'),
                TextColumn::make('name')->label('Name'),
                TextColumn::make('remarks')->label('Remarks'),
            ]);
    }
}
