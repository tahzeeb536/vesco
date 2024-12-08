<?php

namespace App\Filament\Resources\SaleInvoiceResource\Pages;

use App\Filament\Resources\SaleInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\SaleInvoiceItem;

class CreateSaleInvoice extends CreateRecord
{
    protected static string $resource = SaleInvoiceResource::class;

    public $items;

    protected function afterCreate(): void
    {
        $saleInvoice = $this->record;

        $items=null;

        if (isset($this->data['order_items'])) {
            $items = json_decode($this->data['order_items'], true);
        }

        if($items) {
            foreach ($items as $item) {
                SaleInvoiceItem::create([
                    'sale_invoice_id' => $saleInvoice->id,
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }
        }
        
    }
}
