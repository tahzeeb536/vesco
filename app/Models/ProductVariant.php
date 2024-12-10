<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
        'color_id',
        'size_id',
        'price',
        'vendor_price',
        'customer_price',
        'stock_quantity',
        'low_stock_threshold', 
    ];

    protected static function booted()
    {
        parent::boot();
        
        static::saved(function ($variant) {
            $variant->name = $variant->generateName();
            $variant->saveQuietly();
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function vendor_prices() : HasMany {
        return $this->hasMany(VendorProductPrice::class, 'product_variant_id', 'id');
    }

    public function generateName()
    {
        $articleNumber = $this->product->article_number ?? '';
        $size = $this->size->name ?? '';
        $color = $this->color->name ?? '';
        $productName = $this->product->name ?? '';

        return "{$articleNumber} - {$size} - {$color} - {$productName}";
    }

    public function stockEntries() : HasMany
    {
        return $this->hasMany(StockEntry::class, 'product_variant_id', 'id');
    }

    public function scopeOutOfStock(Builder $query): Builder
    {
        return $query
            ->select(
                'product_variants.name as variant_name',
                'products.name_for_vendor as vendor_name',
                DB::raw('COALESCE(SUM(stock_entries.quantity), 0) as stock'),
                'shelves.name as shelf_name',
                'racks.name as rack_name',
                'rooms.name as room_name',
                'stores.name as store_name'
            )
            ->leftJoin('products', 'products.id', '=', 'product_variants.product_id')
            ->leftJoin('stock_entries', 'stock_entries.product_variant_id', '=', 'product_variants.id')
            ->leftJoin('shelves', 'shelves.id', '=', 'stock_entries.shelf_id')
            ->leftJoin('racks', 'racks.id', '=', 'shelves.rack_id')
            ->leftJoin('rooms', 'rooms.id', '=', 'racks.room_id')
            ->leftJoin('stores', 'stores.id', '=', 'rooms.store_id')
            ->groupBy(
                'product_variants.id',
                'product_variants.name',
                'products.name_for_vendor',
                'shelves.name',
                'racks.name',
                'rooms.name',
                'stores.name'
            )
            ->having('stock', '<=', 30);
    }



}
