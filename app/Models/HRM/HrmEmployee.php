<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmEmployee extends Model
{
    use HasFactory;

    protected $table = 'hrm_employees';

    protected $fillable = [
        'employee_code', 'user_id', 'first_name', 'last_name', 'email', 'phone',
        'gender', 'date_of_birth', 'joining_date', 'department_id', 'designation_id',
        'employment_type', 'basic_salary', 'address', 'avatar', 'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'basic_salary' => 'decimal:2',
    ];

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id');
    }

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id');
    }

    public function todayAttendance()
    {
        return $this->hasOne(Attendance::class, 'employee_id')->whereDate('date', today());
    }
}
