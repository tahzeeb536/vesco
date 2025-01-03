<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_category_id', 'date', 'description', 'amount', 'expense_by', 'image', 
    ];

    public function category() : BelongsTo {
        $this->belongsTo(ExpenseCategory::class, 'expense_category_id', 'id');
    }
    
}
