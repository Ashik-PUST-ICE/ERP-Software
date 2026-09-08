<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class ProductionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'line_name' => 'required|string|max:100',
            'planned_quantity' => 'required|integer|min:1',
            'daily_target' => 'required|integer|min:1',
            'capacity_per_day' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', array_keys(garmentPlanStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
