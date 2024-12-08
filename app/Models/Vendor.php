<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'organization',
        'phone',
        'address',
        'country',
        'city',
        'currency',
        'status',
        'opening_balance',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->full_name = $model->first_name . ' ' . $model->last_name;
        });
        
    }

    public function product_prices(): HasMany {
        return $this->hasMany(VendorProductPrice::class, 'vendor_id', 'id');
    }

    
}
