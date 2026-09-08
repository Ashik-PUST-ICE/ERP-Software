<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\CostingRequest;
use App\Http\Services\Admin\Garments\CostingService;
use App\Models\Garments\Costing;
use App\Models\Garments\GarmentOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CostingController extends Controller
{
    use ResponseTrait;

    public function __construct(public CostingService $costingService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $costings = Costing::with(['order.style', 'order.buyer'])->orderByDesc('id');

            return datatables($costings)
                ->addIndexColumn()
                ->addColumn('sl', function ($costing) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('order_number', fn ($costing) => e($costing->order?->order_number ?? 'N/A'))
                ->addColumn('style_name', fn ($costing) => e($costing->order?->style?->style_code ?? 'N/A'))
                ->addColumn('buyer_name', fn ($costing) => e($costing->order?->buyer?->company_name ?? 'N/A'))
                ->addColumn('total_cost_display', fn ($costing) => number_format((float) $costing->total_cost, 4))
                ->addColumn('fob_price_display', fn ($costing) => number_format((float) $costing->fob_price, 4))
                ->addColumn('status', function ($costing) {
                    [$label, $class] = garmentCostingStatuses()[$costing->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($costing) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.costings.edit', $costing->id) . '\', \'#edit-costing-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.costings.destroy', $costing->id) . '\', \'garmentCostingDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.costings.index', [
            'title' => __('Costing Sheets'),
            'orders' => GarmentOrder::with(['style', 'buyer'])->whereDoesntHave('costing')->orderByDesc('id')->get(),
            'activeGarments' => 'active',
            'activeGarmentCostings' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(CostingRequest $request)
    {
        return $this->costingService->store($request);
    }

    public function edit($id)
    {
        $costing = Costing::with('order.style')->findOrFail($id);
        $orders = GarmentOrder::with(['style', 'buyer'])->where(function ($query) use ($costing) {
            $query->whereDoesntHave('costing')->orWhere('id', $costing->order_id);
        })->orderByDesc('id')->get();
        return view('admin.garments.costings.form', compact('costing', 'orders'));
    }

    public function update(CostingRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->costingService->store($request);
    }

    public function destroy($id)
    {
        return $this->costingService->destroy($id);
    }
}
