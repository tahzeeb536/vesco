<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockEntryResource\Pages;
use App\Filament\Resources\StockEntryResource\RelationManagers;
use App\Models\StockEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockEntryResource extends Resource
{
    protected static ?string $model = StockEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-pointing-in';
    protected static ?string $navigationGroup = 'Reports';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('productVariant.name')->label('Variant'),
                Tables\Columns\TextColumn::make('source_type')
                    ->label('Transaction Type')
                    ->formatStateUsing(function ($state) {
                        $map = [
                            'Grn' => 'Products Received',
                            'Grnr' => 'Products Returned',
                            'OpeningStock' => 'Opening Stock',
                            'StockAdjustment' => 'Stock Adjustment',
                            'StockTransfer' => 'Stock Transfer',
                            'SaleInvoice' => 'Sale Invoice',
                            'SaleInvoiceReturn' => 'Sale Invoice Return',
                        ];

                        $class = class_basename($state);

                        return $map[$class] ?? $class;
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->orWhereHas('productVariant', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', '%' . $search . '%');
                        });
                    }),
                Tables\Columns\TextColumn::make('shelf.name')->label('Shelf'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('created_at')->date()->sortable()->label('Date'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source_type')
                    ->label('Transaction Type')
                    ->options([
                        'App\Models\Grn' => 'Products Received',
                        'App\Models\Grnr' => 'Products Returned',
                        'App\Models\OpeningStock' => 'Opening Stock',
                        'App\Models\StockAdjustment' => 'Stock Adjustment',
                        'App\Models\StockTransfer' => 'Stock Transfer',
                        'App\Models\SaleInvoice' => 'Sale Invoice',
                        'App\Models\SaleInvoiceReturn' => 'Sale Invoice Return',
                    ])
                    ->query(function (Builder $query, $state) {
                        $value = $state['value'] ?? null;
                        if ($value !== null && $value !== '') {
                            $query->where('source_type', $value);
                        }
                        return $query;
                    }),
                ])
            ->actions([])
            ->bulkActions([]);
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
            'index' => Pages\ListStockEntries::route('/'),
            // 'create' => Pages\CreateStockEntry::route('/create'),
            // 'edit' => Pages\EditStockEntry::route('/{record}/edit'),
        ];
    }
}
