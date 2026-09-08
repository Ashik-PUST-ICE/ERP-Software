<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cutting extends Model
{
    use HasFactory;

    protected $table = 'garment_cuttings';

    protected $fillable = [
        'order_id', 'material_id', 'marker_number', 'fabric_consumption',
        'marker_efficiency', 'planned_cut_quantity', 'cut_quantity', 'panel_quantity',
        'status', 'cutting_date', 'notes',
    ];

    protected $casts = [
        'fabric_consumption' => 'decimal:4',
        'marker_efficiency' => 'decimal:4',
        'cutting_date' => 'date',
        'planned_cut_quantity' => 'integer',
        'cut_quantity' => 'integer',
        'panel_quantity' => 'integer',
    ];

    public function order(): BelongsTo { return $this->belongsTo(GarmentOrder::class, 'order_id'); }
    public function material(): BelongsTo { return $this->belongsTo(Material::class, 'material_id'); }
}
