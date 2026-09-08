<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
class InlineQcRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['order_id'=>'required|exists:garment_orders,id','inspection_point'=>'required|string|max:120','inspection_date'=>'required|date','checked_quantity'=>'required|integer|min:0','passed_quantity'=>'required|integer|min:0|lte:checked_quantity','defect_quantity'=>'required|integer|min:0|lte:checked_quantity','inspector_name'=>'nullable|string|max:120','status'=>'required|in:'.implode(',',array_keys(garmentQcStatuses())),'notes'=>'nullable|string']; }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ((int) $this->input('passed_quantity', 0) + (int) $this->input('defect_quantity', 0) > (int) $this->input('checked_quantity', 0)) {
                    $validator->errors()->add('defect_quantity', __('Passed and defect quantities cannot exceed checked quantity.'));
                }
            },
        ];
    }
}
