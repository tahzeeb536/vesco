<?php

namespace App\Filament\Resources\StockEntryResource\Pages;

use App\Filament\Resources\StockEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Exports\StockEntriesExport;
use Maatwebsite\Excel\Facades\Excel;

class ListStockEntries extends ListRecords
{
    protected static string $resource = StockEntryResource::class;

    protected function getHeaderActions(): array
    {
       return [
        Actions\Action::make('export')
            ->label('Export Stock Entries')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                return Excel::download(new StockEntriesExport(), 'stock-entries.xlsx');
            })
            ->color('primary'),
        ];
    }
}
