<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_urdu',
        'father_name',
        'dob',
        'cnic',
        'photo',
        'phone',
        'address',
        'type',
        'basic_salary',
        'status',
    ];

    public function attendance() : HasMany {
        return $this->hasMany(Attendance::class, 'employee_id', 'id');
    }

    public function today_attendance() : HasOne {
        return $this->hasOne(Attendance::class, 'employee_id', 'id')
        ->where('date', Carbon::today());
    }

    public function attendance_for_date($date = null) : HasOne {
        $date = $date ?: Carbon::today();
        return $this->hasOne(Attendance::class, 'employee_id', 'id')
        ->where('date', $date);
    }
 
}
