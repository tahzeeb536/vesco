<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvanceSalaryBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'total_amount', 'paid_amount', 'monthly_deduction', 'remaining_amount'
    ];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
