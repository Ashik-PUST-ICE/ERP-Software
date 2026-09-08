<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\GarmentOrderRequest;
use App\Http\Services\Admin\Garments\GarmentOrderService;
use App\Models\Garments\Buyer;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Style;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class GarmentOrderController extends Controller
{
    use ResponseTrait;

    public function __construct(public GarmentOrderService $orderService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = GarmentOrder::with(['buyer', 'style'])->orderByDesc('id');

            return datatables($orders)
                ->addIndexColumn()
                ->addColumn('sl', function ($order) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('style_name', fn ($order) => e($order->style ? $order->style->style_code . ' - ' . $order->style->style_name : 'N/A'))
                ->addColumn('buyer_name', fn ($order) => e($order->buyer?->company_name ?? 'N/A'))
                ->addColumn('quantity_display', fn ($order) => number_format($order->quantity))
                ->addColumn('delivery_date_display', fn ($order) => $order->delivery_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($order) {
                    $labels = garmentOrderStatuses();
                    [$label, $class] = $labels[$order->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($order) {
                    return '<div class="inline-flex">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.orders.edit', $order->id) . '\', \'#edit-order-modal\')">' . __('Edit') . '</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.orders.destroy', $order->id) . '\', \'garmentOrderDataTable\')">' . __('Delete') . '</a></li>
                            </ul>
                        </div>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.orders.index', [
            'title' => __('Orders'),
            'buyers' => Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->get(),
            'styles' => Style::where('status', STATUS_ACTIVE)->orderBy('style_code')->get(),
            'activeGarments' => 'active',
            'activeGarmentOrders' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(GarmentOrderRequest $request)
    {
        return $this->orderService->store($request);
    }

    public function edit($id)
    {
        $order = GarmentOrder::findOrFail($id);
        $buyers = Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->get();
        $styles = Style::where('status', STATUS_ACTIVE)->orderBy('style_code')->get();
        return view('admin.garments.orders.form', compact('order', 'buyers', 'styles'));
    }

    public function update(GarmentOrderRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->orderService->store($request);
    }

    public function destroy($id)
    {
        return $this->orderService->destroy($id);
    }
}
