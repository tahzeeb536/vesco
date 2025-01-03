<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceSalaryDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'amount', 'return_date', 'remarks'
    ];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
