<?php

namespace App\Http\Requests\AutoPost\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageRequest extends FormRequest
{
    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('title') && !$this->has('slug')) {
            $title = $this->title;
            $slug = (string) Str::of($title)->lower()
                ->replaceMatches('/[^a-z0-9\s-]/', '')
                ->replace(' ', '-')
                ->replaceMatches('/-+/', '-')
                ->trim();
            
            $this->merge([
                'slug' => $slug
            ]);
        }
    }

    public function rules(): array
    {
        $pageId = $this->route('uuid') ?? null;
        
        return [
            'title' => 'bail|required|string|max:255',
            'slug' => [
                'bail',
                'nullable',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($pageId, 'uuid'),
            ],
            'en_description' => 'bail|required|string',
            'meta_title' => 'bail|nullable|string|max:255',
            'meta_description' => 'bail|nullable|string',
            'meta_keywords' => 'bail|nullable|string',
            'og_image' => 'bail|nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }


}