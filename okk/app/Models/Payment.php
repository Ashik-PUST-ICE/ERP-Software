<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        
        'paymentable_id',
        'paymentable_type',
        'gateway_id',
        'paymentId',
        'tnxId',
        'user_id',
        'bank_id',
        'deposit_slip',
        'subscription_type',
        'adjusted_payment_id',
        'adjusted_amount',
        'price',
        'sub_total',
        'tax',
        'system_currency',
        'payment_currency',
        'conversion_rate',
        'grand_total_with_conversation_rate',
        'grand_total',
        'subscription_id',
        'invoice_id',
        'stripe_customer_id',
        'payment_details',
        'gateway_callback_details',
        'payment_time',
        'payment_status',
    ];

    public function paymentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class );
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class );
    }

    public function gateway(){
        return $this->belongsTo(Gateway::class);
    }
    public  function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function refundPayment($payment_id, $amount = null, $reason = null)
    {
        // Check if provider has refundPayment method
        if (method_exists($this->provider, 'refundPayment')) {
            return $this->provider->refundPayment($payment_id, $amount, $reason);
        }
        
        // Return error if gateway doesn't support refunds
        return [
            'success' => false,
            'message' => 'Refund not supported for this payment gateway',
            'refund_id' => null
        ];
    }


}