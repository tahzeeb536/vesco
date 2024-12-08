<?php

namespace App\Filament\Resources\ShelfResource\Pages;

use App\Filament\Resources\ShelfResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewShelf extends ViewRecord
{
    protected static string $resource = ShelfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
