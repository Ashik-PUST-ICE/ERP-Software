<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CostingVarianceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $orders = GarmentOrder::with(['costing', 'profitLoss', 'buyer'])
                ->when($request->filled('order_id'), fn ($query) => $query->whereKey($request->integer('order_id')))
                ->latest();

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('buyer', fn ($order) => $order->buyer?->company_name ?? '-')
                ->addColumn('estimated_cost', fn ($order) => number_format((float) ($order->costing?->total_cost ?? 0), 2))
                ->addColumn('actual_cost', fn ($order) => number_format((float) ($order->profitLoss?->total_cost ?? 0), 2))
                ->addColumn('variance', fn ($order) => number_format((float) ($order->profitLoss?->total_cost ?? 0) - (float) ($order->costing?->total_cost ?? 0), 2))
                ->addColumn('margin', fn ($order) => number_format((float) ($order->profitLoss?->profit_margin ?? $order->costing?->profit_margin ?? 0), 2) . '%')
                ->rawColumns(['variance', 'margin'])
                ->make(true);
        }

        $orderOptions = GarmentOrder::latest()->get();

        return view('admin.garments.costing-variance.index', [
            'title' => __('Costing & Variance Analysis'),
            'orderOptions' => $orderOptions,
            'activeGarments' => 'active',
            'activeGarmentCostingVariance' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }
}
