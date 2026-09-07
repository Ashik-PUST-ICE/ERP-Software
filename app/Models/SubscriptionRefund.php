<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionRefund extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id',
        'user_package_id',
        'payment_id',
        'transaction_id',
        'transaction_hash',
        'refund_amount',
        'buy_amount',
        'reasons',
        'admin_feedback',
        'status',
        'tenant_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_package()
    {
        return $this->belongsTo(UserPackage::class);
    }
}