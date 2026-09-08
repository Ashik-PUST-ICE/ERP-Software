<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\OrderProfitLossRequest;
use App\Http\Services\Admin\Garments\OrderProfitLossService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\OrderProfitLoss;
use Illuminate\Http\Request;

class OrderProfitLossController extends Controller
{
    public function __construct(public OrderProfitLossService $profitLossService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(OrderProfitLoss::with('order')->latest('id'))
                ->addIndexColumn()->addColumn('sl', function () { static $count = 0; return ++$count; })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('profit_display', fn ($row) => number_format((float) $row->profit_amount, 2).' ('.number_format((float) $row->profit_margin, 2).'%)')
                ->addColumn('status', function ($row) { [$label, $class] = garmentProfitLossStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning']; return '<div class="zBadge '.$class.'">'.__($label).'</div>'; })
                ->addColumn('action', fn ($row) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.profit-loss.edit', $row->id).'\', \'#edit-profit-loss-modal\')">'.__('Edit').'</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.profit-loss.destroy', $row->id).'\', \'garmentProfitLossDataTable\')">'.__('Delete').'</a></li></ul></div></div>')
                ->rawColumns(['status', 'action'])->make(true);
        }
        return view('admin.garments.profit-loss.index', ['title' => __('Order-wise Profit & Loss'), 'orders' => GarmentOrder::latest()->get(), 'activeGarments' => 'active', 'activeGarmentProfitLoss' => 'active', 'showGarmentsMenu' => 'show']);
    }

    public function store(OrderProfitLossRequest $request) { return $this->profitLossService->store($request); }
    public function edit($id) { $profitLoss = OrderProfitLoss::findOrFail($id); $orders = GarmentOrder::latest()->get(); return view('admin.garments.profit-loss.form', compact('profitLoss', 'orders')); }
    public function update(OrderProfitLossRequest $request, $id) { $request->merge(['id' => $id]); return $this->profitLossService->store($request); }
    public function destroy($id) { return $this->profitLossService->destroy($id); }
}
