<?php

namespace App\Http\Services\Payment;

use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;

class StripeService extends BasePaymentService
{
    public  $stripClient;

    public function __construct($method, $object)
    {
        parent::__construct($method, $object);
        $this->stripClient = new StripeClient($this->gateway->secret);
    }

    public function makePayment($amount)
    {
        $this->setAmount($amount);
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;

        $payment = $this->stripClient->checkout->sessions->create([
            'success_url' => $this->callbackUrl,
            'cancel_url' => $this->callbackUrl,
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => $this->currency,
                        'product_data' => [
                            'name' => 'Amount',
                        ],
                        'unit_amount' => $this->amount * 100,
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
        ]);

        try {

            if ($payment->status == 'open') {
                $data['payment_id'] = $payment->id;
                $data['success'] = true;
                $data['redirect_url'] = $payment->url;
            }

            return $data;
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
        return $data;
    }

    public function paymentConfirmation($payment_id)
    {
        $data['data'] = null;
        $payment = $this->stripClient->checkout->sessions->retrieve($payment_id, []);
        if ($payment->payment_status == 'paid') {
            $data['success'] = true;
            $data['data']['amount'] = $payment->amount_total / 100;
            $data['data']['currency'] = $payment->currency;
            $data['data']['payment_status'] =  'success';
            $data['data']['payment_method'] = STRIPE;
        } else {
            $data['success'] = false;
            $data['data']['amount'] = $payment->amount_total / 100;
            $data['data']['currency'] = $payment->currency;
            $data['data']['payment_status'] =  'unpaid';
            $data['data']['payment_method'] = STRIPE;
        }
        return $data;
    }

    /**
     * Refund a payment through Stripe
     *
     * @param string $payment_id The checkout session ID or payment intent ID
     * @param float|null $amount Optional partial refund amount. If null, full refund
     * @param string|null $reason Optional reason for refund
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
            // Check if payment_id is a checkout session ID (starts with 'cs_')
            // or a payment intent ID (starts with 'pi_')
            $paymentIntentId = null;

            if (strpos($payment_id, 'cs_') === 0) {
                // It's a checkout session, get the payment intent
                $checkoutSession = $this->stripClient->checkout->sessions->retrieve($payment_id, [
                    'expand' => ['payment_intent']
                ]);

                if (!$checkoutSession->payment_intent) {
                    $data['message'] = 'Payment intent not found for this checkout session';
                    Log::error('Stripe refund error: Payment intent not found', [
                        'checkout_session_id' => $payment_id
                    ]);
                    return $data;
                }

                $paymentIntentId = is_string($checkoutSession->payment_intent)
                    ? $checkoutSession->payment_intent
                    : $checkoutSession->payment_intent->id;
            } elseif (strpos($payment_id, 'pi_') === 0) {
                // It's already a payment intent ID
                $paymentIntentId = $payment_id;
            } else {
                $data['message'] = 'Invalid payment ID format';
                Log::error('Stripe refund error: Invalid payment ID format', [
                    'payment_id' => $payment_id
                ]);
                return $data;
            }

            // Get charges associated with the payment intent
            $charges = \Stripe\Charge::all([
                'payment_intent' => $paymentIntentId,
                'limit' => 100,
            ], ['api_key' => $this->gateway->secret]);

            if (!$charges->data || count($charges->data) === 0) {
                $data['message'] = 'No charge found for this payment';
                Log::error('Stripe refund error: No charge found', [
                    'payment_intent_id' => $paymentIntentId
                ]);
                return $data;
            }

            // Process refund for all charges
            $refundResults = [];
            $allRefundsSuccessful = true;
            $totalRefundedAmount = 0;
            $refundIds = [];

            foreach ($charges->data as $charge) {
                $chargeId = $charge->id;

                // Skip if charge is already fully refunded
                if ($charge->refunded) {
                    Log::info('Stripe charge already refunded, skipping', [
                        'charge_id' => $chargeId
                    ]);
                    continue;
                }

                // Prepare refund parameters
                $refundParams = [
                    'charge' => $chargeId,
                ];

                // Add amount if partial refund
                if ($amount !== null && $amount > 0) {
                    $refundParams['amount'] = (int) round($amount * 100); // Convert to cents
                }

                // Add reason if provided
                if ($reason !== null) {
                    $refundParams['reason'] = $reason; // 'duplicate', 'fraudulent', or 'requested_by_customer'
                }

                try {
                    // Create the refund
                    $refund = $this->stripClient->refunds->create($refundParams);

                    if ($refund->status === 'succeeded' || $refund->status === 'pending') {
                        $refundResults[] = [
                            'charge_id' => $chargeId,
                            'refund_id' => $refund->id,
                            'amount' => $refund->amount / 100,
                            'status' => $refund->status,
                            'success' => true
                        ];
                        $totalRefundedAmount += $refund->amount / 100;
                        $refundIds[] = $refund->id;

                        Log::info('Stripe refund successful', [
                            'refund_id' => $refund->id,
                            'charge_id' => $chargeId,
                            'amount' => $refund->amount / 100,
                            'status' => $refund->status
                        ]);
                    } else {
                        $allRefundsSuccessful = false;
                        $refundResults[] = [
                            'charge_id' => $chargeId,
                            'refund_id' => $refund->id,
                            'status' => $refund->status,
                            'success' => false,
                            'error' => 'Refund failed with status: ' . $refund->status
                        ];

                        Log::error('Stripe refund failed', [
                            'refund_id' => $refund->id,
                            'charge_id' => $chargeId,
                            'status' => $refund->status
                        ]);
                    }
                } catch (\Exception $e) {
                    $allRefundsSuccessful = false;
                    $refundResults[] = [
                        'charge_id' => $chargeId,
                        'success' => false,
                        'error' => $e->getMessage()
                    ];

                    Log::error('Stripe refund exception for charge', [
                        'charge_id' => $chargeId,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Set response data based on results
            if ($allRefundsSuccessful && count($refundResults) > 0) {
                $data['success'] = true;
                $data['refund_id'] = implode(', ', $refundIds);
                $data['refund_ids'] = $refundIds;
                $data['refund_amount'] = $totalRefundedAmount;
                $data['refunds_count'] = count($refundResults);
                $data['message'] = count($refundResults) > 1
                    ? sprintf('Successfully refunded %d charges (Total: $%.2f)', count($refundResults), $totalRefundedAmount)
                    : 'Refund processed successfully';
                $data['refund_details'] = $refundResults;
            } elseif (count($refundResults) > 0) {
                $successCount = count(array_filter($refundResults, function ($r) {
                    return $r['success'];
                }));
                $data['success'] = false;
                $data['message'] = sprintf('Partial refund: %d of %d charges refunded successfully', $successCount, count($refundResults));
                $data['refund_details'] = $refundResults;
            } else {
                $data['success'] = false;
                $data['message'] = 'No charges were refunded';
            }

            return $data;
        } catch (\Stripe\Exception\InvalidRequestException $e) {
            $data['message'] = $e->getMessage();
            Log::error('Stripe refund error: Invalid request', [
                'error' => $e->getMessage(),
                'payment_id' => $payment_id
            ]);
            return $data;
        } catch (\Exception $e) {
            $data['message'] = $e->getMessage();
            Log::error('Stripe refund error', [
                'error' => $e->getMessage(),
                'payment_id' => $payment_id,
                'trace' => $e->getTraceAsString()
            ]);
            return $data;
        }
    }

    public function subscriptionCancel($subscription_id)
    {
        $this->stripClient->subscriptions->cancel($subscription_id, ['prorate' => true, 'invoice_now' => true]);
    }

    public function makePaymentSubscribe($object, $price_id)
    {
        $data['success'] = false;
        $data['redirect_url'] = '';
        $data['payment_id'] = '';
        $data['message'] = SOMETHING_WENT_WRONG;

        $authUser = auth()->user();

        // Create or update Stripe customer directly
        $customer = $this->stripClient->customers->create([
            'name' => $authUser->name,
            'email' => $authUser->email,
            'phone' => $authUser->phone,
            'address' => [
                'country' => $authUser->country ?? 'US',
                'line1' => $authUser->address ?? ''
            ],
            'metadata' => [
                'user_id' => $authUser->id,
            ]
        ]);

        $payment = $this->stripClient->checkout->sessions->create([
            'customer' => $customer->id,
            'success_url' => $object['success_url'],
            'cancel_url' => $object['cancel_url'],
            'subscription_data' => [
                'metadata' => [
                    'customer' => $customer->id,
                    'package_id' => $object['package_id'],
                    'user_id' => $object['user_id'],
                ]
            ],
            'line_items' => [
                [
                    'price' => $price_id,
                    'quantity' => 1,
                ]
            ],
            'mode' => 'subscription',
        ]);

        try {

            if ($payment->status == 'open') {
                $data['payment_id'] = $payment->payment_intent;
                $data['success'] = true;
                $data['redirect_url'] = $payment->url;
            }

            return $data;
        } catch (\Exception $ex) {
            return $data['message'] = $ex->getMessage();
        }
        return $data;
    }

    public function saveProductSaas(array $data): array
    {
        // Step 1: Process started log
        Log::info('Stripe product sync started', [
            'product_name' => $data['name'] ?? 'Unknown',
            'has_monthly'  => !empty($data['monthly_price']),
            'has_yearly'   => !empty($data['yearly_price']),
        ]);

        try {
            $response = [];
            $productId = $data['stripe_product_id'] ?? null;

            // Step 1: Create or Update Product (ONE product for all plans)
            if ($productId) {
                try {
                    // Check if product exists and update it
                    $product = $this->stripClient->products->retrieve($productId);

                    // Prepare update data
                    $updateData = [];

                    // Update product name if changed
                    if ($product->name !== $data['name']) {
                        $updateData['name'] = $data['name'];
                    }

                    // Update description if provided
                    if (isset($data['description']) && $product->description !== $data['description']) {
                        $updateData['description'] = $data['description'];
                    }

                    // Update images if provided
                    if (!empty($data['images']) && is_array($data['images'])) {
                        $newImages = array_slice($data['images'], 0, 8);
                        if ($product->images !== $newImages) {
                            $updateData['images'] = $newImages;
                        }
                    }

                    // Update metadata if provided
                    if (!empty($data['metadata']) && is_array($data['metadata'])) {
                        $updateData['metadata'] = $data['metadata'];
                    }

                    // Only update if there are changes
                    if (!empty($updateData)) {
                        $this->stripClient->products->update($productId, $updateData);

                        Log::info("Stripe product updated", [
                            'product_id' => $productId,
                            'updated_fields' => array_keys($updateData)
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning("Existing product ID not found in Stripe, creating new", [
                        'product_id' => $productId
                    ]);
                    $productId = null;
                }
            }

            // Create new product if doesn't exist
            if (!$productId) {
                $productData = [
                    'name' => $data['name'],
                    'active' => true,
                ];

                // Add description if provided
                if (!empty($data['description'])) {
                    $productData['description'] = $data['description'];
                }

                // Add images if provided (must be publicly accessible URLs)
                if (!empty($data['images']) && is_array($data['images'])) {
                    $productData['images'] = array_slice($data['images'], 0, 8); // Stripe allows max 8 images
                }

                // Add metadata for additional information
                if (!empty($data['metadata']) && is_array($data['metadata'])) {
                    $productData['metadata'] = $data['metadata'];
                }

                $product = $this->stripClient->products->create($productData);
                $productId = $product->id;
                $response['product_id'] = $productId;

                Log::info("New Stripe product created", [
                    'product_id' => $productId,
                    'product_name' => $data['name'],
                    'has_description' => !empty($data['description']),
                    'has_images' => !empty($data['images']),
                ]);
            } else {
                $response['product_id'] = $productId;
            }

            // Step 2: Configuration array (clean and simple)
            $plans = [
                'monthly' => [
                    'label'     => 'Monthly',
                    'interval'  => 'month',
                    'price_key' => 'monthly_price',
                    'id_key'    => 'monthlyPriceId'
                ],
                'yearly' => [
                    'label'     => 'Yearly',
                    'interval'  => 'year',
                    'price_key' => 'yearly_price',
                    'id_key'    => 'yearlyPriceId'
                ],
            ];

            foreach ($plans as $type => $config) {
                $price = $data[$config['price_key']] ?? 0;
                $existingPriceId = $data[$config['id_key']] ?? null;

                // Skip if no price set
                if ($price <= 0) {
                    continue;
                }

                // Money handling (convert to cents)
                $priceInCents = (int) round($price * 100);
                $shouldCreateNew = true;

                // Step 3: Check existing price
                if ($existingPriceId) {
                    try {
                        $oldPrice = $this->stripClient->prices->retrieve($existingPriceId);

                        if ($oldPrice->unit_amount === $priceInCents && $oldPrice->active) {
                            $response["{$type}_price_id"] = $existingPriceId;
                            $shouldCreateNew = false;

                            Log::info("Reusing existing Stripe price", [
                                'type'     => $type,
                                'price_id' => $existingPriceId
                            ]);
                        } else {
                            $this->stripClient->prices->update($existingPriceId, ['active' => false]);

                            Log::info("Deactivated old Stripe price due to changes", [
                                'type'         => $type,
                                'old_price_id' => $existingPriceId
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::warning("Old price ID provided but not found in Stripe", [
                            'type'     => $type,
                            'price_id' => $existingPriceId
                        ]);
                    }
                }

                // Step 4: Create new price attached to the same product
                if ($shouldCreateNew) {
                    $newPrice = $this->stripClient->prices->create([
                        'currency'     => $this->currency,
                        'unit_amount'  => $priceInCents,
                        'recurring'    => ['interval' => $config['interval']],
                        'product'      => $productId,  // Attach to same product
                        'nickname'     => $config['label'],
                    ]);

                    $response["{$type}_price_id"] = $newPrice->id;

                    Log::info("New Stripe price created", [
                        'type'         => $type,
                        'price_id'     => $newPrice->id,
                        'product_id'   => $productId,
                        'interval'     => $config['interval']
                    ]);
                }
            }

            return [
                'success' => true,
                'data'    => $response,
                'message' => 'Stripe product and plans saved successfully'
            ];
        } catch (\Throwable $ex) {
            // Step 5: Critical error logging
            Log::error('Stripe Product/Plan Save Failed', [
                'error' => $ex->getMessage(),
                'input' => $data
            ]);

            return [
                'success' => false,
                'message' => 'Stripe Error: ' . $ex->getMessage()
            ];
        }
    }
}
