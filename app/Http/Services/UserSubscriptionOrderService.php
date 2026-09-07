<?php

namespace App\Http\Services;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserSubscriptionOrderService
{
    use ResponseTrait;

    public function getByStatus($request)
    {
        $packageClass = Package::class;
        $orders = Payment::query()
            ->leftJoin('users', 'payments.user_id', '=', 'users.id')
            ->leftJoin('gateways', 'payments.gateway_id', '=', 'gateways.id')
            ->leftJoin('banks', 'payments.bank_id', '=', 'banks.id')
            ->leftJoin('packages', function ($join) use ($packageClass) {
                $join->on(DB::raw('payments.paymentable_id'), '=', 'packages.id')
                    ->where('payments.paymentable_type', '=', $packageClass);
            })
            ->with(['bank:id,name'])
            ->select([
                'users.name as userName',
                'users.email as userEmail',
                'gateways.title as gatewayName',
                'gateways.slug as gatewaySlug',
                'banks.name as bankName',
                'packages.name as packageName',
                'payments.*'
            ]);



        if ($request->status == 'Paid') {
            $orders = $orders->where('payments.payment_status', PAYMENT_STATUS_PAID);
        } else if ($request->status == 'Pending') {
            // Only bank payments should be pending (require manual approval)
            $orders = $orders->where('payments.payment_status', PAYMENT_STATUS_PENDING)
                ->where('gateways.slug', 'bank');
        } else if ($request->status == 'Cancelled') {
            $orders = $orders->where('payments.payment_status', PAYMENT_STATUS_CANCELLED);
        } else {
            // For "All" tab, exclude pending payments from non-bank gateways
            $orders = $orders->where(function ($query) {
                $query->where('payments.payment_status', '!=', PAYMENT_STATUS_PENDING)
                      ->orWhere(function ($subQuery) {
                          $subQuery->where('payments.payment_status', PAYMENT_STATUS_PENDING)
                                   ->where('gateways.slug', 'bank');
                      });
            });
        }


        $orders = $orders->orderBy('payments.created_at', 'desc');


        return datatables($orders)
            ->addColumn('sl', function ($data) {
                static $count = 0;
                return ++$count;
            })
            ->addColumn('package', function ($order) {
                return $order->packageName ?? ($order->paymentable ? $order->paymentable->name : null) ?? 'N/A';
            })
            ->addColumn('userName', function ($order) {
                return $order->userName ?? 'N/A';
            })
            ->addColumn('userEmail', function ($order) {
                return $order->userEmail ?? 'N/A';
            })
            ->addColumn('date', function ($order) {
                return $order->created_at->format('Y-m-d h:i');
            })
            ->addColumn('amount', function ($order) {
                return showPrice($order->sub_total);
            })
            ->addColumn('tnxId', function ($order) {
                return $order->tnxId;
            })
            ->addColumn('gateway', function ($order) {
                return $order->gatewayName ?? 'N/A';
            })
            
            ->addColumn('status', function ($order) {
                if ($order->payment_status == PAYMENT_STATUS_PAID) {
                    return '<div class="status active">' . __('Paid') . '</div>';
                } elseif ($order->payment_status == PAYMENT_STATUS_PENDING) {
                    return '<div class="status pending">' . __('Pending') . '</div>';
                } else {
                    return '<div class="status failed">' . __('Cancelled') . '</div>';
                }
            })
            ->addColumn('action', function ($data) {
                // Only show edit button for bank payments
                if ($data->gatewaySlug === 'bank') {
                    return '<div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item orderPayStatus" href="javascript:void(0)" data-id="' . $data->id . '">
                                            ' . __('Change Status') . '
                                        </a>
                                    </li>
                                </ul>
                            </div>';
                }
                return '-';
            })
            ->rawColumns(['package', 'userName', 'status', 'gateway', 'action', 'payment_info'])
            ->make(true);
    }

    public function orderPaymentStatusChange($request)
    {
        DB::beginTransaction();
        try {
            $paymentOrder = Payment::findOrFail($request->id);
            $oldStatus = $paymentOrder->payment_status;
            $paymentOrder->payment_status = $request->payment_status;
            $paymentOrder->save();

            // If status changed to paid and was not paid before
            if ($request->payment_status == PAYMENT_STATUS_PAID && $oldStatus != PAYMENT_STATUS_PAID) {
                // Check if paymentable relationship exists
                if (!$paymentOrder->paymentable) {
                    DB::rollBack();
                    return $this->error([], 'Package not found for this order');
                }
                
                // Create transaction record (use payment id as reference_id since it's unsignedBigInteger)
                Transaction::create([
                    'user_id' => $paymentOrder->user_id,
                    'payment_id' => $paymentOrder->id,
                    'reference_id' => $paymentOrder->id,
                    'type' => TRANSACTION_TYPE_SUBSCRIPTION,
                    'tnxId' => $paymentOrder->tnxId,
                    'amount' => $paymentOrder->sub_total,
                    'purpose' => 'Package Subscription',
                    'payment_time' => now(),
                    'payment_method' => $paymentOrder->gateway->title ?? 'Manual',
                ]);

                // Assign package to user
                $package = $paymentOrder->paymentable;
                if ($package) {
                    // Deactivate existing active packages
                    UserPackage::where('user_id', $paymentOrder->user_id)
                        ->where('status', STATUS_ACTIVE)
                        ->where('end_date', '>=', now())
                        ->update(['status' => STATUS_REJECT]);

                    // Calculate end date based on subscription type
                    $expiredDate = $paymentOrder->subscription_type == SUBSCRIPTION_TYPE_MONTHLY
                        ? now()->addMonth()
                        : now()->addYear();

                    // Create user package
                    UserPackage::create([
                        'user_id' => $paymentOrder->user_id,
                        'packageable_id' => $package->id,
                        'packageable_type' => get_class($package),
                        'payment_id' => $paymentOrder->id,
                        'start_date' => now(),
                        'end_date' => $expiredDate,
                        'status' => STATUS_ACTIVE,
                        'subscription_type' => $paymentOrder->subscription_type,
                    ]);
                }
            }

            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->error([], $e->getMessage());
        }
    }


    public function orderGetInfo($id)
    {
        try {
            $payment = Payment::query()
                ->leftJoin('gateways', 'payments.gateway_id', '=', 'gateways.id')
                ->select(['payments.*', 'gateways.title as gatewayTitle'])
                ->where('payments.id', $id)
                ->first();
            
            if (!$payment) {
                return $this->error([], 'Order not found');
            }
            
            return $payment;
        } catch (Exception $e) {
            return $this->error([], SOMETHING_WENT_WRONG);
        }
    }
}