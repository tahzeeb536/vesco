<?php

namespace App\Filament\Resources\GrnResource\Pages;

use App\Filament\Resources\GrnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGrns extends ListRecords
{
    protected static string $resource = GrnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Products Received';
    }
}
