<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\Grnr;
use App\Filament\Resources\GrnrResource;

class GrnrCountWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Card::make('Total PRTs', Grnr::count())
                ->description('Total products returned')
                ->descriptionIcon('heroicon-o-arrow-uturn-left')
                ->color('warning')
                ->url(GrnrResource::getUrl())
                ->extraAttributes(['class' => 'cursor-pointer']),
        ];
    }
}
