<?php

namespace App\Filament\Resources\GrnResource\Pages;

use App\Filament\Resources\GrnResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGrn extends ViewRecord
{
    protected static string $resource = GrnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'View Products Received';
    }
}
