<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionPlan extends Model
{
    use HasFactory;

    protected $table = 'garment_production_plans';

    protected $fillable = [
        'order_id', 'line_name', 'planned_quantity', 'daily_target',
        'capacity_per_day', 'start_date', 'end_date', 'status', 'notes',
    ];

    protected $casts = [
        'planned_quantity' => 'integer',
        'daily_target' => 'integer',
        'capacity_per_day' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
