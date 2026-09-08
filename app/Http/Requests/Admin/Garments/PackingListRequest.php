<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class PackingListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'packing_date' => 'required|date',
            'carton_number' => 'required|string|max:60',
            'color' => 'nullable|string|max:80',
            'size' => 'nullable|string|max:40',
            'quantity' => 'required|integer|min:1',
            'gross_weight' => 'required|numeric|min:0',
            'net_weight' => 'required|numeric|min:0|lte:gross_weight',
            'carton_length' => 'nullable|numeric|min:0',
            'carton_width' => 'nullable|numeric|min:0',
            'carton_height' => 'nullable|numeric|min:0',
            'status' => 'required|in:' . implode(',', array_keys(garmentPackingStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
