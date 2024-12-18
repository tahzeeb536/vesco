<?php

namespace App\Filament\Resources\PackagingListResource\Pages;

use App\Filament\Resources\PackagingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Crypt;

class ViewPackagingList extends ViewRecord
{
    protected static string $resource = PackagingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_packaging_list_with_logo')
                ->label('Print With Logo')
                ->color('success')
                ->url(fn () => $this->print_packaging_list_with_logo())
                ->openUrlInNewTab(),
            Actions\Action::make('print_packaging_list_with_stamp')
                ->label('Print With Stamp')
                ->color('success')
                ->url(fn () => $this->print_packaging_list_with_stamp())
                ->openUrlInNewTab(),
            Actions\Action::make('share_packaging_list')
                ->label('Share Packaging List')
                ->color('success')
                ->url(fn () => $this->share_packaging_list())
                ->openUrlInNewTab(),
                
        ];
    }

    protected function print_packaging_list_with_logo()
    {
        return route('print_packaging_list_with_logo', ['record' => $this->record->id]);
    }

    protected function print_packaging_list_with_stamp()
    {
        return route('print_packaging_list_with_stamp', ['record' => $this->record->id]);
    }

    public function share_packaging_list() {
        return route('share_packaging_list', ['record' => Crypt::encrypt($this->record->id)]);
    }
}
