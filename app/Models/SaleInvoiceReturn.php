<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoiceReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_invoice_id',
        'sale_invoice_return_number',
        'return_date',
        'total_amount',
        'reason'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (is_null($sale->sale_invoice_return_number)) {
                $lastInvoice = self::latest('id')->first();
                $sale->sale_invoice_return_number = $lastInvoice ?  'SIR-' . $lastInvoice->id + 1 : 'SIR-' . 1;

            }
        });

        static::deleting(function ($sale) {
            foreach ($sale->items as $item) {
                $item->delete();
            }

            // StockEntry::where('source_id', $sale->id)
            // ->where('source_type', self::class)
            // ->delete();
        });

    }

    public function sale_invoice () : BelongsTo {
        return $this->belongsTo(SaleInvoice::class, 'sale_invoice_id', 'id');
    }

    public function items() : HasMany {
        return $this->hasMany(SaleInvoiceReturnItem::class, 'sale_invoice_return_id', 'id');
    }

    public function stockEntries()
    {
        return $this->morphMany(StockEntry::class, 'source');
    }

    public function updateTotalAmount()
    {
        $totalAmount = $this->items()->sum('total_price');

        if ($this->total_amount !== $totalAmount) {
            $this->total_amount = $totalAmount;
            $this->saveQuietly();
        }
    }

}
