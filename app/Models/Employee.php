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
        'home_allowance',
        'medical_allowance',
        'mobile_allowance',
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

    public function advance_salaries(): HasMany  {
        return $this->hasMany(AdvanceSalary::class, 'employee_id', 'id');
    }
 
    public function advance_salary_balance(): HasOne
    {
        return $this->hasOne(AdvanceSalaryBalance::class, 'employee_id', 'id');
    }

    public function salaries(): HasMany  {
        return $this->hasMany(Salary::class, 'employee_id', 'id');
    }

    public function advance_salary_deductions(): HasMany  {
        return $this->hasMany(AdvanceSalaryDeduction::class, 'employee_id', 'id');
    }

    public function temp_loans() : HasMany {
        return $this->hasMany(TempLoan::class, 'employee_id', 'id');
    }

    public function employee_statements() : HasMany {
        return $this->hasMany(EmployeeStatement::class, 'employee_id', 'id');
    }
}
