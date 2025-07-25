<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShelfResource\Pages;
use App\Filament\Resources\ShelfResource\RelationManagers;
use App\Models\Shelf;
use App\Models\Rack;
use App\Models\Room;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShelfResource extends Resource
{
    protected static ?string $model = Shelf::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationGroup = 'Stores';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('room_id')
                    ->label('Room')
                    ->options(Room::pluck('name', 'id'))
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('rack_id', null))
                    ->required()
                    ->afterStateHydrated(function ($state, callable $set, $record) {
                        // Only set on edit, not create
                        if ($record && $record->rack) {
                            $set('room_id', $record->rack->room_id);
                        }
                    }),
                Forms\Components\Select::make('rack_id')
                    ->label('Rack')
                    ->options(function (callable $get) {
                        $roomId = $get('room_id');
                        if (!$roomId) {
                            return [];
                        }
                        return Rack::where('room_id', $roomId)->pluck('name', 'id');
                    })
                    ->reactive()
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        true => 'Active',
                        false => 'Inactive',
                    ])
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rack.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rack.room.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListShelves::route('/'),
            'create' => Pages\CreateShelf::route('/create'),
            'view' => Pages\ViewShelf::route('/{record}'),
            'edit' => Pages\EditShelf::route('/{record}/edit'),
        ];
    }
}
