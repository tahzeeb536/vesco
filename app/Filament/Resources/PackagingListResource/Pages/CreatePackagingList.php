<?php

namespace App\Filament\Resources\PackagingListResource\Pages;

use App\Filament\Resources\PackagingListResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\PackagingBox;

class CreatePackagingList extends CreateRecord
{
    protected static string $resource = PackagingListResource::class;

    public $boxes;

    protected function afterCreate(): void
    {
        $packagingList = $this->record;

        $boxes=null;

        if (isset($this->data['packaging_boxes'])) {
            $boxes = json_decode($this->data['packaging_boxes'], true);
        }

        if($boxes) {
            foreach ($boxes as $box) {
                PackagingBox::create([
                    'packaging_list_id' => $packagingList->id,
                    'cartons' => $box['cartons'],
                    'qty_cartons' => $box['qty_cartons'],
                    'article_no' => $box['article_no'],
                    'details' => $box['details'],
                    'size_qty' => $box['size_qty'],
                    'total_qty' => $box['total_qty'],
                ]);
            }
        }
        
    }
}
