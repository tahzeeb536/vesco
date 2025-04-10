<?php

namespace App\Filament\Resources\CourierReceiptResource\Pages;

use App\Filament\Resources\CourierReceiptResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCourierReceipt extends ViewRecord
{
    protected static string $resource = CourierReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_courier_receipt')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('primary')
                ->url(fn () => route('print_courier_receipt', [
                    'id' => $this->record->id,
                ]))
                ->openUrlInNewTab(),
        ];
    }
}
