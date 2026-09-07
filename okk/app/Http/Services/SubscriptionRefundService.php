<?php

namespace App\Http\Services;

use App\Models\SubscriptionRefund;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use App\Http\Services\SubscriptionService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscriptionRefundService
{
    use ResponseTrait;

    public function refundRequestButton()
    {
        return SubscriptionRefund::where('user_id', auth()->id())->latest('id')->first();
    }

    public function refundSubscriptionRequest($request)
    {
        $userCurrentPackage = (new SubscriptionService())->getCurrentPlan();
        if (!$userCurrentPackage) {
            return $this->error([], __('You do not have an active package.'));
        }
        $getId = UserPackage::leftJoin('payments', 'user_packages.payment_id', '=', 'payments.id')
            ->where('user_packages.id', $userCurrentPackage->id)
            ->select('payments.tnxId as transaction_id', 'payments.sub_total as buy_amount', 'user_packages.payment_id as payment_id', 'user_packages.id as user_package_id')
            ->first();
        DB::beginTransaction();
        try {
            $subscriptionRefund = new SubscriptionRefund();
            $subscriptionRefund->user_id = auth()->id();
            $subscriptionRefund->user_package_id = $getId->user_package_id;
            $subscriptionRefund->transaction_id = $getId->transaction_id;
            $subscriptionRefund->payment_id = $getId->payment_id;
            $subscriptionRefund->buy_amount = $getId->buy_amount;
            $subscriptionRefund->reasons = $request->reasons;
            $subscriptionRefund->status = 0; // Explicitly set to 0 (STATUS_PENDING)
            $subscriptionRefund->tenant_id = auth()->user()->tenant_id;

            $subscriptionRefund->save();

            DB::commit();
            return $this->success([], 'Your request submitted. Waiting for admin approval');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], SOMETHING_WENT_WRONG);
        }
    }

    public function refundRequestList()
    {
        $refundRequest = SubscriptionRefund::leftJoin('users', 'subscription_refunds.user_id', '=', 'users.id')
            ->leftJoin('payments', 'payments.id', '=', 'subscription_refunds.payment_id')
            ->leftJoin('user_packages', 'user_packages.id', '=', 'subscription_refunds.user_package_id')
            ->with(['user_package:id,packageable_id,packageable_type,subscription_type', 'user_package.packageable:id,name'])
            ->select(
                'users.name as customer_name',
                'users.id as customer_id',
                'users.email as customer_email',
                'subscription_refunds.id as refund_id',
                'subscription_refunds.reasons as refunds_reasons',
                'subscription_refunds.created_at as request_time',
                'subscription_refunds.status as status',
                'user_packages.subscription_type',
                'user_packages.subscription_id',
                'subscription_refunds.user_package_id',
                'subscription_refunds.transaction_id',
                'subscription_refunds.buy_amount'
            );

        return datatables($refundRequest)
            ->addIndexColumn()
            ->editColumn('customer_name', function ($data) {
                return $data->customer_name;
            })
            ->addColumn('package_name', function ($data) {
                return $data->user_package?->packageable?->name;
            })
            ->addColumn('subscription_type', function ($data) {
                if (isset($data->subscription_type)) {
                    return $data->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? __('Monthly') : __('Yearly');
                }
                // Fallback to check user_package relation
                if (isset($data->user_package->subscription_type)) {
                    return $data->user_package->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? __('Monthly') : __('Yearly');
                }
                return 'N/A';
            })
            ->editColumn('request_time', function ($data) {
                if ($data->request_time) {
                    return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->request_time)->format('jS F, h:i:s A');
                }
                return $data->request_time;
            })
            ->editColumn('refunds_reasons', function ($data) {
                return $data->refunds_reasons;
            })
            ->editColumn('status', function ($data) {
                $status = (int)$data->status;
                
                if ($status === 1) {
                    return '<span class="status active">' . __("Paid") . '</span>';
                } elseif ($status === 3) {
                    return '<span class="status failed">' . __("Reject") . '</span>';
                } else {
                    return '<span class="status pending">' . __("Pending") . '</span>';
                }
            })
             ->addColumn('action', function ($data) {
                if ($data->status == STATUS_PENDING)
                    return '<div class="inline-flex">
                                <div class="dropdown options-area">
                                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('super_admin.subscription-refund.edit-status-model', $data->refund_id) . '\', \'#edit-modal\')">
                                                ' . __('Edit') . '
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>';
                 else {
                    return '<div class="inline-flex">
                                <div class="dropdown options-area">
                                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('super_admin.subscription-refund.edit-status-model', $data->refund_id) . '\', \'#edit-modal\')">
                                                ' . __('View') . '
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function getById($id)
    {
        $subscription = SubscriptionRefund::with('user')->find($id);
        return $subscription;
    }

    public function getStatusChange($request, $id)
    {
        DB::beginTransaction();
        try {
            $refundSubscription = SubscriptionRefund::findorfail($id);
            Log::channel('stripe_payment_log')->info('refund subscription');
            Log::channel('stripe_payment_log')->info(json_encode($refundSubscription));
            $refundSubscription->refund_amount = $request->refund_amount;
            $refundSubscription->admin_feedback = $request->admin_feedback;
            $refundSubscription->transaction_id = $request->transaction_id;
            if ($request->status == STATUS_ACTIVE) {
                $userPackage = UserPackage::find($refundSubscription->user_package_id);
                $userPackage->update(['status' => STATUS_CANCELLED]);
                $refundSubscription->status = $request->status;
            } else {
                $refundSubscription->status = $request->status;
            }
            $refundSubscription->save();

            DB::commit();

            $message = getMessage(UPDATED_SUCCESSFULLY);
            Log::channel('stripe_payment_log')->info('--------***Refund END with success ***------');
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('stripe_payment_log')->info($e->getMessage() . ' Line: ' . $e->getLine() . ' File: ' . $e->getFile());
            Log::channel('stripe_payment_log')->info('--------***Package cancel from subscription change END***------');
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}