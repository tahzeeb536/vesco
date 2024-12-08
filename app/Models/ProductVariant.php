<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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


}
