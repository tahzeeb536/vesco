<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\PurchaseOrderItem;
use Barryvdh\DomPDF\Facade\Pdf;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $purchaseOrder = $this->record;

        $items = null;

        if (isset($this->data['order_items'])) {
            $items = json_decode($this->data['order_items'], true);
        }

        if ($items) {
            $existingItems = $purchaseOrder->items->keyBy('id');

            foreach ($items as $item) {
                if (isset($item['id']) && $existingItems->has($item['id'])) {
                    $existingItem = $existingItems->get($item['id']);
                    $existingItem->update([
                        'variant_id' => $item['variant_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                    ]);

                    $existingItems->forget($item['id']);
                } else {
                    PurchaseOrderItem::create([
                        'purchase_order_id' => $purchaseOrder->id,
                        'variant_id' => $item['variant_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                    ]);
                }
            }

            foreach ($existingItems as $remainingItem) {
                $remainingItem->delete();
            }
        }
    }

}
