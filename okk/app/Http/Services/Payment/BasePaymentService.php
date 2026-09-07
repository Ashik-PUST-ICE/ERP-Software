<?php


namespace App\Http\Services\Payment;

use App\Models\Gateway;
use App\Models\GatewayCurrency;

class BasePaymentService
{
    public $paymentMethod;
    public $callbackUrl;
    public $currency;
    public $gateway;
    public $gatewayCurrency;
    public $amount;
    public $type;

    public function __construct($method, $object)
    {
        $params = ['id', 'url'];
        $paramString = '';

        foreach ($params as $param) {
            if (isset($object[$param])) {
                $paramString .= '&' . $param . '=' . $object[$param];
            }
        }

        if (isset($object['callback_url'])) {
            $this->callbackUrl = $object['callback_url'] . '?' . ltrim($paramString, '&');
        }

        if (isset($object['currency'])) {
            $this->currency = $object['currency'];
        }
        if (isset($object['type'])) {
            $this->type = $object['type'];
        }




        $this->paymentMethod = $method;
        $this->gateway = Gateway::where('slug', $this->paymentMethod)->first();
        $this->gatewayCurrency = GatewayCurrency::where(['gateway_id' => $this->gateway->id, 'currency' => $this->currency])->firstOrFail();
    }

    public function calculateAmount($amount)
    {
        return $this->numberParser($this->gatewayCurrency->conversion_rate) * $this->numberParser($amount);
    }

    public function setAmount($amount)
    {
        $this->amount = $this->calculateAmount($amount);
    }

    function numberParser($value)
    {
        return (float) str_replace(',', '', number_format(($value), 2));
    }
}
