<?php


namespace App\Http\Services\Payment;

class Payment
{
    public  $provider = null;
    public function __construct($method, $object = [])
    {
        $classPath = getPaymentServiceClass($method);
        $this->provider = new $classPath($method, $object);
    }

    public function makePayment($amount)
    {
        $res = $this->provider->makePayment($amount);
        return $res;
    }

    public function makePaymentSubscribe($object, $price_id)
    {
        $res = $this->provider->makePaymentSubscribe($object, $price_id);
        return $res;
    }

    public function subscriptionCancel($subscription_id)
    {
        $res = $this->provider->subscriptionCancel($subscription_id);
        return $res;
    }

    public function paymentConfirmation($payment_id, $payer_id = null)
    {
        if (is_null($payer_id)) {
            return $this->provider->paymentConfirmation($payment_id);
        }
        return $this->provider->paymentConfirmation($payment_id, $payer_id);
    }

    public function saveProductSaas($data)
    {
        return $this->provider->saveProductSaas($data);
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
