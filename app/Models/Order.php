<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'email_date',
        'delivery_date',
        'order_name',
        'customer_id',
        'invoice_number',
        'status',
        'currency',
        'order_amount',
        'damage_amount',
        'grand_total',
        'paid_amount',
        'balance',
        'order_file_admin',
        'order_file_manager',
        'total_boxes',
        'boxes_details',
        'shipping_carrier',
        'tracking_number',
        'airway_bill_number',
    ];

    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function payments() : HasMany {
        return $this->hasMany(OrderPayment::class, 'order_id');
    }

    protected $casts = [
        'boxes_details' => 'array',
    ];
    
}
