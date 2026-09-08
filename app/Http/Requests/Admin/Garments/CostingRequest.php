<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class CostingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'order_id' => 'required|exists:garment_orders,id|unique:garment_costings,order_id,' . $id,
            'fabric_cost' => 'required|numeric|min:0',
            'trims_cost' => 'required|numeric|min:0',
            'accessories_cost' => 'required|numeric|min:0',
            'cm_cost' => 'required|numeric|min:0',
            'washing_cost' => 'required|numeric|min:0',
            'printing_cost' => 'required|numeric|min:0',
            'embroidery_cost' => 'required|numeric|min:0',
            'overhead_cost' => 'required|numeric|min:0',
            'other_cost' => 'required|numeric|min:0',
            'fob_price' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentCostingStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
