<?php

namespace App\Filament\Resources\SaleInvoiceReturnResource\Pages;

use App\Filament\Resources\SaleInvoiceReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaleInvoiceReturn extends EditRecord
{
    protected static string $resource = SaleInvoiceReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
