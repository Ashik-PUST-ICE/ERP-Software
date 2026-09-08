<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class StyleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'style_code' => 'required|string|max:50|unique:garment_styles,style_code,' . $id,
            'style_name' => 'required|string|max:150',
            'product_type' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'season' => 'nullable|string|max:80',
            'status' => 'required|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
            'notes' => 'nullable|string',
        ];
    }
}
