<?php

namespace App\Filament\Resources\OpeningStockResource\Pages;

use App\Filament\Resources\OpeningStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOpeningStocks extends ListRecords
{
    protected static string $resource = OpeningStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
