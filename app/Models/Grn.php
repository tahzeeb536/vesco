<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\StockEntry;

class Grn extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_order_id', 'received_date', 'grn_number', 'note'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($grn) {
            $grn->grn_number = 'TEMP-' . uniqid();
        });

        static::created(function ($grn) {
            $grn->grn_number = 'PR-' . $grn->id;
            $grn->saveQuietly();
        });


        static::deleting(function ($grn) {
            foreach ($grn->items as $item) {
                $item->delete();
            }

            // StockEntry::where('source_id', $grn->id)
            // ->where('source_type', self::class)
            // ->delete();

        });

    }

    public function purchase_order() : BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id', 'id');
    }

    public function items() : HasMany
    {
        return $this->hasMany(GrnItem::class, '');
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum('total_price');
    }

    public function stockEntries()
    {
        return $this->morphMany(StockEntry::class, 'source');
    }


}
