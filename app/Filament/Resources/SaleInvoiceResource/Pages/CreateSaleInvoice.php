<?php

namespace App\Filament\Resources\SaleInvoiceResource\Pages;

use App\Filament\Resources\SaleInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\SaleInvoiceItem;
use App\Models\ProductVariant;

class CreateSaleInvoice extends CreateRecord
{
    protected static string $resource = SaleInvoiceResource::class;

    public $items;

    protected function afterCreate(): void
    {
        $saleInvoice = $this->record;

        $saleInvoice->refresh();

        $items=null;

        if (isset($this->data['order_items'])) {
            $items = json_decode($this->data['order_items'], true);
        }

        if($items) {
            foreach ($items as $item) {
                $variant = ProductVariant::where('id', $item['variant_id'])->first();
                SaleInvoiceItem::create([
                    'sale_invoice_id' => $saleInvoice->id,
                    'variant_id' => $item['variant_id'],
                    'product_name' => $variant?->product?->name,
                    'article_number' => $variant?->product?->article_number,
                    'size' => $variant?->size?->name,
                    'color' => $variant?->color?->name,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }
        }
        
    }
}
