<?php

namespace App\Http\Requests\Admin\HRM;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->id ?? null;
        return [
            'name' => 'required|string|max:120',
            'code' => 'required|string|max:20|unique:hrm_departments,code,' . $id,
            'description' => 'nullable|string',
            'status' => 'required|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
        ];
    }
}
