<?php

namespace App\Filament\Resources\VendorProductPriceResource\Pages;

use App\Filament\Resources\VendorProductPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVendorProductPrices extends ListRecords
{
    protected static string $resource = VendorProductPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
