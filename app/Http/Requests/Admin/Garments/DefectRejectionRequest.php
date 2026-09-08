<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
class DefectRejectionRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['order_id'=>'required|exists:garment_orders,id','inline_qc_id'=>'nullable|exists:garment_inline_qcs,id','defect_type'=>'required|string|max:120','section'=>'required|string|max:100','defect_quantity'=>'required|integer|min:0','rejected_quantity'=>'required|integer|min:0|lte:defect_quantity','root_cause'=>'nullable|string|max:180','corrective_action'=>'nullable|string','status'=>'required|in:'.implode(',',array_keys(garmentDefectStatuses())),'reported_date'=>'required|date','notes'=>'nullable|string']; }
}
