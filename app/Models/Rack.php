<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rack extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'room_id', 'status'];
    
    public function room() : BelongsTo {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function shelves() : HasMany {
        return $this->hasMany(Shelf::class, 'rack_id', 'id');
    }

}
