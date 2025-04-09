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
        'ntn',
        'financial_instrument_no',
        'bank_name',
        'shipping',
        'port_of_loading',
        'port_of_discharge',
        'term',
        'hs_code',
        'po_no',
        'frieignt_charges',
        'tax_charges',
        'total_amount',
        'paid_amount',
        'pending_amount',
        'note',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($sale) {
            $sale->invoice_number = 'VES-' . $sale->id;
            $sale->save();
        });

        static::updating(function ($sale) {
            if (is_null($sale->invoice_number)) {
                $sale->invoice_number = 'VES-' . $sale->id;
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

    public function payments(): HasMany
    {
        return $this->hasMany(SaleInvoicePayment::class);
    }

}
