<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'hrm_departments';

    protected $fillable = ['name', 'code', 'description', 'status'];

    public function designations()
    {
        return $this->hasMany(Designation::class, 'department_id');
    }

    public function employees()
    {
        return $this->hasMany(HrmEmployee::class, 'department_id');
    }
}
