<?php

namespace App\Exports;

use App\Models\StockEntry;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockEntriesExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return StockEntry::query()
            ->with(['productVariant', 'shelf']); 
    }

    public function headings(): array
    {
        return [
            'ID',
            'Variant Name',
            'Transaction Type',
            'Shelf Name',
            'Quantity',
            'Date',
        ];
    }

    public function map($entry): array
    {
        $transactionTypeMap = [
            'Grn' => 'Products Received',
            'Grnr' => 'Products Returned',
            'OpeningStock' => 'Opening Stock',
            'StockAdjustment' => 'Stock Adjustment',
            'StockTransfer' => 'Stock Transfer',
            'SaleInvoice' => 'Sale Invoice',
            'SaleInvoiceReturn' => 'Sale Invoice Return',
        ];

        $class = class_basename($entry->source_type);

        return [
            $entry->id,
            $entry->productVariant?->name,
            $transactionTypeMap[$class] ?? $class,
            $entry->shelf?->name,
            $entry->quantity,
            optional($entry->created_at)->format('Y-m-d'),
        ];
    }
}
