<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LetterHeadResource\Pages;
use App\Filament\Resources\LetterHeadResource\RelationManagers;
use App\Models\LetterHead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;


class LetterHeadResource extends Resource
{
    protected static ?string $model = LetterHead::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('date')
                    ->required()
                    ->label('Date'),
                TextInput::make('ref_no')
                    ->required()
                    ->label('Ref #'),
                TextInput::make('title')
                    ->required()
                    ->maxLength(1000)
                    ->label('Title')
                    ->columnSpanFull(),
                RichEditor::make('content')
                    ->required()
                    ->label('Content')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Date')->sortable()->searchable(),
                TextColumn::make('ref_no')->label('Reference #')->sortable()->searchable(),
                TextColumn::make('title')->label('Title')->limit(50)->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLetterHeads::route('/'),
            'create' => Pages\CreateLetterHead::route('/create'),
            'view' => Pages\ViewLetterHead::route('/{record}'),
            'edit' => Pages\EditLetterHead::route('/{record}/edit'),
        ];
    }
}
