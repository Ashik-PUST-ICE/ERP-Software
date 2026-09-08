<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinishingEntry extends Model
{
    use HasFactory;

    protected $table = 'garment_finishing_entries';

    protected $fillable = [
        'order_id', 'finishing_date', 'received_quantity', 'passed_quantity',
        'rework_quantity', 'rejected_quantity', 'status', 'remarks',
    ];

    protected $casts = [
        'finishing_date' => 'date',
        'received_quantity' => 'integer',
        'passed_quantity' => 'integer',
        'rework_quantity' => 'integer',
        'rejected_quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
