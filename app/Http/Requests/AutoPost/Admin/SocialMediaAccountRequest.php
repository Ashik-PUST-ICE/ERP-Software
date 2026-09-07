<?php

namespace App\Http\Requests\AutoPost\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaAccountRequest extends FormRequest
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
        $rules = [
            'user_id' => 'nullable|exists:users,id',
            'platform' => 'required|string|max:50',
            'account_id' => 'required|string|max:255',
            'access_token' => 'required|string',
            'username' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
            'permissions' => 'nullable|array',
            'token_expires_at' => 'nullable|date',
            'page_id' => 'nullable|string|max:255',
            'group_id' => 'nullable|string|max:255',
            'settings' => 'nullable|array',
            'refresh_token' => 'nullable|string',
            'bearer_token' => 'nullable|string',
        ];

        // Additional validation based on platform
        if ($this->input('platform') === 'facebook') {
            $rules['access_token'] = 'required|string|min:10';
        }

        if ($this->input('platform') === 'twitter') {
            $rules['access_token_secret'] = 'required|string|min:10';
        }

        return $rules;
    }

    /**
     * Get custom messages for validation errors
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select a user',
            'user_id.exists' => 'The selected user is invalid',
            'platform.required' => 'Please select a platform',
            'platform.string' => 'Platform must be a valid string',
            'account_id.required' => 'Account ID is required',
            'access_token.required' => 'Access token is required',
            'email.email' => 'Please provide a valid email address',
            'token_expires_at.date' => 'Token expiration date must be a valid date',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        // Convert boolean values from string to boolean
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Convert permissions from string to array if needed
        if ($this->has('permissions') && is_string($this->input('permissions'))) {
            $this->merge([
                'permissions' => json_decode($this->input('permissions'), true) ?: []
            ]);
        }

        // Convert settings from string to array if needed
        if ($this->has('settings') && is_string($this->input('settings'))) {
            $this->merge([
                'settings' => json_decode($this->input('settings'), true) ?: []
            ]);
        }
    }
}