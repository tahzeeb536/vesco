<?php

namespace App\Filament\Resources\GrnResource\Pages;

use App\Filament\Resources\GrnResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGrn extends CreateRecord
{
    protected static string $resource = GrnResource::class;

    public function getTitle(): string
    {
        return 'Create Goods Received (GRN)';
    }
}
