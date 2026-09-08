<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\TnaTaskRequest;
use App\Http\Services\Admin\Garments\TnaTaskService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\TnaTask;
use App\Models\HRM\HrmEmployee;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TnaTaskController extends Controller
{
    use ResponseTrait;

    public function __construct(public TnaTaskService $tnaTaskService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = TnaTask::with(['order.style', 'order.buyer', 'employee'])->orderBy('planned_date')->orderByDesc('id');

            return datatables($tasks)
                ->addIndexColumn()
                ->addColumn('sl', function ($task) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('order_number', fn ($task) => e($task->order?->order_number ?? 'N/A'))
                ->addColumn('style_code', fn ($task) => e($task->order?->style?->style_code ?? 'N/A'))
                ->addColumn('planned_date_display', fn ($task) => $task->planned_date?->format('d M Y') ?? 'N/A')
                ->addColumn('employee_name', fn ($task) => e($task->employee?->full_name ?? 'Unassigned'))
                ->addColumn('status', function ($task) {
                    [$label, $class] = garmentTnaStatuses()[$task->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($task) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.tna.edit', $task->id) . '\', \'#edit-tna-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.tna.destroy', $task->id) . '\', \'garmentTnaDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.tna.index', [
            'title' => __('TNA Calendar'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->orderByDesc('id')->get(),
            'employees' => HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get(),
            'activeGarments' => 'active',
            'activeGarmentTna' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(TnaTaskRequest $request)
    {
        return $this->tnaTaskService->store($request);
    }

    public function edit($id)
    {
        $task = TnaTask::findOrFail($id);
        $orders = GarmentOrder::with(['style', 'buyer'])->orderByDesc('id')->get();
        $employees = HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->orderBy('first_name')->get();
        return view('admin.garments.tna.form', compact('task', 'orders', 'employees'));
    }

    public function update(TnaTaskRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->tnaTaskService->store($request);
    }

    public function destroy($id)
    {
        return $this->tnaTaskService->destroy($id);
    }
}
