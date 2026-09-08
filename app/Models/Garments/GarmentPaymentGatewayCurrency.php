<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;

class GarmentPaymentGatewayCurrency extends Model
{
    use BelongsToTenant;

    protected $table = 'garment_payment_gateway_currencies';

    protected $fillable = ['tenant_id', 'gateway_id', 'currency', 'conversion_rate'];

    protected $casts = ['conversion_rate' => 'decimal:6'];

    public function gateway(): BelongsTo
    {
        return $this->belongsTo(GarmentPaymentGateway::class, 'gateway_id');
    }
}
