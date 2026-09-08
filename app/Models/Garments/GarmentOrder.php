<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GarmentOrder extends Model
{
    use HasFactory;

    protected $table = 'garment_orders';

    protected $fillable = [
        'buyer_id',
        'style_id',
        'order_number',
        'product_description',
        'quantity',
        'unit_price',
        'order_date',
        'delivery_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:4',
        'order_date' => 'date',
        'delivery_date' => 'date',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function style(): BelongsTo
    {
        return $this->belongsTo(Style::class);
    }

    public function costing(): HasOne
    {
        return $this->hasOne(Costing::class, 'order_id');
    }

    public function tnaTasks(): HasMany
    {
        return $this->hasMany(TnaTask::class, 'order_id');
    }

    public function productionPlans(): HasMany
    {
        return $this->hasMany(ProductionPlan::class, 'order_id');
    }
}
