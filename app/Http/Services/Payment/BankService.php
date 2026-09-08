<?php

namespace App\Http\Services\Payment;

use App\Models\Gateway;
use App\Models\GatewayCurrency;

class BankService extends BasePaymentService
{
    public function __construct($method, $object)
    {
        $this->paymentMethod = $method;
        $this->gateway = $object['gateway'] ?? Gateway::where('slug', $method)->first();
        if (isset($object['callback_url'])) {
            $this->callbackUrl = $object['callback_url'];
        }
        $this->currency = $object['currency'] ?? 'USD';
        if ($this->gateway) {
            $this->gatewayCurrency = $object['gateway_currency'] ?? GatewayCurrency::where([
                'gateway_id' => $this->gateway->id,
                'currency' => $this->currency
            ])->first();
            if (!$this->gatewayCurrency) {
                $this->gatewayCurrency = GatewayCurrency::where('gateway_id', $this->gateway->id)->first();
            }
            if ($this->gatewayCurrency) {
                $this->currency = $this->gatewayCurrency->currency ?? $this->currency;
            }
        }
    }

    public function setAmount($amount)
    {
        $this->amount = $this->gatewayCurrency
            ? $this->numberParser($this->gatewayCurrency->conversion_rate) * $this->numberParser($amount)
            : $this->numberParser($amount);
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $successUrl = route('admin.pricing.checkout.success', [
            'success' => true,
            'message' => __('Payment submitted successfully. Awaiting admin approval.')
        ]);
        return [
            'success' => true,
            'redirect_url' => $successUrl,
            'payment_id' => null,
            'message' => __('Payment submitted. Awaiting admin approval.')
        ];
    }

    public function paymentConfirmation($payment_id)
    {
        return [
            'success' => true,
            'data' => [
                'payment_status' => 'pending',
                'payment_method' => 'bank'
            ]
        ];
    }

    public function makePaymentSubscribe($object, $price_id)
    {
        return $this->makePayment($object['amount'] ?? 0);
    }
}
