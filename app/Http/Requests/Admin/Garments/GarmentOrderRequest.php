<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class GarmentOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'buyer_id' => 'required|exists:garment_buyers,id',
            'style_id' => 'required|exists:garment_styles,id',
            'order_number' => 'required|string|max:60|unique:garment_orders,order_number,' . $id,
            'product_description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'delivery_date' => 'required|date|after_or_equal:order_date',
            'status' => 'required|in:' . implode(',', array_keys(garmentOrderStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
