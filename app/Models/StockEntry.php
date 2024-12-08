<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\StockEntry;

class StockEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'source_type',
        'shelf_id',
        'product_variant_id',
        'quantity',
    ];

    public function grn() : BelongsTo
    {
        return $this->belongsTo(Grn::class, 'grn_id', 'id');
    }

    public function shelf() : BelongsTo
    {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'id');
    }

    public function rack()
    {
        return $this->hasOneThrough(Rack::class, Shelf::class, 'id', 'id', 'shelf_id', 'rack_id');
    }

    public function room()
    {
        return $this->hasOneThrough(Room::class, Rack::class, 'id', 'id', 'rack_id', 'room_id');
    }

    public function store()
    {
        return $this->hasOneThrough(Store::class, Room::class, 'id', 'id', 'room_id', 'store_id');
    }


    public function productVariant() : belongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function product()
    {
        return $this->hasOneThrough(Product::class, ProductVariant::class, 'id', 'id', 'product_variant_id', 'product_id');
    }

    public function source()
    {
        return $this->morphTo();
    }
    
    public function getFirstShelfNameByVariant($productVariantId)
    {
        $stockEntryWithShelf = self::where('product_variant_id', $productVariantId)
            ->whereNotNull('shelf_id')
            ->with('shelf')
            ->first();

        return $stockEntryWithShelf ? optional($stockEntryWithShelf->shelf)->name : 'N/A';
    }
}
