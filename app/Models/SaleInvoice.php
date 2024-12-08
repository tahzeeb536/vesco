<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'note',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (is_null($sale->invoice_number)) {
                $lastInvoice = self::latest('id')->first();
                $sale->invoice_number = $lastInvoice ?  'SI-' . $lastInvoice->id + 1 : 'SI-' . 1;

            }
        });

        static::deleting(function ($sale) {
            foreach ($sale->items as $item) {
                $item->delete();
            }

            StockEntry::where('source_id', $sale->id)
            ->where('source_type', self::class)
            ->delete();
        });

    }

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function stockEntries()
    {
        return $this->morphMany(StockEntry::class, 'source');
    }

    public function items() : HasMany {
        return $this->hasMany(SaleInvoiceItem::class, 'sale_invoice_id', 'id');
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
