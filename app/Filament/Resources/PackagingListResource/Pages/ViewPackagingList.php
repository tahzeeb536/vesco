<?php

namespace App\Filament\Resources\PackagingListResource\Pages;

use App\Filament\Resources\PackagingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPackagingList extends ViewRecord
{
    protected static string $resource = PackagingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
