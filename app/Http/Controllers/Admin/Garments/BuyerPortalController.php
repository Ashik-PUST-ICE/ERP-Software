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
                $buyer = $buyerId ? Buyer::find($buyerId) : Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->first();
                $orders = $buyer ? GarmentOrder::where('buyer_id', $buyer->id)->get() : collect();

                return response()->json([
                    'buyer' => $buyer ? [
                        'id' => $buyer->id,
                        'company_name' => $buyer->company_name,
                        'buyer_code' => $buyer->buyer_code ?? 'N/A',
                        'contact_person' => $buyer->contact_person ?? 'N/A',
                        'email' => $buyer->email ?? '',
                        'phone' => $buyer->phone ?? '',
                        'website' => $buyer->website ?? '',
                        'country' => $buyer->country ?? 'N/A',
                        'currency' => $buyer->currency ?? 'USD',
                        'payment_terms' => $buyer->payment_terms ?? 'N/A',
                        'office_address' => $buyer->office_address ?? '',
                    ] : null,
                    'total_orders' => $orders->count(),
                    'total_quantity' => (int) $orders->sum('quantity'),
                    'in_production_orders' => $orders->where('status', GARMENT_ORDER_STATUS_IN_PRODUCTION)->count(),
                    'completed_orders' => $orders->where('status', GARMENT_ORDER_STATUS_COMPLETED)->count(),
                ]);
            }

            $buyerId = $request->query('buyer_id');
            $orders = GarmentOrder::with(['style', 'buyer'])
                ->when($buyerId, fn ($q) => $q->where('buyer_id', $buyerId))
                ->latest('order_date');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('order_number_html', function ($data) {
                    return '<a href="' . route('admin.garments.buyer-portal.orders.show', $data->id) . '" class="text-primary font-weight-bold"><strong>' . e($data->order_number) . '</strong></a>';
                })
                ->addColumn('style', fn ($data) => $data->style ? e($data->style->style_code . ' - ' . $data->style->style_name) : '-')
                ->addColumn('description', fn ($data) => e($data->product_description ?: '-'))
                ->addColumn('order_date', fn ($data) => $data->order_date ? \Carbon\Carbon::parse($data->order_date)->format('d M Y') : '-')
                ->addColumn('delivery_date', fn ($data) => $data->delivery_date ? \Carbon\Carbon::parse($data->delivery_date)->format('d M Y') : '-')
                ->addColumn('quantity', fn ($data) => number_format((float) ($data->quantity ?? 0)))
                ->addColumn('status', function ($data) {
                    $statuses = garmentOrderStatuses();
                    [$label, $badgeClass] = $statuses[$data->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<span class="zBadge ' . $badgeClass . '">' . __($label) . '</span>';
                })
                ->addColumn('action', function ($data) {
                    return '<a href="' . route('admin.garments.buyer-portal.orders.show', $data->id) . '" class="primary-btn-outline py-1 px-3 d-inline-flex align-items-center gap-1" style="font-size: 12px; border-radius: 6px;">
                        <i class="fa-solid fa-eye"></i> ' . __('Details') . '
                    </a>';
                })
                ->rawColumns(['order_number_html', 'status', 'action'])
                ->make(true);
        }

        $buyers = Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->get();
        $buyer = $request->filled('buyer_id') ? Buyer::find($request->integer('buyer_id')) : $buyers->first();
        $buyerOrders = $buyer ? GarmentOrder::where('buyer_id', $buyer->id)->get() : collect();
        $stats = [
            'total_orders' => $buyerOrders->count(),
            'total_quantity' => (int) $buyerOrders->sum('quantity'),
            'in_production_orders' => $buyerOrders->where('status', GARMENT_ORDER_STATUS_IN_PRODUCTION)->count(),
            'completed_orders' => $buyerOrders->where('status', GARMENT_ORDER_STATUS_COMPLETED)->count(),
        ];

        return view('admin.garments.buyer-portal.index', [
            'title' => __('Buyer Portal'),
            'buyers' => $buyers,
            'buyer' => $buyer,
            'stats' => $stats,
            'activeGarments' => 'active',
            'activeGarmentBuyerPortal' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function show($id)
    {
        $order = GarmentOrder::with(['buyer', 'style', 'shipmentDocuments', 'tnaTasks', 'productionPlans', 'sewingProductions'])->findOrFail($id);
        return view('admin.garments.buyer-portal.show', [
            'title' => __('Buyer Order Details'),
            'order' => $order,
            'activeGarments' => 'active',
            'activeGarmentBuyerPortal' => 'active',
            'showGarmentsMenu' => 'show'
        ]);
    }
}
