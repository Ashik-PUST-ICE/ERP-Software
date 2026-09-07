<?php

namespace App\Http\Requests\Admin\HRM;

use Illuminate\Foundation\Http\FormRequest;

class BulkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'status' => 'required|in:' . ATTENDANCE_STATUS_PRESENT . ',' . ATTENDANCE_STATUS_LATE . ',' . ATTENDANCE_STATUS_ABSENT,
        ];
    }
}
