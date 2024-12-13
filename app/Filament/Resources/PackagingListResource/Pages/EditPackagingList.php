<?php

namespace App\Filament\Resources\PackagingListResource\Pages;

use App\Filament\Resources\PackagingListResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\PackagingBox;

class EditPackagingList extends EditRecord
{
    protected static string $resource = PackagingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $packagingList = $this->record;

        $items = json_decode($this->data['packaging_boxes'] ?? '[]', true);

        if (!empty($items)) {
            // Get existing items keyed by their ID
            $existingItems = $packagingList->boxes->keyBy('id');

            foreach ($items as $item) {
                if (isset($item['id']) && $existingItems->has($item['id'])) {
                    // Update existing item
                    $existingItem = $existingItems->get($item['id']);
                    $existingItem->update([
                        'packaging_list_id' => $packagingList->id,
                        'cartons' => $item['cartons'] ?? null,
                        'qty_cartons' => $item['qty_cartons'] ?? null,
                        'article_no' => $item['article_no'] ?? null,
                        'details' => $item['details'] ?? null,
                        'size_qty' => $item['size_qty'] ?? null,
                        'total_qty' => $item['total_qty'] ?? null,
                    ]);

                    $existingItems->forget($item['id']);
                } else {
                    // Create a new item
                    PackagingBox::create([
                        'packaging_list_id' => $packagingList->id,
                        'cartons' => $item['cartons'] ?? null,
                        'qty_cartons' => $item['qty_cartons'] ?? null,
                        'article_no' => $item['article_no'] ?? null,
                        'details' => $item['details'] ?? null,
                        'size_qty' => $item['size_qty'] ?? null,
                        'total_qty' => $item['total_qty'] ?? null,
                    ]);
                }
            }

            // Delete any remaining items that weren't part of the new data
            foreach ($existingItems as $remainingItem) {
                $remainingItem->delete();
            }
        }
    }

}
