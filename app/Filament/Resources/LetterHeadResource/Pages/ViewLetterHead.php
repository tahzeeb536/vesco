<?php

namespace App\Filament\Resources\LetterHeadResource\Pages;

use App\Filament\Resources\LetterHeadResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLetterHead extends ViewRecord
{
    protected static string $resource = LetterHeadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('print_letter_head_with_logo')
                ->label('Print With Logo')
                ->color('success')
                ->url(fn () => $this->print_letter_head_with_logo())
                ->openUrlInNewTab(),
            
            Actions\Action::make('print_letter_head_without_logo')
                ->label('Print Without Logo')
                ->color('success')
                ->url(fn () => $this->print_letter_head_without_logo())
                ->openUrlInNewTab(),

            Actions\Action::make('print_letter_head_without_stamp')
                ->label('Print Without Stamp')
                ->color('success')
                ->url(fn () => $this->print_letter_head_without_stamp())
                ->openUrlInNewTab(),
        ];
    }

    protected function print_letter_head_with_logo()
    {
        return route('print_letter_head_with_logo', ['record' => $this->record->id]);
    }

    protected function print_letter_head_without_logo()
    {
        return route('print_letter_head_without_logo', ['record' => $this->record->id]);
    }

    protected function print_letter_head_without_stamp()
    {
        return route('print_letter_head_without_stamp', ['record' => $this->record->id]);
    }
}
