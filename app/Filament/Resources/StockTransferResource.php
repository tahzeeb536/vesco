<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockTransferResource\Pages;
use App\Filament\Resources\StockTransferResource\RelationManagers;
use App\Models\StockTransfer;
use App\Models\ProductVariant;
use App\Models\Shelf;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;

class StockTransferResource extends Resource
{
    protected static ?string $model = StockTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Stock Management';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('variant_id')
                    ->label('Product Variant')
                    ->required()
                    ->searchable()
                    ->debounce(700)
                    ->getSearchResultsUsing(function (string $search) {
                        return ProductVariant::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return ProductVariant::find($value)?->name ?? '';
                    })
                    ->afterStateUpdated(function($state, callable $set) {
                        $stock_entry = StockEntry::where('product_variant_id', $state)->whereNotNull('shelf_id')->first();
                        if($stock_entry) {
                            $shelf = Shelf::find($stock_entry->shelf_id);
                            if ($shelf) {
                                $set('source_shelf_id', $shelf->id);
                                $set('source_shelf_helper', null);
                            }
                        }
                        else {
                            $set('source_shelf_id', null);
                            $set('source_shelf_helper', 'Product not found in shelf');
                            Notification::make()
                                ->title('Invalid Product Variant')
                                ->danger()
                                ->body('No stock entry found for the selected product variant.')
                                ->send();
                        }
                    }),
                
                Forms\Components\Hidden::make('source_shelf_helper')->default(null),
                
                Forms\Components\Select::make('source_shelf_id')
                    ->label('Shelf From')
                    ->required()
                    ->searchable()
                    ->debounce(700)
                    ->disabled()
                    ->helperText(fn (callable $get) => $get('source_shelf_helper'))
                    ->getOptionLabelUsing(function ($value) {
                        return Shelf::find($value)?->name ?? '';
                    }),
                    
                Forms\Components\Select::make('destination_shelf_id')
                    ->label('Shelf To')
                    // ->relationship('destinationShelf', 'name')
                    ->getSearchResultsUsing(function (string $search) {
                        return Shelf::query()
                            ->where('name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(function ($value) {
                        return Shelf::find($value)?->name ?? '';
                    })
                    ->required()
                    ->searchable()
                    ->debounce(700),

                Forms\Components\Textarea::make('reason')
                    ->label('Reason to transfer')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('variant.name')
                    ->label('Variant')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sourceShelf.name')
                    ->label('Shelf From')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('destinationShelf.name')
                    ->label('Shelf To')
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
                //Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListStockTransfers::route('/'),
            'create' => Pages\CreateStockTransfer::route('/create'),
            'view' => Pages\ViewStockTransfer::route('/{record}'),
            // 'edit' => Pages\EditStockTransfer::route('/{record}/edit'),
        ];
    }
}
