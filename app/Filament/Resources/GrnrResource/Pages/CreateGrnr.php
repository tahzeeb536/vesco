<?php

namespace App\Filament\Resources\GrnrResource\Pages;

use App\Filament\Resources\GrnrResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGrnr extends CreateRecord
{
    protected static string $resource = GrnrResource::class;

    public function getTitle(): string
    {
        return 'Create Goods Returned (GRNR)';
    }
}
