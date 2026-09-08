<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\ShipmentDocument;
use App\Models\Garments\TnaTask;
use Carbon\Carbon;

class MerchandiserController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        return view('admin.garments.merchandiser.index', [
            'title' => __('Merchandiser Workspace'),
            'orders' => GarmentOrder::with('buyer')->whereNotIn('status', [GARMENT_ORDER_STATUS_COMPLETED, GARMENT_ORDER_STATUS_CANCELLED])->orderBy('delivery_date')->take(15)->get(),
            'overdueTasks' => TnaTask::with('order')->whereDate('planned_date', '<', $today)->whereNotIn('status', [GARMENT_TNA_STATUS_COMPLETED, GARMENT_TNA_STATUS_CANCELLED])->orderBy('planned_date')->get(),
            'shipments' => ShipmentDocument::with('order')->whereIn('status', [GARMENT_SHIPMENT_STATUS_DRAFT, GARMENT_SHIPMENT_STATUS_READY])->latest()->take(10)->get(),
            'activeGarments' => 'active', 'activeGarmentMerchandiser' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }
}
