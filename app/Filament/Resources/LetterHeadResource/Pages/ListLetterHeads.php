<?php

namespace App\Filament\Resources\LetterHeadResource\Pages;

use App\Filament\Resources\LetterHeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLetterHeads extends ListRecords
{
    protected static string $resource = LetterHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
