<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'status'];

    public function products() : HasMany {
        return $this->hasMany(Expenses::class, 'expense_category_id', 'id');
    }

}
