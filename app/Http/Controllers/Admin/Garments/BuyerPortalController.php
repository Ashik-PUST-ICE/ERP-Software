<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Buyer;
use App\Models\Garments\GarmentOrder;
use Illuminate\Http\Request;

class BuyerPortalController extends Controller
{
    public function index(Request $request)
    {
        $buyers = Buyer::where('status', STATUS_ACTIVE)->orderBy('company_name')->get();
        $buyer = $request->filled('buyer_id') ? Buyer::findOrFail($request->integer('buyer_id')) : $buyers->first();
        $orders = $buyer ? GarmentOrder::where('buyer_id', $buyer->id)->latest('order_date')->get() : collect();

        return view('admin.garments.buyer-portal.index', [
            'title' => __('Buyer Portal'),
            'buyers' => $buyers,
            'buyer' => $buyer,
            'orders' => $orders,
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
