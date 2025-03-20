<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdvanceSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'amount', 'advance_date', 'name', 'remarks'
    ];

    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
