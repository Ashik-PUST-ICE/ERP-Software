<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Buyer;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\MerchandiserHandover;
use App\Models\Garments\MerchandiserTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MerchandiserInsightsController extends Controller
{
    public function index()
    {
        return view('admin.garments.merchandiser.insights', [
            'title' => __('Merchandiser Dashboard'),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function data(): JsonResponse
    {
        $today = Carbon::today();
        $excludedStatuses = [GARMENT_ORDER_STATUS_COMPLETED, GARMENT_ORDER_STATUS_CANCELLED];
        $orders = GarmentOrder::with('buyer')->whereNotIn('status', $excludedStatuses)->orderBy('delivery_date')->get();

        return response()->json([
            'cards' => [
                'orders' => GarmentOrder::whereHas('merchandisers')->count(),
                'overdue_tasks' => MerchandiserTask::whereDate('due_date', '<', $today)->where('status', STATUS_PENDING)->count(),
                'delivery_risk' => GarmentOrder::whereDate('delivery_date', '<=', $today->copy()->addDays(7))->whereNotIn('status', $excludedStatuses)->count(),
                'buyers' => Buyer::whereHas('orders.merchandisers')->count(),
            ],
            'orders' => $orders->map(fn ($order) => [
                'id' => $order->id,
                'label' => trim($order->order_number . ' - ' . ($order->buyer?->company_name ?: __('Buyer not assigned'))),
            ])->values(),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(['id', 'name']),
            'handovers' => MerchandiserHandover::with(['order', 'fromUser', 'toUser'])
                ->latest('handed_over_at')->take(15)->get()->map(fn ($handover) => [
                    'order' => $handover->order?->order_number ?: __('Order not assigned'),
                    'from' => $handover->fromUser?->name ?: __('System'),
                    'to' => $handover->toUser?->name ?: __('Unknown'),
                    'date' => $handover->handed_over_at?->format('d M Y, h:i A') ?: __('Not set'),
                ])->values(),
        ]);
    }

    public function handover(Request $request)
    {
        $data = $request->validate(['order_id' => 'required|exists:garment_orders,id', 'to_user_id' => 'required|exists:users,id', 'notes' => 'required|string']);
        $order = GarmentOrder::findOrFail($data['order_id']);
        $fromUserId = $order->merchandisers()->wherePivot('is_primary', true)->value('users.id') ?: auth()->id();
        DB::transaction(function () use ($order, $data, $fromUserId) {
            $order->merchandisers()->updateExistingPivot($fromUserId, ['is_primary' => false]);
            $order->merchandisers()->syncWithoutDetaching([$data['to_user_id'] => ['is_primary' => true]]);
            MerchandiserHandover::create(['order_id' => $order->id, 'from_user_id' => $fromUserId, 'to_user_id' => $data['to_user_id'], 'notes' => $data['notes'], 'handed_over_at' => now()]);
        });
        return back()->with('success', __('Order handed over successfully.'));
    }
}
