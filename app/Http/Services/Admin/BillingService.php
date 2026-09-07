<?php

namespace App\Http\Services\Admin;

use App\Http\Services\Payment\Payment;
use App\Models\Transaction;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingService
{
    use ResponseTrait;

    public function cancel()
    {
        try {
            DB::beginTransaction();

            $userPackage = UserPackage::where('status', STATUS_ACTIVE)
                ->where('end_date', '>=', now())
                ->where('user_id', auth()->id())
                ->first();

            if (!$userPackage) {
                DB::rollBack();
                return $this->error([], __('No active subscription found.'));
            }

            $isTrail = $userPackage->is_trail;

            $userPackage->update(['status' => STATUS_CANCELLED]);

            if ($isTrail != STATUS_ACTIVE && $userPackage->payment && $userPackage->payment->subscription_id) {
                Log::channel('stripe_payment_log')->info('--------***Package cancel from subscription change START***------');
                $payment = new Payment('stripe', [
                    'currency' => 'USD',
                ]);
                $subscriptionCancel = $payment->subscriptionCancel($userPackage->payment->subscription_id);
                Log::channel('stripe_payment_log')->info($subscriptionCancel);
                Log::channel('stripe_payment_log')->info('--------***Package cancel from subscription change END***------');
            }

            DB::commit();

            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('stripe_payment_log')->info($e->getMessage() . ' File: ' . $e->getFile() . ' ' . $e->getLine());
            Log::channel('stripe_payment_log')->info('--------***Package cancel from subscription change END***------');
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function getUserPackage($userId)
    {
        $history = UserPackage::where(['user_id' => $userId])
            ->with('packageable')
            ->orderBy('id', 'desc');

        return datatables($history)
            ->addColumn('plan_name', function ($data) {
                return $data->packageable ? $data->packageable->name : '-';
            })
            ->addColumn('subscription_type', function ($data) {
                return $data->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? __('Monthly') : __('Yearly');
            })
            ->addColumn('start_date', function ($data) {
                return $data->start_date;
            })
            ->addColumn('end_date', function ($data) {
                return $data->end_date;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE && $data->end_date >= now()) {
                    return '<span class="status active">' . __('Active') . '</span>';
                } elseif ($data->status == STATUS_CANCELLED) {
                    return '<span class="status cancelled">' . __('Cancelled') . '</span>';
                } else {
                    return '<span class="status inactive">' . __('Expired') . '</span>';
                }
            })
            ->rawColumns(['status'])
            ->make(true);
    }

    public function getUserTransactionHistory($userId)
    {
        $history = Transaction::where(['user_id' => $userId])
            ->with('payment.gateway')
            ->orderBy('id', 'desc');

        return datatables($history)
            ->addColumn('tnxId', function ($data) {
                return $data->tnxId;
            })
            ->editColumn('amount', function ($data) {
                return showPrice($data->amount);
            })
            ->addColumn('purpose', function ($data) {
                return $data->purpose;
            })
            ->addColumn('payment_time', function ($data) {
                return $data->created_at;
            })
            ->addColumn('payment_method', function ($data) {
                return $data->payment && $data->payment->gateway ? $data->payment->gateway->title : ($data->payment_method ?? 'N/A');
            })
            ->rawColumns(['status'])
            ->make(true);
    }
}
