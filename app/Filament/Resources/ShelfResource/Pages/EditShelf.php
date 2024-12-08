<?php

namespace App\Filament\Resources\ShelfResource\Pages;

use App\Filament\Resources\ShelfResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShelf extends EditRecord
{
    protected static string $resource = ShelfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
