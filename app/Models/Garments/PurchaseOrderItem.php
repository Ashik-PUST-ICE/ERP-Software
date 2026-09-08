<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $table = 'garment_purchase_order_items';
    protected $fillable = ['purchase_order_id', 'material_id', 'quantity', 'unit_rate', 'line_total'];
    protected $casts = ['quantity' => 'decimal:4', 'unit_rate' => 'decimal:4', 'line_total' => 'decimal:2'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
