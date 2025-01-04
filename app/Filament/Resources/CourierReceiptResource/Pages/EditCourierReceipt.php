<?php

namespace App\Filament\Resources\CourierReceiptResource\Pages;

use App\Filament\Resources\CourierReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourierReceipt extends EditRecord
{
    protected static string $resource = CourierReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
