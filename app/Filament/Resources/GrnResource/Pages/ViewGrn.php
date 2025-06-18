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
            Actions\Action::make('print_pr')
                ->label('Print PR')
                ->color('success')
                ->url(fn () => $this->getPrintPrUrl())
                ->openUrlInNewTab(),
            Actions\Action::make('print_pr')
                ->label('Print PR W/O Price')
                ->color('success')
                ->url(fn () => $this->getPrintPrNoPriceUrl())
                ->openUrlInNewTab(),
        ];
    }

    protected function getPrintPrUrl()
    {
        return route('print_pr', ['record' => $this->record->id]);
    }

    protected function getPrintPrNoPriceUrl() {
        return route('print_pr_no_price', ['record' => $this->record->id]);
    }

    public function getTitle(): string
    {
        return 'View Products Received';
    }
}
