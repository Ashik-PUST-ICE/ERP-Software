<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_code' => 'required|string|max:30|unique:garment_suppliers,supplier_code,' . ($this->id ?? null),
            'company_name' => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:120',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:40',
            'category' => 'nullable|string|max:80',
            'address' => 'nullable|string',
            'payment_terms' => 'nullable|string|max:150',
            'status' => 'required|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
            'notes' => 'nullable|string',
        ];
    }
}
