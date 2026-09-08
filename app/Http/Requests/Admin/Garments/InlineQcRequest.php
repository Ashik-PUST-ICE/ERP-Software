<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
class InlineQcRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['order_id'=>'required|exists:garment_orders,id','inspection_point'=>'required|string|max:120','inspection_date'=>'required|date','checked_quantity'=>'required|integer|min:0','passed_quantity'=>'required|integer|min:0|lte:checked_quantity','defect_quantity'=>'required|integer|min:0|lte:checked_quantity','inspector_name'=>'nullable|string|max:120','status'=>'required|in:'.implode(',',array_keys(garmentQcStatuses())),'notes'=>'nullable|string']; }
}
