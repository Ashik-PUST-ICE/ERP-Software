<?php

namespace App\Http\Requests\Admin\Garments;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentDocumentRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:garment_orders,id',
            'document_type' => 'required|in:' . implode(',', array_keys(garmentShipmentDocumentTypes())),
            'document_number' => 'required|string|max:80',
            'document_date' => 'required|date',
            'shipper' => 'nullable|string|max:160',
            'consignee' => 'nullable|string|max:160',
            'port_of_loading' => 'nullable|string|max:120',
            'port_of_discharge' => 'nullable|string|max:120',
            'carrier' => 'nullable|string|max:120',
            'shipment_date' => 'nullable|date',
            'status' => 'required|in:' . implode(',', array_keys(garmentShipmentStatuses())),
            'notes' => 'nullable|string',
        ];
    }
}
