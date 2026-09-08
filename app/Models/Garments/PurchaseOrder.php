<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'garment_purchase_orders';
    protected $fillable = ['supplier_id', 'po_number', 'order_date', 'expected_date', 'total_amount', 'status', 'notes'];
    protected $casts = ['order_date' => 'date', 'expected_date' => 'date', 'total_amount' => 'decimal:2'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }
}
