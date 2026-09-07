<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'client_id' => 'bail|nullable|exists:users,id',
            'order_id' => [
                'nullable',
                'string',
                'max:255',
                Rule::requiredIf(!$this->filled('id')),
            ],
            'ticket_title' => 'bail|nullable|string|max:255',
            'description' => 'bail|required|string',
            'priority' => 'bail|nullable|integer',
        ];
    }
}
