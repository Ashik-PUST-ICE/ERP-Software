<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\ShipmentDocumentRequest;
use App\Http\Services\Admin\Garments\ShipmentDocumentService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\ShipmentDocument;
use Illuminate\Http\Request;

class ShipmentDocumentController extends Controller
{
    public function __construct(public ShipmentDocumentService $documentService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(ShipmentDocument::with('order')->latest('document_date'))
                ->addIndexColumn()->addColumn('sl', function () { static $count = 0; return ++$count; })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('type_display', fn ($row) => __(garmentShipmentDocumentTypes()[$row->document_type] ?? $row->document_type))
                ->addColumn('date_display', fn ($row) => $row->document_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($row) { [$label, $class] = garmentShipmentStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning']; return '<div class="zBadge '.$class.'">'.__($label).'</div>'; })
                ->addColumn('action', fn ($row) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\''.route('admin.garments.shipment-documents.edit', $row->id).'\', \'#edit-shipment-document-modal\')">'.__('Edit').'</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\''.route('admin.garments.shipment-documents.destroy', $row->id).'\', \'garmentShipmentDocumentDataTable\')">'.__('Delete').'</a></li></ul></div></div>')
                ->rawColumns(['status', 'action'])->make(true);
        }
        return view('admin.garments.shipment-documents.index', ['title' => __('Shipment & Export Documents'), 'orders' => GarmentOrder::latest()->get(), 'activeGarments' => 'active', 'activeGarmentShipmentDocuments' => 'active', 'showGarmentsMenu' => 'show']);
    }

    public function store(ShipmentDocumentRequest $request) { return $this->documentService->store($request); }
    public function edit($id) { $document = ShipmentDocument::findOrFail($id); $orders = GarmentOrder::latest()->get(); return view('admin.garments.shipment-documents.form', compact('document', 'orders')); }
    public function update(ShipmentDocumentRequest $request, $id) { $request->merge(['id' => $id]); return $this->documentService->store($request); }
    public function destroy($id) { return $this->documentService->destroy($id); }
}
