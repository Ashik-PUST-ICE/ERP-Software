<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
class FinalInspectionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['order_id'=>'required|exists:garment_orders,id','inspection_lot'=>'required|string|max:80','inspection_date'=>'required|date','lot_quantity'=>'required|integer|min:0','sample_quantity'=>'required|integer|min:0|lte:lot_quantity','aql_level'=>'nullable|string|max:30','defect_quantity'=>'required|integer|min:0|lte:sample_quantity','rejected_quantity'=>'required|integer|min:0|lte:lot_quantity','result'=>'required|in:'.implode(',',array_keys(garmentInspectionResults())),'inspector_name'=>'nullable|string|max:120','notes'=>'nullable|string']; }
}
