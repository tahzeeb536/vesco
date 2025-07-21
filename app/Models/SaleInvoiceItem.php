<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SaleInvoice;

class SaleInvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_invoice_id',
        'variant_id',
        'product_name',
        'article_number',
        'size',
        'color',
        'quantity',
        'unit_price',
        'total_price'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($saleItem) {
            $saleItem->updateStockEntry();
            $saleItem->sale_invoice->updateTotalAmount();
        });

        static::updated(function ($saleItem) {
            $saleItem->updateStockEntry();
            $saleItem->sale_invoice->updateTotalAmount();
        });

        // static::deleting(function ($saleItem) {
        //     $saleItem->removeStockEntry();
        // });
    }

    public function sale_invoice() : BelongsTo {
        return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id', 'id');
    }

    public function variant() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function updateStockEntry()
    {
        $stock_entry = StockEntry::where('source_id', $this->sale_invoice_id)
            ->where('source_type', SaleInvoice::class)
            ->where('product_variant_id', $this->variant_id)
            ->first();

        if($stock_entry) {
            $stock_entry->update([
                'quantity' => -1 * $this->quantity
            ]);
        }
        else {
            StockEntry::create([
                'product_variant_id' => $this->variant_id,
                'source_id' => $this->sale_invoice_id,
                'source_type' => SaleInvoice::class,
                'quantity' => -1 * $this->quantity,
            ]);
        }
    }

    public function removeStockEntry()
    {
        StockEntry::where('source_id', $this->sale_invoice_id)
            ->where('source_type', SaleInvoice::class)
            ->where('product_variant_id', $this->variant_id)
            ->delete();
    }
}
