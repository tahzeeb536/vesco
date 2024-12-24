<?php

namespace App\Filament\Resources\SaleInvoiceResource\Pages;

use App\Filament\Resources\SaleInvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSaleInvoice extends ViewRecord
{
    protected static string $resource = SaleInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_sale_invoice')
                ->label('Print Invoice')
                ->color('success')
                ->url(fn () => $this->print_sale_invoice())
                ->openUrlInNewTab(),
            Actions\Action::make('print_sale_invoice_with_stamp')
                ->label('Invoice with Stamp')
                ->color('success')
                ->url(fn () => $this->print_sale_invoice_with_stamp())
                ->openUrlInNewTab(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_view'] = true;
    
        return $data;
    }

    protected function print_sale_invoice()
    {
        return route('print_sale_invoice', ['record' => $this->record->id]);
    }

    protected function print_sale_invoice_with_stamp()
    {
        return route('print_sale_invoice_with_stamp', ['record' => $this->record->id]);
    }
}
