<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */



    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $packageId = $this->route('package');

        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:packages,slug,' . $packageId,
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string|max:1000',
            'monthly_price' => 'required|numeric|min:0',
            'old_monthly_price' => 'nullable|numeric|min:0',
            'yearly_price' => 'nullable|numeric|min:0',
            'old_yearly_price' => 'nullable|numeric|min:0',
            'stripe_monthly_plan_id' => 'nullable|string|max:255',
            'stripe_yearly_plan_id' => 'nullable|string|max:255',
            'paypal_monthly_plan_id' => 'nullable|string|max:255',
            'paypal_yearly_plan_id' => 'nullable|string|max:255',
            'ai_enabled' => 'boolean',
            'provider_limit' => 'required|array',
            'features' => 'nullable|array',
            'post_limit' => 'nullable|integer|min:0',
            'status' => 'boolean',
            'create_plan_in_gateway' => 'nullable|boolean',
            'selected_gateway' => 'nullable|string|in:stripe,paypal',
        ];
    }


    protected function prepareForValidation()
    {
        if (empty($this->slug) && filled($this->name)) {
            $this->merge([
                'slug' => Str::slug($this->name),
            ]);
        }

        // Ensure features is an array
        if (is_string($this->features)) {
            $this->merge([
                'features' => array_values(array_filter(
                    array_map('trim', explode(',', $this->features))
                )),
            ]);
        }

        // Ensure provider_limit is an array (multi-select usually sends array)
        if ($this->has('provider_limit') && !is_array($this->provider_limit)) {
            $this->merge([
                'provider_limit' => [$this->provider_limit],
            ]);
        }
    }
}
