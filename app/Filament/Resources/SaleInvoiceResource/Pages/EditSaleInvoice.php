<?php

namespace App\Filament\Resources\SaleInvoiceResource\Pages;

use App\Filament\Resources\SaleInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\SaleInvoiceItem;

class EditSaleInvoice extends EditRecord
{
    protected static string $resource = SaleInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $saleInvoice = $this->record;

        $items = null;

        if (isset($this->data['order_items'])) {
            $items = json_decode($this->data['order_items'], true);
        }

        if ($items) {
            $existingItems = $saleInvoice->items->keyBy('id');

            foreach ($items as $item) {

                $variant = \App\Models\ProductVariant::find($item['variant_id']);

                $data = [
                    'variant_id'    => $item['variant_id'],
                    'product_name'  => $variant?->product?->name,
                    'article_number'=> $variant?->product?->article_number,
                    'size'          => $variant?->size?->name,
                    'color'         => $variant?->color?->name,
                    'quantity'      => $item['quantity'],
                    'unit_price'    => $item['unit_price'],
                    'total_price'   => $item['total_price'],
                ];

                if (isset($item['id']) && $existingItems->has($item['id'])) {
                    // Update the existing record with new details
                    $existingItem = $existingItems->get($item['id']);
                    $existingItem->update($data);
    
                    // Remove from the collection as it's processed
                    $existingItems->forget($item['id']);
                } else {
                    // Create a new sale invoice item record with full details
                    \App\Models\SaleInvoiceItem::create(array_merge([
                        'sale_invoice_id' => $saleInvoice->id,
                    ], $data));
                }
            }

            foreach ($existingItems as $remainingItem) {
                $remainingItem->delete();
            }
        }
    }
}
