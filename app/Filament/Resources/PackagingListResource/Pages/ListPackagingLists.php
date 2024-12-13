<?php

namespace App\Filament\Resources\PackagingListResource\Pages;

use App\Filament\Resources\PackagingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackagingLists extends ListRecords
{
    protected static string $resource = PackagingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
