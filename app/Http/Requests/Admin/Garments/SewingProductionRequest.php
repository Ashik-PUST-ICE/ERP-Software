<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class SewingProductionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'line_name' => 'required|string|max:100',
            'production_date' => 'required|date',
            'daily_target' => 'required|integer|min:0',
            'hourly_target' => 'required|integer|min:0',
            'hourly_output' => 'required|integer|min:0',
            'total_output' => 'required|integer|min:0',
            'wip_quantity' => 'required|integer|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentSewingStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
