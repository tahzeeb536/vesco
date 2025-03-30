<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\EmployeeResource\Pages\EmployeeAttendance;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('Attendance')
                ->url(fn () => EmployeeResource::getUrl('attendance', ['record' => $this->record->id])),
            Actions\Action::make('Loans')
                ->url(fn () => EmployeeResource::getUrl('laon', ['record' => $this->record->id])),
            Actions\Action::make('TempAdvance')
                ->url(fn () => EmployeeResource::getUrl('temp_loan', ['record' => $this->record->id])),
            Actions\Action::make('Account Statement')
                ->url(fn () => EmployeeResource::getUrl('account-statement', ['record' => $this->record->id])),
        ];
    }

}
