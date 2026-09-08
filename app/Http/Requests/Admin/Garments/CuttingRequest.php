<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class CuttingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'material_id' => 'nullable|exists:garment_materials,id',
            'marker_number' => 'nullable|string|max:60',
            'fabric_consumption' => 'required|numeric|min:0',
            'marker_efficiency' => 'required|numeric|min:0|max:100',
            'planned_cut_quantity' => 'required|integer|min:0',
            'cut_quantity' => 'required|integer|min:0',
            'panel_quantity' => 'required|integer|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentCuttingStatuses())),
            'cutting_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
