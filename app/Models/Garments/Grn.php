<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grn extends Model
{
    use HasFactory;

    protected $table = 'garment_grns';

    protected $fillable = [
        'material_id', 'grn_number', 'supplier_name', 'purchase_reference',
        'received_date', 'ordered_quantity', 'received_quantity', 'rejected_quantity',
        'accepted_quantity', 'unit_cost', 'status', 'notes',
    ];

    protected $casts = [
        'received_date' => 'date',
        'ordered_quantity' => 'decimal:4',
        'received_quantity' => 'decimal:4',
        'rejected_quantity' => 'decimal:4',
        'accepted_quantity' => 'decimal:4',
        'unit_cost' => 'decimal:4',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }
}
