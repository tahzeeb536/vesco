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
            Actions\Action::make('print_prt')
                ->label('Print PRT')
                ->color('success')
                ->url(fn () => $this->getPrintPrtUrl())
                ->openUrlInNewTab(),
            Actions\Action::make('print_prt')
                ->label('Print PRT W/O Price')
                ->color('success')
                ->url(fn () => $this->getPrintPrtNoPriceUrl())
                ->openUrlInNewTab(),
        ];
    }

    protected function getPrintPrtUrl()
    {
        return route('print_prt', ['record' => $this->record->id]);
    }

    protected function getPrintPrtNoPriceUrl() {
        return route('print_prt_no_price', ['record' => $this->record->id]);
    }

    public function getTitle(): string
    {
        return 'View Products Returned (PRT)';
    }
}
