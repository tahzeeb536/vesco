<?php

namespace App\Filament\Resources\GrnrResource\Pages;

use App\Filament\Resources\GrnrResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGrnrs extends ListRecords
{
    protected static string $resource = GrnrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Goods Returned (GRNR)';
    }
}
