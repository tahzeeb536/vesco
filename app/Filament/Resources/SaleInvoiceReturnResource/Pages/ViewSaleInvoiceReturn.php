<?php

namespace App\Filament\Resources\SaleInvoiceReturnResource\Pages;

use App\Filament\Resources\SaleInvoiceReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSaleInvoiceReturn extends ViewRecord
{
    protected static string $resource = SaleInvoiceReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
