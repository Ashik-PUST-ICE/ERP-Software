<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\ShipmentDocument;
use App\Models\Garments\TnaTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MerchandiserController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->query('section');

        if ($section === 'overdue_tasks' || $section === 'shipments') {
            if ($section === 'overdue_tasks') {
                $today = Carbon::today();
                $items = TnaTask::with('order')
                    ->whereDate('planned_date', '<', $today)
                    ->whereNotIn('status', [GARMENT_TNA_STATUS_COMPLETED, GARMENT_TNA_STATUS_CANCELLED])
                    ->orderBy('planned_date')
                    ->get()
                    ->map(fn ($task) => [
                        'task_name' => $task->task_name,
                        'order_number' => $task->order?->order_number,
                        'planned_date' => $task->planned_date?->format('d M Y'),
                    ]);
            } else {
                $items = ShipmentDocument::with('order')
                    ->whereIn('status', [GARMENT_SHIPMENT_STATUS_DRAFT, GARMENT_SHIPMENT_STATUS_READY])
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(fn ($shipment) => [
                        'document_number' => $shipment->document_number,
                        'order_number' => $shipment->order?->order_number,
                        'carrier' => $shipment->carrier,
                    ]);
            }

            return response()->json([
                $section === 'overdue_tasks' ? 'overdue_tasks' : 'shipments' => $items,
            ]);
        }

        if ($request->ajax() && $request->query('section') === 'orders') {
            $orders = GarmentOrder::with('buyer')
                ->whereNotIn('status', [GARMENT_ORDER_STATUS_COMPLETED, GARMENT_ORDER_STATUS_CANCELLED])
                ->orderBy('delivery_date');

            return DataTables::of($orders)
                ->addIndexColumn()
                ->addColumn('buyer', fn ($data) => $data->buyer?->company_name)
                ->addColumn('delivery_date', fn ($data) => $data->delivery_date?->format('d M Y'))
                ->addColumn('quantity', fn ($data) => number_format($data->quantity))
                ->rawColumns(['buyer', 'delivery_date', 'quantity'])
                ->make(true);
        }

        return view('admin.garments.merchandiser.index', [
            'title' => __('Merchandiser Workspace'),
            'activeGarments' => 'active',
            'activeGarmentMerchandiser' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }
}
