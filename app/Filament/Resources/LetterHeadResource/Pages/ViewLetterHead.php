<?php

namespace App\Filament\Resources\LetterHeadResource\Pages;

use App\Filament\Resources\LetterHeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLetterHead extends ViewRecord
{
    protected static string $resource = LetterHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
