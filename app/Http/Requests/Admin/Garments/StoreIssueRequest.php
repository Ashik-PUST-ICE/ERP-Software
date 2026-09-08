<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'material_id' => 'required|exists:garment_materials,id',
            'order_id' => 'nullable|exists:garment_orders,id',
            'issue_number' => 'required|string|max:60|unique:garment_store_issues,issue_number,' . ($this->id ?? null),
            'section' => 'required|string|max:100',
            'line_name' => 'nullable|string|max:100',
            'issue_date' => 'required|date',
            'issued_quantity' => 'required|numeric|min:0.0001',
            'returned_quantity' => 'required|numeric|min:0|lte:issued_quantity',
            'status' => 'required|in:' . implode(',', array_keys(garmentIssueStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
