<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackingList extends Model
{
    use HasFactory;

    protected $table = 'garment_packing_lists';

    protected $fillable = [
        'order_id', 'packing_date', 'carton_number', 'color', 'size', 'quantity',
        'gross_weight', 'net_weight', 'carton_length', 'carton_width',
        'carton_height', 'status', 'notes',
    ];

    protected $casts = [
        'packing_date' => 'date',
        'quantity' => 'integer',
        'gross_weight' => 'decimal:3',
        'net_weight' => 'decimal:3',
        'carton_length' => 'decimal:2',
        'carton_width' => 'decimal:2',
        'carton_height' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
