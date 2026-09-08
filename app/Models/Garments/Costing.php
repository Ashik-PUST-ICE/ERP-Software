<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Costing extends Model
{
    use HasFactory;

    protected $table = 'garment_costings';

    protected $fillable = [
        'order_id', 'fabric_cost', 'trims_cost', 'accessories_cost', 'cm_cost',
        'washing_cost', 'printing_cost', 'embroidery_cost', 'overhead_cost',
        'other_cost', 'total_cost', 'fob_price', 'profit_amount', 'profit_margin',
        'status', 'notes',
    ];

    protected $casts = [
        'fabric_cost' => 'decimal:4', 'trims_cost' => 'decimal:4', 'accessories_cost' => 'decimal:4',
        'cm_cost' => 'decimal:4', 'washing_cost' => 'decimal:4', 'printing_cost' => 'decimal:4',
        'embroidery_cost' => 'decimal:4', 'overhead_cost' => 'decimal:4', 'other_cost' => 'decimal:4',
        'total_cost' => 'decimal:4', 'fob_price' => 'decimal:4', 'profit_amount' => 'decimal:4',
        'profit_margin' => 'decimal:4',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
