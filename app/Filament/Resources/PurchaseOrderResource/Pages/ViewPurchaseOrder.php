<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Filament\Resources\PurchaseOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\viewRecord;
use Filament\Forms;
use App\Models\PurchaseOrderItem;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_po')
                ->label('Print P.O')
                ->color('success')
                ->url(fn () => $this->getPrintPoUrl())
                ->openUrlInNewTab(),
            Actions\Action::make('print_po')
                ->label('Print P.O W/O Price')
                ->color('success')
                ->url(fn () => $this->getPrintPoNoPriceUrl())
                ->openUrlInNewTab(),
        ];
    }

    protected function getPrintPoUrl()
    {
        return route('print_po', ['record' => $this->record->id]);
    }

    protected function getPrintPoNoPriceUrl() {
        return route('print_po_no_price', ['record' => $this->record->id]);
    }


    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_view'] = true;
    
        return $data;
    }

}
