<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'organization',
        'phone',
        'address',
        'city',
        'post_code',
        'country',
        'state',
        'currency',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->full_name = $model->first_name . ' ' . $model->last_name;
        });
        
    }
    
}
