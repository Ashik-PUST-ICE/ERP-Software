<?php
namespace App\Http\Requests\Admin\Garments;
use Illuminate\Foundation\Http\FormRequest;
class ProductionAttendanceRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return ['employee_id'=>'required|exists:hrm_employees,id','order_id'=>'nullable|exists:garment_orders,id','line_name'=>'required|string|max:100','attendance_date'=>'required|date','status'=>'required|in:1,2,3','production_quantity'=>'required|integer|min:0','working_hours'=>'required|numeric|min:0|max:24','notes'=>'nullable|string']; }
}
