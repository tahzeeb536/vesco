<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'adjusted_quantity',
        'adjustment_type',
        'reason'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($openingStock) {
            $openingStock->createOrUpdateStockEntry();
        });

        static::deleting(function ($openingStock) {
            $openingStock->removeStockEntry();
        });

    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function stockEntries()
    {
        return $this->morphMany(StockEntry::class, 'source');
    }

    public function createOrUpdateStockEntry()
    {
        $stockEntry = StockEntry::where('source_id', $this->id)
                        ->where('source_type', self::class)
                        ->first();
            
        $type = $this->adjustment_type;
        $quantity = ($type == 'increase') ? $this->adjusted_quantity : -1 * $this->adjusted_quantity;

        if ($stockEntry) {
            $stockEntry->update([
                'quantity' => $quantity,
            ]);
        } else {
            StockEntry::create([
                'product_variant_id' => $this->variant_id,
                'quantity' => $quantity,
                'source_id' => $this->id,
                'source_type' => self::class,
            ]);
        }
    }

    public function removeStockEntry()
    {
        StockEntry::where('source_id', $this->id)->where('source_type', self::class)->delete();
    }
}
