<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\PurchaseOrder;
use App\Models\Garments\ShipmentDocument;
use Illuminate\Http\JsonResponse;

class ApprovalController extends Controller
{
    public function purchaseOrder($id): JsonResponse
    {
        $order = PurchaseOrder::findOrFail($id);
        $order->update(['approval_status' => STATUS_ACTIVE, 'approved_by' => auth()->id(), 'approved_at' => now()]);
        return response()->json(['message' => __('Purchase order approved.'), 'data' => $order]);
    }

    public function shipment($id): JsonResponse
    {
        $shipment = ShipmentDocument::findOrFail($id);
        $shipment->update(['approval_status' => STATUS_ACTIVE, 'approved_by' => auth()->id(), 'approved_at' => now()]);
        return response()->json(['message' => __('Shipment document approved.'), 'data' => $shipment]);
    }
}
