<?php

namespace App\Filament\Resources\GrnrResource\Pages;

use App\Filament\Resources\GrnrResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGrnr extends ViewRecord
{
    protected static string $resource = GrnrResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'View Goods Returned (GRNR)';
    }
}
