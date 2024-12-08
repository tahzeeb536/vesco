<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'store_id', 'status'];

    public function racks() : HasMany {
        return $this->hasMany(Rack::class, 'room_id', 'id');
    }

    public function store() : BelongsTo {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    } 

}
