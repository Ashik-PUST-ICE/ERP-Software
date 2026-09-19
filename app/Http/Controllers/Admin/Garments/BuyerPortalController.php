<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Buyer;
use App\Models\Garments\GarmentOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BuyerPortalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $section = $request->query('section');

            if ($section === 'summary') {
                $buyerId = $request->query('buyer_id');
                $buyer = $buyerId ? Buyer::findOrFail($buyerId) : Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->first();
                $orders = $buyer ? GarmentOrder::where('buyer_id', $buyer->id)->get() : collect();

                return response()->json([
                    'buyer' => $buyer ? [
                        'company_name' => $buyer->company_name,
                        'email' => $buyer->email,
                    ] : null,
                    'total_orders' => $orders->count(),
                    'total_quantity' => (int) $orders->sum('quantity'),
                ]);
            }

            $buyerId = $request->query('buyer_id');
            $orders = GarmentOrder::query()
                ->when($buyerId, fn ($q) => $q->where('buyer_id', $buyerId))
                ->latest('order_date');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('description', fn ($data) => $data->product_description ?: '-')
                ->addColumn('delivery_date', fn ($data) => $data->delivery_date?->format('d M Y'))
                ->addColumn('quantity', fn ($data) => number_format($data->quantity))
                ->addColumn('status', fn ($data) => garmentOrderStatuses()[$data->status] ?? $data->status)
                ->rawColumns(['status'])
                ->make(true);
        }

        $buyers = Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->get();
        $buyer = $request->filled('buyer_id') ? Buyer::findOrFail($request->integer('buyer_id')) : $buyers->first();

        return view('admin.garments.buyer-portal.index', [
            'title' => __('Buyer Portal'),
            'buyers' => $buyers,
            'buyer' => $buyer,
            'activeGarments' => 'active',
            'activeGarmentBuyerPortal' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function show($id)
    {
        $order = GarmentOrder::with(['buyer', 'shipmentDocuments', 'tnaTasks', 'productionPlans', 'sewingProductions'])->findOrFail($id);
        return view('admin.garments.buyer-portal.show', ['title' => __('Buyer Order Details'), 'order' => $order, 'activeGarments' => 'active', 'activeGarmentBuyerPortal' => 'active', 'showGarmentsMenu' => 'show']);
    }
}
