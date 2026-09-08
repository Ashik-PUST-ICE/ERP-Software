<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\EfficiencyRequest;
use App\Http\Services\Admin\Garments\EfficiencyService;
use App\Models\Garments\Efficiency;
use App\Models\Garments\GarmentOrder;
use App\Models\HRM\HrmEmployee;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class EfficiencyController extends Controller
{
    use ResponseTrait;
    public function __construct(public EfficiencyService $efficiencyService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $efficiencies = Efficiency::with(['employee', 'order'])->orderByDesc('work_date')->orderByDesc('id');
            return datatables($efficiencies)->addIndexColumn()->addColumn('sl', function ($row) { static $count = 0; return ++$count; })
                ->addColumn('operator_name', fn ($row) => e($row->employee?->full_name ?? 'Machine / Unassigned'))
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('date_display', fn ($row) => $row->work_date?->format('d M Y') ?? 'N/A')
                ->addColumn('efficiency_display', fn ($row) => number_format((float) $row->efficiency_percentage, 2) . '%')
                ->addColumn('status', function ($row) { [$label, $class] = garmentEfficiencyStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning']; return '<div class="zBadge ' . $class . '">' . __($label) . '</div>'; })
                ->addColumn('action', function ($row) { return '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.efficiency.edit', $row->id) . '\', \'#edit-efficiency-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.efficiency.destroy', $row->id) . '\', \'garmentEfficiencyDataTable\')">' . __('Delete') . '</a></li></ul></div></div>'; })
                ->rawColumns(['status', 'action'])->make(true);
        }
        return view('admin.garments.efficiency.index', ['title' => __('Operator / Machine Efficiency'), 'employees' => HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get(), 'orders' => GarmentOrder::orderByDesc('id')->get(), 'activeGarments' => 'active', 'activeGarmentEfficiency' => 'active', 'showGarmentsMenu' => 'show']);
    }

    public function store(EfficiencyRequest $request) { return $this->efficiencyService->store($request); }
    public function edit($id) { $efficiency = Efficiency::findOrFail($id); $employees = HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get(); $orders = GarmentOrder::orderByDesc('id')->get(); return view('admin.garments.efficiency.form', compact('efficiency', 'employees', 'orders')); }
    public function update(EfficiencyRequest $request, $id) { $request->merge(['id' => $id]); return $this->efficiencyService->store($request); }
    public function destroy($id) { return $this->efficiencyService->destroy($id); }
}
