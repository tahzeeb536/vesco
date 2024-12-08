<?php

namespace App\Filament\Resources\GrnrResource\Pages;

use App\Filament\Resources\GrnrResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGrnr extends EditRecord
{
    protected static string $resource = GrnrResource::class;

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
        return 'Edit Goods Returned (GRNR)';
    }
}
