<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use App\Models\ProductVariant;
use App\Models\StockEntry;
use App\Models\Product;
use App\Models\Shelf;

class StockDetail extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static string $view = 'filament.pages.stock-detail';

    protected function getTableQuery()
    {
        return ProductVariant::query()
            // Vendor name (from products)
            ->addSelect([
                'name_for_vendor' => Product::select('name_for_vendor')
                    ->whereColumn('id', 'product_variants.product_id')
            ])
            // Stock calculation
            ->addSelect([
                'stock' => StockEntry::selectRaw('COALESCE(SUM(quantity), 0)')
                    ->whereColumn('product_variant_id', 'product_variants.id')
            ])
            // Shelf name
            ->addSelect([
                'shelf_name' => StockEntry::select('shelves.name')
                    ->join('shelves', 'shelves.id', '=', 'stock_entries.shelf_id')
                    ->whereColumn('stock_entries.product_variant_id', 'product_variants.id')
                    ->limit(1)
            ])
            // Rack name
            ->addSelect([
                'rack_name' => StockEntry::select('racks.name')
                    ->join('shelves', 'shelves.id', '=', 'stock_entries.shelf_id')
                    ->join('racks', 'racks.id', '=', 'shelves.rack_id')
                    ->whereColumn('stock_entries.product_variant_id', 'product_variants.id')
                    ->limit(1)
            ])
            // Room name
            ->addSelect([
                'room_name' => StockEntry::select('rooms.name')
                    ->join('shelves', 'shelves.id', '=', 'stock_entries.shelf_id')
                    ->join('racks', 'racks.id', '=', 'shelves.rack_id')
                    ->join('rooms', 'rooms.id', '=', 'racks.room_id')
                    ->whereColumn('stock_entries.product_variant_id', 'product_variants.id')
                    ->limit(1)
            ])
            // Store name
            ->addSelect([
                'store_name' => StockEntry::select('stores.name')
                    ->join('shelves', 'shelves.id', '=', 'stock_entries.shelf_id')
                    ->join('racks', 'racks.id', '=', 'shelves.rack_id')
                    ->join('rooms', 'rooms.id', '=', 'racks.room_id')
                    ->join('stores', 'stores.id', '=', 'rooms.store_id')
                    ->whereColumn('stock_entries.product_variant_id', 'product_variants.id')
                    ->limit(1)
            ]);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Variant Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('product.name_for_vendor')
                ->label('Vendor Name')
                ->sortable()
                ->searchable(),
            
            TextColumn::make('stock')
                ->label('Stock')
                ->sortable()
                ->formatStateUsing(fn($state) => $state ?? 0),

            TextColumn::make('shelf_name')
                ->label('Shelf Name')
                ->sortable()
                ->formatStateUsing(fn($state) => $state ?? 'N/A'),
            
            // TextColumn::make('rack_name')
            //         ->label('Rack Name')
            //         ->formatStateUsing(fn($state) => $state ?? 'N/A'),
                    
            // TextColumn::make('room_name')
            //     ->label('Room Name')
            //     ->formatStateUsing(fn($state) => $state ?? 'N/A'),

            // TextColumn::make('store_name')
            //     ->label('Store Name')
            //     ->formatStateUsing(fn($state) => $state ?? 'N/A'),

        ];
    }
}
