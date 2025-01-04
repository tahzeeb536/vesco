<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'airway_bill_number',
        'destination_code',
        'origin_code',
        'shipper_account_number',
        'shipper_credit_card',
        'shipper_name',
        'shipper_address',
        'shipper_city',
        'shipper_zip',
        'shipper_country',
        'shipper_phone',
        'shipper_department',
        'receiver_company_name',
        'receiver_attention_to',
        'receiver_address',
        'receiver_city',
        'receiver_state',
        'receiver_country',
        'receiver_zip',
        'receiver_phone',
        'items',
        'kilos',
        'type',
        'extra_information',
    ];

    
}
