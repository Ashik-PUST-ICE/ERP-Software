<?php

namespace App\Http\Requests\Admin\HRM;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:hrm_employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:' . ATTENDANCE_STATUS_PRESENT . ',' . ATTENDANCE_STATUS_LATE . ',' . ATTENDANCE_STATUS_ABSENT . ',' . ATTENDANCE_STATUS_HALF_DAY . ',' . ATTENDANCE_STATUS_ON_LEAVE,
            'notes' => 'nullable|string',
        ];
    }
}
