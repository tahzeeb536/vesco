<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Grnr;

class GrnrItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'grnr_id',
        'variant_id',
        'returned_quantity',
        'unit_price',
        'total_price',
        'reason'
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($grnrItem) {
            $grnrItem->updateStockEntry();
        });

        static::updated(function ($grnrItem) {
            $grnrItem->updateStockEntry();
        });

        static::deleting(function ($grnrItem) {
            $grnrItem->removeStockEntry();
        });
    }


    public function grnr() : BelongsTo
    {
        return $this->belongsTo(Grnr::class, 'grnr_id', 'id');
    }

    public function variant() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function updateStockEntry()
    {
        $stock_entry = StockEntry::where('source_id', $this->grnr_id)
            ->where('source_type', Grnr::class)
            ->where('product_variant_id', $this->variant_id)
            ->first();

        if($stock_entry) {
            $stock_entry->update([
                'quantity' => -1 * $this->returned_quantity
            ]);
        }
        else {
            StockEntry::create([
                'product_variant_id' => $this->variant_id,
                'source_id' => $this->grnr_id,
                'source_type' => Grnr::class,
                'shelf_id' => $this->shelf_id,
                'quantity' => -1 * $this->returned_quantity,
            ]);
        }
    }

    public function removeStockEntry()
    {
        StockEntry::where('source_id', $this->grnr_id)
            ->where('source_type', Grnr::class)
            ->where('product_variant_id', $this->variant_id)
            ->delete();
    }
    
}
