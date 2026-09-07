<?php

namespace App\Http\Requests\Admin\HRM;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;
        return [
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'email' => 'required|email|unique:hrm_employees,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:' . GENDER_MALE . ',' . GENDER_FEMALE . ',' . GENDER_OTHER,
            'date_of_birth' => 'nullable|date',
            'joining_date' => 'required|date',
            'department_id' => 'required|exists:hrm_departments,id',
            'designation_id' => 'required|exists:hrm_designations,id',
            'employment_type' => 'required|in:' . EMPLOYMENT_TYPE_FULL_TIME . ',' . EMPLOYMENT_TYPE_PART_TIME . ',' . EMPLOYMENT_TYPE_CONTRACT . ',' . EMPLOYMENT_TYPE_INTERN,
            'basic_salary' => 'required|numeric|min:0',
            'address' => 'nullable|string',
            'status' => 'required|in:' . EMPLOYEE_STATUS_ACTIVE . ',' . EMPLOYEE_STATUS_ON_LEAVE . ',' . EMPLOYEE_STATUS_TERMINATED,
        ];
    }
}
