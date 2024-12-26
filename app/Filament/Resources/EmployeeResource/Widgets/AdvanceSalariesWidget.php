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
            ->query(
                AdvanceSalary::query()->where('employee_id', $this->employee->id)
            )
            ->columns([
                TextColumn::make('amount')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
                TextColumn::make('advance_date')->label('Advance Date'),
                TextColumn::make('remarks')->label('Remarks'),
            ]);
    }
}
