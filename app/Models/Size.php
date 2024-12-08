<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Size extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'status'];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'size_id', 'id');
    }
}
