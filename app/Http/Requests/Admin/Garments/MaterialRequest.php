<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'item_code' => 'required|string|max:50|unique:garment_materials,item_code,' . $id,
            'barcode' => 'nullable|string|max:100|unique:garment_materials,barcode,' . $id,
            'item_name' => 'required|string|max:150',
            'category' => 'required|in:' . implode(',', array_keys(garmentMaterialCategories())),
            'unit' => 'required|string|max:30',
            'opening_stock' => 'required|numeric|min:0',
            'current_stock' => 'required|numeric|min:0',
            'reorder_level' => 'required|numeric|min:0',
            'warehouse' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:100',
            'status' => 'required|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
            'notes' => 'nullable|string',
        ];
    }
}
