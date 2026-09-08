<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class BuyerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'buyer_code' => 'required|string|max:30|unique:garment_buyers,buyer_code,' . $id,
            'company_name' => 'required|string|max:150',
            'contact_person' => 'nullable|string|max:120',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:40',
            'website' => 'nullable|string|max:150',
            'office_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'country' => 'nullable|string|max:100',
            'currency' => 'required|string|max:10',
            'payment_terms' => 'nullable|string|max:150',
            'contract_terms' => 'nullable|string',
            'status' => 'required|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
            'notes' => 'nullable|string',
        ];
    }
}
