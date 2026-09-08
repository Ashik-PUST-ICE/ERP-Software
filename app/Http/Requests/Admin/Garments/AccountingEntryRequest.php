<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
class AccountingEntryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['order_id'=>'nullable|exists:garment_orders,id','entry_type'=>'required|in:'.implode(',',array_keys(garmentAccountingTypes())),'account_code'=>'required|string|max:40','account_name'=>'required|string|max:120','entry_date'=>'required|date','debit'=>'required|numeric|min:0','credit'=>'required|numeric|min:0','reference'=>'nullable|string|max:100','status'=>'required|in:'.implode(',',array_keys(garmentAccountingStatuses())),'description'=>'nullable|string']; }
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ((float) $this->input('debit', 0) === 0.0 && (float) $this->input('credit', 0) === 0.0) {
                $validator->errors()->add('debit', __('Debit or credit must have a value.'));
            }
        });
    }
}
