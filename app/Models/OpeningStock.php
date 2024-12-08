<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OpeningStock extends Model
{
    use HasFactory;

    protected $fillable = ['variant_id', 'shelf_id', 'quantity', 'unit_price', 'total_price', 'note'];

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($openingStock) {
            $openingStock->total_price = $openingStock->quantity * $openingStock->unit_price;
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

    public function shelf(): BelongsTo
    {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'id');
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

        if ($stockEntry) {
            $stockEntry->update([
                'quantity' => $this->quantity,
            ]);
        } else {
            StockEntry::create([
                'shelf_id' => $this->shelf_id,
                'product_variant_id' => $this->variant_id,
                'quantity' => $this->quantity,
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
