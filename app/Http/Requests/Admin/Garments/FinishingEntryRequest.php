<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class FinishingEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'finishing_date' => 'required|date',
            'received_quantity' => 'required|integer|min:0',
            'passed_quantity' => 'required|integer|min:0|lte:received_quantity',
            'rework_quantity' => 'required|integer|min:0|lte:received_quantity',
            'rejected_quantity' => 'required|integer|min:0|lte:received_quantity',
            'status' => 'required|in:' . implode(',', array_keys(garmentFinishingStatuses())),
            'remarks' => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $total = (int) $this->input('passed_quantity', 0)
                + (int) $this->input('rework_quantity', 0)
                + (int) $this->input('rejected_quantity', 0);

            if ($total !== (int) $this->input('received_quantity', 0)) {
                $validator->errors()->add('received_quantity', __('Passed, rework and rejected quantities must equal received quantity.'));
            }
        });
    }
}
