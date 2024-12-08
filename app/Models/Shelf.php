<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shelf extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'rack_id', 'status'];

    public function rack() : BelongsTo {
        return $this->belongsTo(Rack::class, 'rack_id', 'id');
    }

    
}
