<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'month', 'year', 'total_present_days', 'total_hours', 'total_minutes', 'total_overtime_hours', 'total_overtime_minutes', 'deduction', 'net_salary', 'late_hours', 'status', 'home_allowance', 'mobile_allowance', 'medical_allowance', 'basic_salary', 'loan_deduction', 'temp_deduction'
    ];

    public function employee() : BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

}
