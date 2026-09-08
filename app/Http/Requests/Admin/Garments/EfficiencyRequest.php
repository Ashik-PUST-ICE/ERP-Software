<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class EfficiencyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'employee_id' => 'nullable|exists:hrm_employees,id',
            'order_id' => 'nullable|exists:garment_orders,id',
            'machine_name' => 'nullable|string|max:100',
            'line_name' => 'nullable|string|max:100',
            'work_date' => 'required|date',
            'working_minutes' => 'required|integer|min:0',
            'target_output' => 'required|integer|min:0',
            'actual_output' => 'required|integer|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentEfficiencyStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
