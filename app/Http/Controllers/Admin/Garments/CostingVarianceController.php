<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentOrder;
use Illuminate\Http\Request;

class CostingVarianceController extends Controller
{
    public function index(Request $request)
    {
        $orders = GarmentOrder::with(['costing', 'profitLoss', 'buyer'])
            ->when($request->filled('order_id'), fn ($query) => $query->whereKey($request->integer('order_id')))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.garments.costing-variance.index', [
            'title' => __('Costing & Variance Analysis'),
            'orders' => $orders,
            'orderOptions' => GarmentOrder::latest()->get(),
            'activeGarments' => 'active',
            'activeGarmentCostingVariance' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }
}
