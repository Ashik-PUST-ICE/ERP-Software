<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class OrderProfitLossRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'sales_revenue' => 'required|numeric|min:0',
            'material_cost' => 'required|numeric|min:0',
            'production_cost' => 'required|numeric|min:0',
            'salary_cost' => 'required|numeric|min:0',
            'overhead_cost' => 'required|numeric|min:0',
            'other_cost' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentProfitLossStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
