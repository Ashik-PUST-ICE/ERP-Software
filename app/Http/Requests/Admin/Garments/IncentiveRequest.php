<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
class IncentiveRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['order_id'=>'nullable|exists:garment_orders,id','employee_name'=>'required|string|max:160','production_date'=>'required|date','operation'=>'required|string|max:120','production_quantity'=>'required|integer|min:1','piece_rate'=>'required|numeric|min:0','status'=>'required|in:'.implode(',',array_keys(garmentIncentiveStatuses())),'notes'=>'nullable|string']; }
}
