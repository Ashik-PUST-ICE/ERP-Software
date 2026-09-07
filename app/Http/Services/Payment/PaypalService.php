<?php


namespace App\Http\Services\Payment;

use Illuminate\Support\Facades\Log;
use Omnipay\Omnipay;

class PaypalService extends BasePaymentService
{
    public $omniPay;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->omniPay = Omnipay::create('PayPal_Rest');
        $this->omniPay->setClientId($this->gateway->key);
        $this->omniPay->setSecret($this->gateway->secret);
        if ($this->gateway->mode == GATEWAY_MODE_SANDBOX) {
            $this->omniPay->setTestMode(true);
        } else {
            $this->omniPay->setTestMode(false);
        }
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $response = $this->omniPay->purchase(array(
            'amount' => $this->amount,
            'currency' => $this->currency,
            'returnUrl' => $this->callbackUrl,
            'cancelUrl' => $this->callbackUrl,
        ))->send();
        Log::info('<<<<<$response->getData()>>>>>');
        Log::info($response->getData());
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = __(SOMETHING_WENT_WRONG);
        try {
            if ($response->isRedirect()) {
                $data['redirect_url'] = $response->getData()['links'][1]['href'];
                $data['payment_id'] = $response->getData()['id'];
                $data['success'] = true;
            }
            Log::info(json_encode($data));
            return $data;
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
    }

    public function paymentConfirmation($payment_id, $payer_id = null)
    {

        $data['success'] = false;
        $data['data'] = null;

        if ($payment_id && $payer_id) {
            $transaction = $this->omniPay->completePurchase(array(
                'payer_id'             => $payer_id,
                'transactionReference' => $payment_id,
            ));
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $arr_body = $response->getData();
                Log::info($response->getData());
                $data['success'] = true;
                $data['data']['amount'] = $arr_body['transactions'][0]['amount']['total'];
                $data['data']['currency'] = $arr_body['transactions'][0]['amount']['currency'];
                $data['data']['payment_status'] = $arr_body['state'] == 'approved' ? 'success' : 'processing';
                $data['data']['payment_method'] = PAYPAL;
            }
        }
        return $data;
    }

    /**
     * Refund a PayPal payment
     * 
     * @param string $payment_id The PayPal payment ID
     * @param float|null $amount Optional partial refund amount. If null, full refund
     * @param string|null $reason Optional reason for refund (note for PayPal)
     * @return array
     */
    public function refundPayment($payment_id, $amount = null, $reason = null)
    {
        $data = [
            'success' => false,
            'message' => SOMETHING_WENT_WRONG,
            'refund_id' => null
        ];

        try {
            Log::info('PayPal refund initiated', [
                'payment_id' => $payment_id,
                'amount' => $amount,
                'reason' => $reason
            ]);

            // Prepare refund parameters
            $refundParams = [
                'transactionReference' => $payment_id,
            ];

            // Add amount if partial refund
            if ($amount !== null && $amount > 0) {
                $refundParams['amount'] = number_format($amount, 2, '.', '');
                $refundParams['currency'] = $this->currency;
            }

            // Add description/note if reason provided
            if ($reason !== null) {
                $refundParams['description'] = $reason;
            }

            // Create refund request
            $refundRequest = $this->omniPay->refund($refundParams);
            $response = $refundRequest->send();

            Log::info('PayPal refund response', [
                'payment_id' => $payment_id,
                'is_successful' => $response->isSuccessful(),
                'response_data' => $response->getData()
            ]);

            if ($response->isSuccessful()) {
                $responseData = $response->getData();

                $data['success'] = true;
                $data['refund_id'] = $responseData['id'] ?? $payment_id;
                $data['refund_amount'] = isset($responseData['amount']) ? $responseData['amount']['total'] : $amount;
                $data['refund_status'] = $responseData['state'] ?? 'completed';
                $data['message'] = 'PayPal refund processed successfully';

                Log::info('PayPal refund successful', [
                    'refund_id' => $data['refund_id'],
                    'payment_id' => $payment_id,
                    'amount' => $data['refund_amount'],
                    'status' => $data['refund_status']
                ]);
            } else {
                $data['message'] = 'PayPal refund failed: ' . ($response->getMessage() ?? 'Unknown error');

                Log::error('PayPal refund failed', [
                    'payment_id' => $payment_id,
                    'error' => $response->getMessage(),
                    'response_data' => $response->getData()
                ]);
            }

            return $data;
        } catch (\Exception $e) {
            $data['message'] = 'PayPal refund error: ' . $e->getMessage();

            Log::error('PayPal refund exception', [
                'payment_id' => $payment_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $data;
        }
    }

    public function saveProductSaas(array $data): array
    {
        try {
            // Log: PayPal product sync started
            Log::info('PayPal product sync started', [
                'product_name' => $data['name'] ?? 'Unknown',
                'has_monthly'  => !empty($data['monthly_price']),
                'has_yearly'   => !empty($data['yearly_price']),
            ]);

            // PayPal plan creation - using placeholder for now
            // TODO: Implement full PayPal product and billing plan creation using PayPal SDK

            $response = [];

            // Generate mock product ID if not exists
            if (empty($data['paypal_product_id'])) {
                $response['product_id'] = 'paypal_prod_' . uniqid();
            } else {
                $response['product_id'] = $data['paypal_product_id'];
            }

            // Generate mock plan IDs
            if (!empty($data['monthly_price']) && $data['monthly_price'] > 0) {
                if (empty($data['monthlyPriceId'])) {
                    $response['monthly_price_id'] = 'paypal_plan_monthly_' . uniqid();
                } else {
                    $response['monthly_price_id'] = $data['monthlyPriceId'];
                }
            }

            if (!empty($data['yearly_price']) && $data['yearly_price'] > 0) {
                if (empty($data['yearlyPriceId'])) {
                    $response['yearly_price_id'] = 'paypal_plan_yearly_' . uniqid();
                } else {
                    $response['yearly_price_id'] = $data['yearlyPriceId'];
                }
            }

            Log::info('PayPal product sync completed (mock)', [
                'product_id' => $response['product_id'] ?? 'N/A',
                'monthly_plan_id' => $response['monthly_price_id'] ?? 'N/A',
                'yearly_plan_id' => $response['yearly_price_id'] ?? 'N/A',
            ]);

            return [
                'success' => true,
                'data' => $response
            ];
        } catch (\Exception $e) {
            Log::error('PayPal product sync error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'PayPal API Error: ' . $e->getMessage()
            ];
        }
    }

    public function createPlan(array $data): array
    {
        try {
            // For PayPal, we need to create product and plan
            // This is a basic implementation - you might need PayPal SDK for full functionality

            $response = [
                'success' => false,
                'message' => 'PayPal plan creation not fully implemented yet',
                'monthly_plan_id' => null,
                'yearly_plan_id' => null
            ];

            // TODO: Implement PayPal product and plan creation using PayPal SDK or direct API

            // For now, return placeholder
            return $response;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'PayPal Error: ' . $e->getMessage(),
                'monthly_plan_id' => null,
                'yearly_plan_id' => null
            ];
        }
    }
}
