<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpeningStockResource\Pages;
use App\Filament\Resources\OpeningStockResource\RelationManagers;
use App\Models\OpeningStock;
use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OpeningStockResource extends Resource
{
    protected static ?string $model = OpeningStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('variant_id')
                    ->label('Product Variant')
                    ->required()
                    ->searchable()
                    ->reactive()
                    ->debounce(500)
                    ->getSearchResultsUsing(function (string $query) {
                        return ProductVariant::where('name', 'like', "%{$query}%")
                            ->limit(50)
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return ProductVariant::find($value)?->name ?? '';
                    })
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $variant = ProductVariant::find($state);
                            if ($variant) {
                                $set('unit_price', $variant->vendor_price);
                                
                                $quantity = $get('quantity') ?? 0;
                                if ($quantity > 0) {
                                    $set('total_price', round($quantity * $variant->vendor_price, 2));
                                }
                            }
                        }
                    }),

                Forms\Components\Select::make('shelf_id')
                    ->label('Shelf')
                    ->options(\App\Models\Shelf::pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->debounce(500),

                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->minValue(1)
                    ->debounce(1000)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $unitPrice = $get('unit_price') ?? 0;
                        if ($state > 0 && $unitPrice > 0) {
                            $set('total_price', round($state * $unitPrice, 2));
                        }
                    }),

                Forms\Components\TextInput::make('unit_price')
                    ->label('Unit Price')
                    ->numeric()
                    ->reactive()
                    ->nullable()
                    ->default(null)
                    ->debounce(500)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $quantity = $get('quantity') ?? 0;
                        if ($state && $quantity) {
                            $set('total_price', round($state * $quantity, 2));
                        }
                    }),

                Forms\Components\TextInput::make('total_price')
                    ->numeric()
                    ->readonly()
                    ->default(null),

                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variant.id')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shelf.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
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
            'index' => Pages\ListOpeningStocks::route('/'),
            'create' => Pages\CreateOpeningStock::route('/create'),
            'view' => Pages\ViewOpeningStock::route('/{record}'),
            'edit' => Pages\EditOpeningStock::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return 'Opening Stock';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Opening Stock';
    }
}
