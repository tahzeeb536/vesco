<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_for_vendor', 'category_id', 'article_number', 'image', 'status'];

    public function category() : BelongsTo {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function variants() : HasMany {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

}
