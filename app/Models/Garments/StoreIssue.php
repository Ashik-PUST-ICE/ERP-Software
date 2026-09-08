<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreIssue extends Model
{
    use HasFactory;

    protected $table = 'garment_store_issues';

    protected $fillable = [
        'material_id', 'order_id', 'issue_number', 'section', 'line_name',
        'issue_date', 'issued_quantity', 'returned_quantity', 'net_quantity', 'status', 'notes',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'issued_quantity' => 'decimal:4',
        'returned_quantity' => 'decimal:4',
        'net_quantity' => 'decimal:4',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
