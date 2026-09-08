<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentDocument extends Model
{
    use HasFactory;

    protected $table = 'garment_shipment_documents';
    protected $fillable = ['order_id', 'document_type', 'document_number', 'document_date', 'shipper', 'consignee', 'port_of_loading', 'port_of_discharge', 'carrier', 'shipment_date', 'status', 'approval_status', 'approved_by', 'approved_at', 'notes'];
    protected $casts = ['document_date' => 'date', 'shipment_date' => 'date', 'approved_at' => 'datetime'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
