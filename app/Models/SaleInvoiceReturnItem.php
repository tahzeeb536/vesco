<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoiceReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_invoice_return_id',
        'variant_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($saleItem) {
            $saleItem->updateStockEntry();
            $saleItem->sale_invoice_return->updateTotalAmount();
        });

        static::updated(function ($saleItem) {
            $saleItem->updateStockEntry();
            $saleItem->sale_invoice_return->updateTotalAmount();
        });

        static::deleting(function ($saleItem) {
            $saleItem->removeStockEntry();
        });
    }

    public function sale_invoice_return() : BelongsTo {
        return $this->belongsTo(SaleInvoiceReturn::class, 'sale_invoice_return_id', 'id');
    }

    public function variant() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function updateStockEntry()
    {
        $stock_entry = StockEntry::where('source_id', $this->sale_invoice_return_id)
            ->where('source_type', SaleInvoiceReturn::class)
            ->where('product_variant_id', $this->variant_id)
            ->first();

        if($stock_entry) {
            $stock_entry->update([
                'quantity' => $this->quantity
            ]);
        }
        else {
            StockEntry::create([
                'product_variant_id' => $this->variant_id,
                'source_id' => $this->sale_invoice_return_id,
                'source_type' => SaleInvoiceReturn::class,
                'quantity' => $this->quantity,
            ]);
        }
    }

    public function removeStockEntry()
    {
        StockEntry::where('source_id', $this->sale_invoice_return_id)
            ->where('source_type', SaleInvoiceReturn::class)
            ->where('product_variant_id', $this->variant_id)
            ->delete();
    }
}
