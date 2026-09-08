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
use Illuminate\Support\Facades\DB;

class MerchandiserInsightsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        return view('admin.garments.merchandiser.insights', [
            'title' => __('Merchandiser Dashboard'),
            'users' => User::where('status', STATUS_ACTIVE)->orderBy('name')->get(),
            'orderCount' => GarmentOrder::whereHas('merchandisers')->count(),
            'overdueTasks' => MerchandiserTask::whereDate('due_date', '<', $today)->where('status', STATUS_PENDING)->count(),
            'deliveryRisk' => GarmentOrder::whereDate('delivery_date', '<=', $today->copy()->addDays(7))->whereNotIn('status', [GARMENT_ORDER_STATUS_COMPLETED, GARMENT_ORDER_STATUS_CANCELLED])->count(),
            'buyerCount' => Buyer::whereHas('orders.merchandisers')->count(),
            'handovers' => MerchandiserHandover::with(['order', 'fromUser', 'toUser'])->latest('handed_over_at')->take(15)->get(),
            'orders' => GarmentOrder::with('buyer')->whereNotIn('status', [GARMENT_ORDER_STATUS_COMPLETED, GARMENT_ORDER_STATUS_CANCELLED])->orderBy('delivery_date')->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
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
