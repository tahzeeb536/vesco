<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'clock_in',
        'clock_out',
        'hours_worked',
        'minutes_worked',
        'overtime_hours',
        'overtime_minutes',
        'status',
    ];

    public function employee() : BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    
    protected $casts = [
        'date' => 'datetime',
        'hours_worked' => 'integer',
        'minutes_worked' => 'integer',
        'overtime_hours' => 'integer',
        'overtime_minutes' => 'integer',
    ];

    /**
     * Accessor for Total Worked Time in "HH:MM" format.
     */
    public function getTotalWorkedTimeAttribute(): string
    {
        return sprintf('%02d:%02d', $this->hours_worked, $this->minutes_worked);
    }

    /**
     * Accessor for Total Overtime in "HH:MM" format.
     */
    public function getTotalOvertimeAttribute(): string
    {
        return sprintf('%02d:%02d', $this->overtime_hours, $this->overtime_minutes);
    }

}
