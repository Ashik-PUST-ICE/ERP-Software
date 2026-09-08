<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;

class WarehouseTransfer extends Model
{
    protected $table = 'garment_warehouse_transfers';
    protected $fillable = ['material_id', 'from_warehouse_id', 'to_warehouse_id', 'quantity', 'transfer_date', 'status', 'notes'];
    protected $casts = ['quantity' => 'decimal:4', 'transfer_date' => 'date'];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
