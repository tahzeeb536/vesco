<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagingBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'packaging_list_id',
        'cartons',
        'qty_cartons',
        'article_no',
        'details',
        'size_qty',
        'total_qty',
    ];
}
