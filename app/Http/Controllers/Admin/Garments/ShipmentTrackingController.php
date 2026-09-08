<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\ShipmentDocument;
use App\Models\Garments\ShipmentTrackingEvent;
use Illuminate\Http\Request;

class ShipmentTrackingController extends Controller
{
    public function page($id)
    {
        return view('admin.garments.shipment-documents.tracking', [
            'title' => __('Shipment Tracking'),
            'shipment' => ShipmentDocument::with('order')->findOrFail($id),
            'events' => ShipmentTrackingEvent::where('shipment_document_id', $id)->latest('event_at')->get(),
            'activeGarments' => 'active',
            'activeGarmentShipmentDocuments' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'shipment' => ShipmentDocument::with('order')->findOrFail($id),
            'events' => ShipmentTrackingEvent::where('shipment_document_id', $id)->latest('event_at')->get(),
        ]);
    }

    public function store(Request $request, $id)
    {
        $data = $request->validate([
            'location' => 'nullable|string|max:150',
            'status' => 'required|string|max:60',
            'event_at' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        ShipmentDocument::findOrFail($id);
        $data['shipment_document_id'] = $id;
        return response()->json(ShipmentTrackingEvent::create($data), 201);
    }
}
