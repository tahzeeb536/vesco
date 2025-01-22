<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Pages\Actions\ButtonAction;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            ButtonAction::make('download_admin_file')
                ->label('Download Admin File')
                ->color('primary')
                ->url($this->record->order_file_admin ? asset('storage/' . $this->record->order_file_admin) : null)
                ->disabled(!$this->record->order_file_admin)
                ->openUrlInNewTab(),

            ButtonAction::make('download_manager_file')
                ->label('Download Manager File')
                ->color('success')
                ->url($this->record->order_file_manager ? asset('storage/' . $this->record->order_file_manager) : null)
                ->disabled(!$this->record->order_file_manager)
                ->openUrlInNewTab(),
        ];
    }
}
