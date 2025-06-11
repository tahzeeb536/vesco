<?php

namespace App\Filament\Resources\GrnResource\Pages;

use App\Filament\Resources\GrnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGrn extends EditRecord
{
    protected static string $resource = GrnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $updatedRecord = parent::handleRecordUpdate($record, $data);

        $this->redirect($this->getResource()::getUrl('view', ['record' => $record->getKey()]));

        return $updatedRecord;
    }

    public function getTitle(): string
    {
        return 'Edit Products Received';
    }
}
