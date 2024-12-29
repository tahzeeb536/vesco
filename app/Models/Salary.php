<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'month', 'year', 'total_present_days', 'total_overtime_hours', 'deduction', 'net_salary'
    ];

    public function employee() : BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}