<?php

namespace App\Exports;

use App\Models\ProductVariant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StockDetailExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return ProductVariant::query()
        ->select(
            'product_variants.name as variant_name',
            'products.name_for_vendor as vendor_name',
            DB::raw('COALESCE(SUM(stock_entries.quantity), 0) as stock'),
            'shelves.name as shelf_name',
            'racks.name as rack_name',
            'rooms.name as room_name',
            'stores.name as store_name'
        )
        ->leftJoin('products', 'products.id', '=', 'product_variants.product_id')
        ->leftJoin('stock_entries', 'stock_entries.product_variant_id', '=', 'product_variants.id')
        ->leftJoin('shelves', 'shelves.id', '=', 'stock_entries.shelf_id')
        ->leftJoin('racks', 'racks.id', '=', 'shelves.rack_id')
        ->leftJoin('rooms', 'rooms.id', '=', 'racks.room_id')
        ->leftJoin('stores', 'stores.id', '=', 'rooms.store_id')
        ->groupBy(
            'product_variants.id',
            'product_variants.name',
            'products.name_for_vendor',
            'shelves.name',
            'racks.name',
            'rooms.name',
            'stores.name'
        );
    }

    public function headings(): array
    {
        return [
            'Variant Name',
            'Vendor Name',
            'Stock',
            'Shelf Name',
            'Rack Name',
            'Room Name',
            'Store Name',
        ];
    }
}
