<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderProfitLoss extends Model
{
    use HasFactory;

    protected $table = 'garment_order_profit_losses';
    protected $fillable = ['order_id', 'sales_revenue', 'material_cost', 'production_cost', 'salary_cost', 'overhead_cost', 'other_cost', 'total_cost', 'profit_amount', 'profit_margin', 'status', 'notes'];
    protected $casts = ['sales_revenue' => 'decimal:4', 'material_cost' => 'decimal:4', 'production_cost' => 'decimal:4', 'salary_cost' => 'decimal:4', 'overhead_cost' => 'decimal:4', 'other_cost' => 'decimal:4', 'total_cost' => 'decimal:4', 'profit_amount' => 'decimal:4', 'profit_margin' => 'decimal:4'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(GarmentOrder::class, 'order_id');
    }
}
