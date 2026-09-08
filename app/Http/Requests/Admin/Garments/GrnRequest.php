<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class GrnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'material_id' => 'required|exists:garment_materials,id',
            'grn_number' => 'required|string|max:60|unique:garment_grns,grn_number,' . $id,
            'supplier_name' => 'required|string|max:150',
            'purchase_reference' => 'nullable|string|max:100',
            'received_date' => 'required|date',
            'ordered_quantity' => 'required|numeric|min:0',
            'received_quantity' => 'required|numeric|min:0.0001',
            'rejected_quantity' => 'required|numeric|min:0|lte:received_quantity',
            'unit_cost' => 'required|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentGrnStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
