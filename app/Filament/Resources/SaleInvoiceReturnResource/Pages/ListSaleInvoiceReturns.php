<?php

namespace App\Filament\Resources\SaleInvoiceReturnResource\Pages;

use App\Filament\Resources\SaleInvoiceReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaleInvoiceReturns extends ListRecords
{
    protected static string $resource = SaleInvoiceReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
