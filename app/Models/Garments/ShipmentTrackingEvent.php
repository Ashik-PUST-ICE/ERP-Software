<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class ShipmentTrackingEvent extends Model
{
    protected $table = 'garment_shipment_tracking_events';
    protected $fillable = ['shipment_document_id', 'location', 'status', 'event_at', 'notes'];
    protected $casts = ['event_at' => 'datetime'];
}
