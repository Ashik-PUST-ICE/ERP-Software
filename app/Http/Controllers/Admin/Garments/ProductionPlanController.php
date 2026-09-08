<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\ProductionPlanRequest;
use App\Http\Services\Admin\Garments\ProductionPlanService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\ProductionPlan;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ProductionPlanController extends Controller
{
    use ResponseTrait;

    public function __construct(public ProductionPlanService $planService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $plans = ProductionPlan::with(['order.style', 'order.buyer'])->orderBy('start_date')->orderByDesc('id');

            return datatables($plans)
                ->addIndexColumn()
                ->addColumn('sl', function ($plan) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('order_number', fn ($plan) => e($plan->order?->order_number ?? 'N/A'))
                ->addColumn('style_code', fn ($plan) => e($plan->order?->style?->style_code ?? 'N/A'))
                ->addColumn('quantity_display', fn ($plan) => number_format($plan->planned_quantity))
                ->addColumn('timeline', fn ($plan) => ($plan->start_date?->format('d M Y') ?? 'N/A') . ' - ' . ($plan->end_date?->format('d M Y') ?? 'N/A'))
                ->addColumn('status', function ($plan) {
                    [$label, $class] = garmentPlanStatuses()[$plan->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($plan) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.plans.edit', $plan->id) . '\', \'#edit-plan-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.plans.destroy', $plan->id) . '\', \'garmentPlanDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.plans.index', [
            'title' => __('Production Planning'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->orderByDesc('id')->get(),
            'activeGarments' => 'active',
            'activeGarmentPlans' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(ProductionPlanRequest $request)
    {
        return $this->planService->store($request);
    }

    public function edit($id)
    {
        $plan = ProductionPlan::findOrFail($id);
        $orders = GarmentOrder::with(['style', 'buyer'])->orderByDesc('id')->get();
        return view('admin.garments.plans.form', compact('plan', 'orders'));
    }

    public function update(ProductionPlanRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->planService->store($request);
    }

    public function destroy($id)
    {
        return $this->planService->destroy($id);
    }
}
