<?php

namespace App\Http\Requests\AutoPost\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SocialMediaConfigRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $method = $this->method();
        $user   = $this->user();
        $tenantId = $user ? $user->tenant_id : null;
        $userId   = $user ? $user->id : null;

        // For updates, don't validate platform uniqueness since it shouldn't change
        if ($method === 'PUT' || $method === 'PATCH') {
            $platformRule = ['required', 'string', 'max:50'];
        } else {
            $platformRule = [
                'required',
                'string',
                'max:50',
                Rule::unique('social_media_configs', 'platform')
                    ->when($tenantId, fn($q) => $q->where('tenant_id', $tenantId))
                    ->when($userId, fn($q) => $q->where('user_id', $userId)),
            ];
        }

        return [
            'platform' => $platformRule,
            'app_id' => 'required|string|max:255',
            'app_secret' => 'required|string|max:255',
            'bearer_token' => 'nullable|string|max:1000',
            'access_token_secret' => 'nullable|string|max:1000',
            'redirect_uri' => 'nullable|url',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|max:100',
            'settings' => 'nullable|array',
            'is_active' => 'boolean'
        ];
    }

    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'platform' => strtolower($this->platform ?? ''),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}