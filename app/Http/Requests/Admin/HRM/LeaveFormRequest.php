<?php

namespace App\Http\Requests\Admin\HRM;

use Illuminate\Foundation\Http\FormRequest;

class LeaveFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|exists:hrm_employees,id',
            'leave_type' => 'required|in:' . LEAVE_TYPE_CASUAL . ',' . LEAVE_TYPE_SICK . ',' . LEAVE_TYPE_ANNUAL . ',' . LEAVE_TYPE_MATERNITY . ',' . LEAVE_TYPE_OTHER,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ];
    }
}
