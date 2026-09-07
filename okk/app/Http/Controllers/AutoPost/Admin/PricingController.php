<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\SubscriptionOrderRequest;
use App\Http\Services\EmailSendService;
use App\Http\Services\GatewayService;
use App\Http\Services\Logger;
use App\Http\Services\PackageService;
use App\Http\Services\PaddleService;
use App\Http\Services\Payment\Payment;
use App\Http\Services\SubscriptionService;
use App\Models\Bank;
use App\Models\Currency;
use App\Models\FileManager;
use App\Models\Gateway;
use App\Models\GatewayCurrency;
use App\Models\Package;
use App\Models\Payment as ModelPayment;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PricingController extends Controller
{
    use ResponseTrait;

    public $packageService;
    public $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService;
        $this->packageService = new PackageService;
    }

    public function index()
    {
        $userId = auth()->id();
        $data = [
            'activePricing' => 'active',
            'showBillingMenu' => 'true',
            'title' => __('Pricing'),
            'currentPackage' => $this->subscriptionService->getCurrentPlan($userId),
            'packages' => $this->packageService->getActiveAll(),
        ];

        return view('auto_posts.admin.pricing.index', $data);
    }

    public function checkout(Request $request)
    {
        $data = [
            'activePricing' => 'active',
            'title' => __('Checkout'),
        ];

        if (!is_null($request->slug) && !is_null($request->type)) {
            $package = Package::where('slug', $request->slug)->first();
            $currentPackage = $this->subscriptionService->getCurrentPlan();
            if (is_null($package)) {
                return redirect(route('admin.pricing.index'))->with(['error' => __('No Package Found')]);
            }
            $data['package'] = $package;
            $data['price'] = $request->type == SUBSCRIPTION_TYPE_MONTHLY ? $package->monthly_price : $package->yearly_price;
            $data['adjustedAmount'] = 0; // Simplified - no adjustment calculation
            $data['adjustedAmountPaymentId'] = $currentPackage?->payment_id;
            $data['subTotal'] = $data['price'] - $data['adjustedAmount'];
            $data['type'] = $request->type;
            $data['slug'] = $package->slug;
            $data['id'] = $package->id;

            // Allow switching between monthly and yearly for the same package
            if (!is_null($currentPackage) && $currentPackage->package_id == $package->id && $currentPackage->subscription_type == $request->type) {
                return redirect()->route('admin.pricing.index')->with(['error' => __('Sorry, You are already in this plan with the same billing cycle')]);
            }

            if ($data['subTotal'] < 1 || $package->is_trail == STATUS_ACTIVE) {
                if (!is_null($currentPackage)) {
                    $currentPackage->update(['status' => STATUS_REJECT]);
                }
                $package->userPackage()->create([
                    'user_id' => auth()->id(),
                    'package_id' => $package->id,
                    'start_date' => now(),
                    'end_date' => now()->addDays(env('TRIAL_DAYS', 10)),
                    'subscription_type' => 1,
                    'status' => STATUS_ACTIVE,
                ]);

                return back()->with(['success' => __('Plan subscribed successfully')]);
            }
            $data['gateways'] = Gateway::where('status', STATUS_ACTIVE)->get();
            return view('auto_posts.admin.pricing.checkout', $data);
        } else {
            return redirect(route('admin.pricing.index'))->with(['error' => __('Data Not Found')]);
        }
    }

    public function pay(SubscriptionOrderRequest $request)
    {
        $userId = auth()->id();
        $gateway = Gateway::where(['id' => $request->gateway, 'status' => STATUS_ACTIVE])->first();
        if (is_null($gateway)) {
            return back()->with(['error' => __('Gateway Not Found')]);
        }

        $bankId = null;
        $depositSlipId = null;

        if ($gateway->slug === 'bank') {
            $request->validate([
                'bank_id' => 'required|exists:banks,id',
                'deposit_slip' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
            ], [
                'bank_id.required' => __('Please select a bank'),
                'bank_id.exists' => __('Invalid bank selected'),
                'deposit_slip.required' => __('Please upload the deposit slip'),
                'deposit_slip.file' => __('Deposit slip must be a file'),
                'deposit_slip.mimes' => __('Deposit slip must be image (jpeg, jpg, png) or PDF'),
            ]);

            $bankId = (int) $request->bank_id;
            $bank = Bank::where('id', $bankId)->where('status', STATUS_ACTIVE)->first();
            if (!$bank) {
                return back()->with(['error' => __('Invalid bank selected')]);
            }

            $fileManager = new FileManager();
            $uploaded = $fileManager->upload('deposit-slip', $request->file('deposit_slip'));
            if (!$uploaded) {
                return back()->with(['error' => __('Failed to upload deposit slip')]);
            }
            $depositSlipId = $uploaded->id;
        }

        $currencyId = $request->currency ?? 2; // Default to USD if not provided
        $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'id' => $currencyId])->first();
        if (is_null($gatewayCurrency)) {
            // Fallback to first available currency for this gateway
            $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id])->first();
            if (is_null($gatewayCurrency)) {
                return back()->with(['error' => __('Gateway Currency Not Found')]);
            }
        }
        $currentPackage = $this->subscriptionService->getCurrentPlan($userId);

        $object = Package::where('id', $request->id)->first();
        if (!isset($object) || is_null($object)) {
            return back()->with(['error' => __('Payment data not found')]);
        }

        $price = $request->type == SUBSCRIPTION_TYPE_MONTHLY ? $object->monthly_price : $object->yearly_price;
        $adjustedAmount = 0; // Simplified - no adjustment calculation
        $adjustedAmountPaymentId = $currentPackage?->payment_id;
        $subTotal = $price - $adjustedAmount;
        $subscriptionType = $request->type;
        $gateway_id = $gateway->id;
        $currency = $gatewayCurrency->currency;
        $gatewayConversionRate = $subTotal * $gatewayCurrency->conversion_rate;

        $order = $this->placeOrder($object, $price, $adjustedAmount, $adjustedAmountPaymentId, $subTotal, $subscriptionType, $gateway_id, $currency, $gatewayConversionRate, null, null, $bankId, $depositSlipId);

        $object = [
            'id' => $order->id,
            'callback_url' => route('admin.pricing.payment.verify'),
            'currency' => $currency,
        ];

        $payment = new Payment($gateway->slug, $object);
        $responseData = $payment->makePayment($order->grand_total);
        if ($responseData['success']) {
            $order->paymentId = $responseData['payment_id'];
            $order->save();
            return redirect($responseData['redirect_url']);
        } else {
            return redirect()->back()->with('error', $responseData['message']);
        }
    }

    public function placeOrder($object, $price, $adjustedAmount, $adjustedPaymentId, $subTotal, $subscriptionType, $gateway_id = NULL, $gatewayCurrency = NULL, $gatewayConversionRate = 0.00, $auth = null, $trax_id = null, $bankId = null, $depositSlipId = null)
    {
        $data = [
            'user_id' => $auth ?? auth()->id(),
            'tnxId' => $trax_id ?? uniqid(),
            'system_currency' => Currency::where('current_currency', 'on')->first()->currency_code,
            'gateway_id' => $gateway_id,
            'payment_currency' => $gatewayCurrency,
            'conversion_rate' => $gatewayConversionRate,
            'price' => $price,
            'adjusted_amount' => $adjustedAmount,
            'adjusted_payment_id' => $adjustedPaymentId,
            'sub_total' => $subTotal,
            'grand_total' => $subTotal,
            'subscription_type' => $subscriptionType,
            'grand_total_with_conversation_rate' => $subTotal * $gatewayConversionRate,
            'payment_details' => json_encode($object),
            'payment_status' => STATUS_PENDING
        ];
        if ($bankId) {
            $data['bank_id'] = $bankId;
        }
        if ($depositSlipId) {
            $data['deposit_slip'] = $depositSlipId;
        }
        return $object->payments()->create($data);
    }

    public function stripePay(Request $request)
    {
        $gatewayId = 2; // Default Stripe gateway
        $currencyId = 2; // Default currency
        $gateway = Gateway::where(['id' => $gatewayId, 'status' => STATUS_ACTIVE])->first();
        if (is_null($gateway)) {
            return back()->with(['error' => __('Gateway Not Found')]);
        }
        $gatewayCurrency = GatewayCurrency::where(['gateway_id' => $gateway->id, 'id' => $currencyId])->first();
        if (is_null($gatewayCurrency)) {
            return back()->with(['error' => __('Gateway Currency Not Found')]);
        }

        $package = Package::where('id', $request->id)->first();
        $plan_id = $request->type == SUBSCRIPTION_TYPE_MONTHLY ? $package->stripe_monthly_plan_id : $package->stripe_yearly_plan_id;
        $currency = $gatewayCurrency->currency;

        if (!isset($package) || is_null($package)) {
            return back()->with(['error' => __('Payment data not found')]);
        }

        $object = [
            'id' => NULL,
            'callback_url' => route('admin.pricing.payment.stripe_success'),
            'currency' => $currency,
        ];

        $subscriptionMeta = [
            'user_id' => auth()->id(),
            'package_id' => $package->id,
            'success_url' => route('admin.pricing.payment.stripe_success'),
            'currency' => $currency,
            'cancel_url' => route('admin.pricing.checkout.success', ['success' => false, 'message' => __('Picked the wrong subscription? Shop around then come back to pay!')])
        ];

        Log::channel('stripe_payment_log')->info('--------***Subscription checkout session START***------');
        $payment = new Payment($gateway->slug, $object);
        $responseData = $payment->makePaymentSubscribe($subscriptionMeta, $plan_id);
        Log::channel('stripe_payment_log')->info($responseData);
        if ($responseData['success']) {
            Log::channel('stripe_payment_log')->info('--------***Subscription checkout session success --- END***------');
            return redirect($responseData['redirect_url']);
        } else {
            Log::channel('stripe_payment_log')->info('--------***Subscription checkout session failed --- END***------');
            return redirect()->back()->with('error', $responseData['message']);
        }
    }

    public function getCurrencyByGateway(Request $request)
    {
        $gateWayService = new GatewayService;
        return $gateWayService->getCurrenciesByGatewayId($request->id);
    }

    public function checkoutSuccess(Request $request)
    {
        $data['title'] = __('Payment Verify');
        $data['success'] = $request->success;
        $data['message'] = $request->message;
        return view('auto_posts.admin.checkout-success', $data);
    }


    public function verify(Request $request)
    {
        Log::info('Payment verify called', $request->all());

        $order_id = $request->get('id', '');
        $payerId = $request->get('PayerID', NULL);
        $payment_id = $request->get('payment_id', NULL);

        Log::info('Order ID: ' . $order_id);

        $order = ModelPayment::find($order_id);
        if (is_null($order)) {
            Log::error('Order not found for ID: ' . $order_id);
            return redirect()->route('admin.pricing.checkout.success', ['success' => false, 'message' => __('Your order is not exist!')]);
        }

        Log::info('Order found', ['order_id' => $order->id, 'status' => $order->payment_status]);

        if ($order->payment_status == STATUS_ACTIVE) {
            Log::info('Order already active');
            return redirect()->route('admin.pricing.checkout.success', ['success' => false, 'message' => __('Your order is not exist!')]);
        }

        $gateway = Gateway::find($order->gateway_id);
        Log::info('Gateway', ['gateway_id' => $gateway->id, 'slug' => $gateway->slug]);

        DB::beginTransaction();
        try {
            if ($order->gateway_id == $gateway->id && $gateway->slug == MERCADOPAGO) {
                $order->paymentId = $payment_id;
                $order->save();
            }

            $payment_id = $order->paymentId;
            Log::info('Payment ID: ' . $payment_id);

            $gatewayBasePayment = new Payment($gateway->slug, ['currency' => $order->payment_currency]);
            $payment_data = $gatewayBasePayment->paymentConfirmation($payment_id, $payerId);
            Log::info('Payment confirmation result', $payment_data);

            if ($payment_data['success']) {
                if ($payment_data['data']['payment_status'] == 'success') {
                    Log::info('Payment successful, updating order');
                    $order->payment_status = STATUS_ACTIVE;
                    $order->payment_time = now();
                    $order->gateway_callback_details = json_encode($request->all());
                    $order->save();

                    $userPackage = $this->subscriptionService->getCurrentPlan($order->user_id);
                    if (!is_null($userPackage)) {
                        $userPackage->update(['status' => STATUS_REJECT]);
                    }

                    $expiredDate = $order->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? now()->addMonth() : now()->addYear();

                    $reference = $order->paymentable->userPackage()->create([
                        'user_id' => $order->user_id,
                        'package_id' => $order->paymentable_id,
                        'payment_id' => $order->id,
                        'start_date' => now(),
                        'end_date' => $expiredDate,
                        'subscription_type' => $order->subscription_type,
                        'status' => STATUS_ACTIVE,
                    ]);

                    $purpose = __('Subscription payment for ') . $order->paymentable->name;
                    $type = TRANSACTION_TYPE_SUBSCRIPTION;

                    //Create Transaction
                    $order->transaction()->create([
                        'user_id' => $order->user_id,
                        'reference_id' => $reference->id,
                        'type' => $type,
                        'tnxId' => $order->tnxId,
                        'amount' => $order->grand_total,
                        'purpose' => $purpose,
                        'payment_time' => $order->payment_time,
                        'payment_method' => $gateway->title
                    ]);

                    DB::commit();
                    Log::info('Payment verification successful');

                    return redirect()->route('admin.pricing.checkout.success', ['success' => true, 'message' => __('Your payment has been successful!')]);
                } else {
                    Log::error('Payment status not success: ' . $payment_data['data']['payment_status']);
                }
            } else {
                Log::error('Payment confirmation failed', $payment_data);
                DB::rollBack();
                return redirect()->route('admin.pricing.checkout.success', ['success' => false, 'message' => __('Your payment has been failed')]);
            }
        } catch (\Exception $e) {
            Log::error('Exception in payment verify: ' . $e->getMessage());
            DB::rollBack();
            return redirect()->route('admin.pricing.checkout.success', ['success' => false, 'message' => __('Your payment has been failed')]);
        }
    }



    protected function isValidSignature(string $payload, string $signature): bool
    {
        $hash = hash_hmac('sha256', $payload, env('LEMON_SQUEEZY_SIGNING_SECRET'));

        return hash_equals($hash, $signature);
    }
}