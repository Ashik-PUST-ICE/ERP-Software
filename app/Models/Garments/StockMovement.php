<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'garment_stock_movements';
    protected $fillable = ['material_id', 'warehouse_id', 'movement_type', 'quantity', 'balance_after', 'reference_type', 'reference_id', 'notes'];
    protected $casts = ['quantity' => 'decimal:4', 'balance_after' => 'decimal:4'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
