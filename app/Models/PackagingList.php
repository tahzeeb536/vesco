<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackagingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'e_form_no',
        'invoice_no',
        'invoice_date',
        'country_of_origin',
        'customer_id',
        'port_of_landing',
        'port_of_discharge'
    ];

    public function customer() : BelongsTo {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function boxes() : HasMany {
        return $this->hasMany(PackagingBox::class, 'packaging_list_id', 'id');
    }
}
