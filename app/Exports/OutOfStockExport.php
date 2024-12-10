<?php

namespace App\Exports;

use App\Models\ProductVariant;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OutOfStockExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return ProductVariant::query()->outOfStock();
    }

    // public function chunkSize(): int
    // {
    //     return 500;
    // }

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
