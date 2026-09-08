<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class TnaTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'employee_id' => 'nullable|exists:hrm_employees,id',
            'task_name' => 'required|string|max:150',
            'task_type' => 'nullable|string|max:100',
            'planned_date' => 'required|date',
            'actual_date' => 'nullable|date|after_or_equal:planned_date',
            'status' => 'required|in:' . implode(',', array_keys(garmentTnaStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
