<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'garment_materials';

    protected $fillable = [
        'item_code', 'item_name', 'category', 'unit', 'opening_stock',
        'current_stock', 'reorder_level', 'warehouse', 'location', 'status', 'notes',
    ];

    protected $casts = [
        'opening_stock' => 'decimal:4',
        'current_stock' => 'decimal:4',
        'reorder_level' => 'decimal:4',
    ];
}
