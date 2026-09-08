<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\FinishingEntry;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Material;
use App\Models\Garments\ShipmentDocument;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class GarmentDashboardController extends Controller
{
    public function index()
    {
        return view('admin.garments.dashboard', [
            'title' => __('Garments Dashboard'),
            'activeGarments' => 'active',
            'activeGarmentDashboard' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function data(): JsonResponse
    {
        $today = Carbon::today();
        $orders = GarmentOrder::with('buyer')->latest()->take(8)->get();
        $notifications = Notification::where(function ($query) {
            $query->whereNull('user_id')->orWhere('user_id', auth()->id());
        })->latest()->take(8)->get();

        return response()->json([
            'cards' => [
                'overdueOrders' => GarmentOrder::whereDate('delivery_date', '<', $today)->whereNotIn('status', [GARMENT_ORDER_STATUS_COMPLETED, GARMENT_ORDER_STATUS_CANCELLED])->count(),
                'lowStock' => Material::whereColumn('current_stock', '<=', 'reorder_level')->count(),
                'pendingFinishing' => FinishingEntry::whereIn('status', [GARMENT_FINISHING_STATUS_PENDING, GARMENT_FINISHING_STATUS_IN_PROGRESS])->count(),
                'readyShipments' => ShipmentDocument::where('status', GARMENT_SHIPMENT_STATUS_READY)->count(),
            ],
            'orders' => $orders->map(fn ($order) => [
                'number' => $order->order_number,
                'buyer' => $order->buyer?->company_name ?? __('N/A'),
                'quantity' => number_format($order->quantity),
                'delivery' => $order->delivery_date?->format('d M Y') ?? __('Not set'),
            ])->values(),
            'notifications' => $notifications->map(fn ($notification) => [
                'title' => $notification->title,
                'body' => $notification->body,
            ])->values(),
        ]);
    }
}
