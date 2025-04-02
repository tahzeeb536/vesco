<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'datetime',
        'details',
        'deposit',
        'withdraw',
        'type',
        'year',
        'month',
    ];

    public function employee() : BelongsTo {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }


}
