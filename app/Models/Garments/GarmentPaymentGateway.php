<?php

namespace App\Models\Garments;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\BelongsToTenant;

class GarmentPaymentGateway extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'garment_payment_gateways';

    protected $fillable = ['tenant_id', 'title', 'slug', 'status', 'mode', 'url', 'key', 'secret', 'image'];

    public function currencies(): HasMany
    {
        return $this->hasMany(GarmentPaymentGatewayCurrency::class, 'gateway_id');
    }
}
