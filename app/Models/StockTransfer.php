<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'variant_id',
        'source_shelf_id',
        'destination_shelf_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($stockTransfer) {
            StockEntry::where('product_variant_id', $stockTransfer->variant_id)
                ->where('shelf_id', $stockTransfer->source_shelf_id)
                ->update(['shelf_id' => $stockTransfer->destination_shelf_id]);
        });
    }

    public function variant() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function sourceShelf() : BelongsTo
    {
        return $this->belongsTo(Shelf::class, 'source_shelf_id', 'id');
    }

    public function destinationShelf() : BelongsTo
    {
        return $this->belongsTo(Shelf::class, 'destination_shelf_id', 'id');
    }

}
