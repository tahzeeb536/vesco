<?php

namespace App\Filament\Resources\OpeningStockResource\Pages;

use App\Filament\Resources\OpeningStockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpeningStock extends EditRecord
{
    protected static string $resource = OpeningStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
