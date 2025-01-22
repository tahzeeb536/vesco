<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_date',
        'amount',
        'ref',
        'added_by',
    ];

    public function order() : BelongsTo {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (auth()->check() && !$payment->added_by) {
                $payment->added_by = auth()->id();
            }
        });

        static::saved(function ($payment) {
            $payment->updateOrderTotals();
        });

        static::deleted(function ($payment) {
            $payment->updateOrderTotals();
        });
    }

    public function updateOrderTotals()
    {
        $totalPaid = self::where('order_id', $this->order_id)->sum('amount');

        // Get the associated order
        $order = $this->order;

        if ($order) {
            $grandTotal = $order->order_amount - $order->damage_amount;

            // Update the order fields
            $order->update([
                'grand_total' => $grandTotal,
                'paid_amount' => $totalPaid,
                'balance' => $grandTotal - $totalPaid,
            ]);
        }
    }

}
