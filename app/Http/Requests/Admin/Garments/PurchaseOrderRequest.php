<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:garment_suppliers,id',
            'po_number' => 'required|string|max:50|unique:garment_purchase_orders,po_number,' . ($this->id ?? null),
            'order_date' => 'required|date',
            'expected_date' => 'nullable|date|after_or_equal:order_date',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|in:' . STATUS_PENDING . ',' . STATUS_ACTIVE . ',' . STATUS_CANCELLED,
            'notes' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.material_id' => 'nullable|exists:garment_materials,id',
            'items.*.quantity' => 'nullable|numeric|min:0.0001',
            'items.*.unit_rate' => 'nullable|numeric|min:0',
        ];
    }
}
