<?php

namespace App\Filament\Resources\VendorProductPriceResource\Pages;

use App\Filament\Resources\VendorProductPriceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVendorProductPrice extends EditRecord
{
    protected static string $resource = VendorProductPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
