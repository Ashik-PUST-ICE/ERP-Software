<?php

namespace App\Http\Requests\AutoPost\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TemplateRequest extends FormRequest
{
    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'title'             => 'required|string|max:255',
            'slug'              => ['nullable', 'string', 'max:255', Rule::unique('templates', 'slug')->ignore($id)],
            'category_id'       => 'nullable|exists:categories,id',
            'image'             => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'short_description' => 'nullable|string',
            'content'           => 'nullable|string',
            'post_type'         => 'nullable|string',
            'platform'          => 'nullable|string',
            'status'            => 'required|in:active,inactive',
        ];
    }
}
