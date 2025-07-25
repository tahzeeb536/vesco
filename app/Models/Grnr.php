<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\StockEntry;

class Grnr extends Model
{
    use HasFactory;

    protected $fillable = [
        'grn_id',
        'returned_date',
        'grnr_number',
        'reason',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($grnr) {
            $grnr->grnr_number = 'TEMP-' . uniqid();
        });

        static::created(function ($grnr) {
            $grnr->grnr_number = 'PR-' . $grnr->id;
            $grnr->saveQuietly();
        });


        static::deleting(function ($grnr) {
            foreach ($grnr->items as $item) {
                $item->delete();
            }

            // StockEntry::where('source_id', $grnr->id)
            // ->where('source_type', self::class)
            // ->delete();
        });

    }

    public function grn() : BelongsTo
    {
        return $this->belongsTo(Grn::class, 'grn_id', 'id');
    }

    public function items() : HasMany
    {
        return $this->hasMany(GrnrItem::class, 'grnr_id', 'id');
    }

    public function getTotalAmountAttribute()
    {
        return $this->items->sum('total_price');
    }

    public function stockEntries()
    {
        return $this->morphMany(StockEntry::class, 'source');
    }
}
