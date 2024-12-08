<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Grn;
use App\Models\Grnr;

class GrnItem extends Model
{
    use HasFactory;

    protected $fillable = ['grn_id', 'variant_id', 'shelf_id', 'ordered_quantity', 'received_quantity', 'unit_price', 'total_price'];


    public function grn() : BelongsTo
    {
        return $this->belongsTo(Grn::class, 'grn_id', 'id');
    }

    public function variant() : BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function($grnItem) {
            $grnItem->updateStockEntry();
        });

        static::deleting(function($grnItem) {
            $grnItem->removeStockEntry();
        });

    }

    public function updateStockEntry()
    {   
        $stock_entry = StockEntry::where('source_id', $this->grn_id)
            ->where('source_type', Grn::class)
            ->where('product_variant_id', $this->variant_id)
            ->first();

        if($stock_entry) {
            $stock_entry->update([
                'quantity' => $this->received_quantity
            ]);
        }
        else {
            StockEntry::create([
                'product_variant_id' => $this->variant_id,
                'source_id' => $this->grn_id,
                'source_type' => Grn::class,
                'shelf_id' => $this->shelf_id,
                'quantity' => $this->received_quantity,
            ]);
        }
    }

    public function removeStockEntry()
    {
        StockEntry::where('source_id', $this->grn_id)
            ->where('source_type', Grn::class)
            ->where('product_variant_id', $this->variant_id)
            ->delete();
    }

}
