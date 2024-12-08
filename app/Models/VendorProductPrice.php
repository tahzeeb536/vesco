<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorProductPrice extends Model
{
    protected $fillable = [
        'vendor_id', 'product_variant_id', 'price'
    ];

    public function vendor() : BelongsTo {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id');
    }

    public function variant() : BelongsTo {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }
    
}
