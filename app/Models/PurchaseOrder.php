<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'vendor_id',
        'order_date',
        'status',
        'total_amount',
        'delivery_date',
        'note',
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->purchase_order_number = 'TEMP-' . uniqid();
        });

        static::created(function ($model) {
            $model->purchase_order_number = 'PO-' . $model->id;
            $model->saveQuietly();
        });


    }


    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function updateTotalAmount()
    {
        $totalAmount = $this->items()->sum('total_price');

        if ($this->total_amount !== $totalAmount) {
            $this->total_amount = $totalAmount;
            $this->saveQuietly();
        }
    }

}
