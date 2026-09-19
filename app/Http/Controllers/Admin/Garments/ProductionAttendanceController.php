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
    public function index(Request $request) {
        if ($request->ajax()) {
            return datatables(ProductionAttendance::with(['employee', 'order'])->latest('attendance_date'))
                ->addIndexColumn()
                ->addColumn('sl', function() { static $count = 0; return ++$count; })
                ->addColumn('employee_name', fn($row) => e($row->employee?->full_name ?? 'N/A'))
                ->addColumn('line_name', fn($row) => e($row->line_name ?? 'N/A'))
                ->addColumn('order_number', fn($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('attendance_date', fn($row) => $row->attendance_date ? date('Y-m-d', strtotime($row->attendance_date)) : 'N/A')
                ->addColumn('status_display', function($row) {
                    $badges = [
                        '1' => '<div class="zBadge zBadge-active">' . __('Present') . '</div>',
                        '2' => '<div class="zBadge zBadge-deactivate">' . __('Absent') . '</div>',
                        '3' => '<div class="zBadge zBadge-warning">' . __('Leave') . '</div>',
                    ];
                    return $badges[$row->status] ?? '<div class="zBadge zBadge-warning">' . __('Unknown') . '</div>';
                })
                ->addColumn('production_quantity', fn($row) => number_format((float)($row->production_quantity ?? 0)))
                ->addColumn('action', fn($row) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.production-attendance.edit', $row->id).'\', \'#edit-modal\')">'.__('Edit').'</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.production-attendance.destroy', $row->id).'\', \'garmentProductionAttendanceDataTable\')">'.__('Delete').'</a></li></ul></div></div>')
                ->rawColumns(['status_display', 'action'])
                ->make(true);
        }
        return view('admin.garments.production-attendance.index', [
            'title' => __('Production-linked Attendance'),
            'employees' => HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get(),
            'orders' => GarmentOrder::latest()->get(),
            'activeGarments' => 'active',
            'activeGarmentProductionAttendance' => 'active',
            'showGarmentsMenu' => 'show'
        ]);
    }
    public function store(ProductionAttendanceRequest $request){return $this->attendanceService->store($request);} public function edit($id){$attendance=ProductionAttendance::findOrFail($id);$employees=HrmEmployee::where('status',EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get();$orders=GarmentOrder::latest()->get();return view('admin.garments.production-attendance.form',compact('attendance','employees','orders'));} public function update(ProductionAttendanceRequest $request,$id){$request->merge(['id'=>$id]);return $this->attendanceService->store($request);} public function destroy($id){return $this->attendanceService->destroy($id);}
}
