<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:fixed,percentage',
            'amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'minimum_spend' => 'nullable|numeric|min:0',
            'usage_limit_per_customer' => 'nullable|integer|min:0',
            'usage_limit_per_coupon' => 'nullable|integer|min:0',
            'status' => 'boolean',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $coupon = $this->route('coupon');
            $couponId = is_object($coupon) ? $coupon->id : $coupon;
            $rules['code'] = 'required|string|max:50|unique:coupons,code,' . $couponId;
        }

        return $rules;
    }
}
