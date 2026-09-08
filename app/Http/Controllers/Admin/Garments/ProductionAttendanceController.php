<?php
namespace App\Http\Controllers\Admin\Garments;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\ProductionAttendanceRequest;
use App\Http\Services\Admin\Garments\ProductionAttendanceService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\ProductionAttendance;
use App\Models\HRM\HrmEmployee;
use Illuminate\Http\Request;
class ProductionAttendanceController extends Controller
{
    public function __construct(public ProductionAttendanceService $attendanceService) {}
    public function index(Request $request) { if($request->ajax()){return datatables(ProductionAttendance::with(['employee','order'])->latest('attendance_date'))->addIndexColumn()->addColumn('sl',function(){static $count=0;return ++$count;})->addColumn('employee_name',fn($row)=>e($row->employee?->full_name??'N/A'))->addColumn('order_number',fn($row)=>e($row->order?->order_number??'N/A'))->addColumn('status_display',fn($row)=>['1'=>'Present','2'=>'Absent','3'=>'Leave'][$row->status]??'Unknown')->addColumn('action',fn($row)=>'<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.production-attendance.edit',$row->id).'\', \'#edit-production-attendance-modal\')">Edit</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.production-attendance.destroy',$row->id).'\', \'garmentProductionAttendanceDataTable\')">Delete</a></li></ul></div></div>')->rawColumns(['action'])->make(true);} return view('admin.garments.production-attendance.index',['title'=>__('Production-linked Attendance'),'employees'=>HrmEmployee::where('status',EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get(),'orders'=>GarmentOrder::latest()->get(),'activeGarments'=>'active','activeGarmentProductionAttendance'=>'active','showGarmentsMenu'=>'show']); }
    public function store(ProductionAttendanceRequest $request){return $this->attendanceService->store($request);} public function edit($id){$attendance=ProductionAttendance::findOrFail($id);$employees=HrmEmployee::where('status',EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get();$orders=GarmentOrder::latest()->get();return view('admin.garments.production-attendance.form',compact('attendance','employees','orders'));} public function update(ProductionAttendanceRequest $request,$id){$request->merge(['id'=>$id]);return $this->attendanceService->store($request);} public function destroy($id){return $this->attendanceService->destroy($id);}
}
