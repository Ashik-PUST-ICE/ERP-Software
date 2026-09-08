<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SewingProduction extends Model
{
    use HasFactory;

    protected $table = 'garment_sewing_productions';

    protected $fillable = [
        'order_id', 'line_name', 'production_date', 'daily_target', 'hourly_target',
        'hourly_output', 'total_output', 'wip_quantity', 'status', 'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
        'daily_target' => 'integer', 'hourly_target' => 'integer',
        'hourly_output' => 'integer', 'total_output' => 'integer', 'wip_quantity' => 'integer',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
