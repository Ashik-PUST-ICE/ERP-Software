<?php

namespace App\Http\Services;

use App\Models\Coupon;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class CouponService
{
    use ResponseTrait;

    public function getAllData()
    {
        $coupons = Coupon::orderBy('id', 'DESC')->select('id', 'name', 'code', 'discount', 'discount_type', 'start_date', 'end_date', 'status');
        return datatables($coupons)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {
                $return = __('Inactive');
                if ($data->status == STATUS_ACTIVE) {
                    $return = __('Active');
                }

                return $return;
            })
            ->editColumn('discount_type', function ($data) {
                $return = __('Percentage');
                if ($data->discount_type == 'fixed') {
                    $return = __('Fixed');
                }

                return $return;
            })
            ->addColumn('action', function ($data) {
                if (auth()->user()->role == USER_ROLE_SUPER_ADMIN) {
                    $role = 'super_admin';
                } else {
                    $role = 'admin';
                }
                return '<ul class="d-flex align-items-center cg-5 justify-content-center">
                <li class="align-items-center d-flex gap-2">
                    <button onclick="getEditModal(\'' . route($role . '.coupons.edit', $data->id) . '\', \'#edit-modal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" data-bs-toggle="modal" data-bs-target="#alumniPhoneNo">
                        <img src="' . asset('assets/images/icon/edit.svg') . '" alt="edit" />
                    </button>
                    <button onclick="deleteItem(\'' . route($role . '.coupons.delete', $data->id) . '\', \'commonDataTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-ededed bg-white" title="' . __('Delete') . '">
                        <img src="' . asset('assets/images/icon/delete-1.svg') . '" alt="delete">
                    </button>
                </li>
            </ul>';
            })
            ->rawColumns(['action', 'status', 'discount_type'])
            ->make(true);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $coupon = new Coupon();

            $coupon->name = $request->name;
            $coupon->code = $request->code;
            $coupon->discount_type = $request->discount_type;
            $coupon->amount = $request->amount;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->minimum_spend = $request->minimum_spend;
            $coupon->usage_limit_per_coupon = $request->usage_limit_per_coupon;
            $coupon->usage_limit_per_customer = $request->usage_limit_per_customer;
            $coupon->status = $request->status;
            $coupon->save();

            DB::commit();
            $message = getMessage(CREATED_SUCCESSFULLY);
            return $this->success(['route' => route('super_admin.coupons.index', [$coupon->id])], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $coupon = Coupon::findOrFail($id);

            $coupon->name = $request->name;
            $coupon->code = $request->code;
            $coupon->discount_type = $request->discount_type;
            $coupon->amount = $request->amount;
            $coupon->start_date = $request->start_date;
            $coupon->end_date = $request->end_date;
            $coupon->minimum_spend = $request->minimum_spend;
            $coupon->usage_limit_per_coupon = $request->usage_limit_per_coupon;
            $coupon->usage_limit_per_customer = $request->usage_limit_per_customer;
            $coupon->status = $request->status;
            $coupon->save();

            DB::commit();
            $message = getMessage(UPDATED_SUCCESSFULLY);
            return $this->success(['route' => route('super_admin.coupons.index', [$coupon->id])], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function getById($id)
    {
        return Coupon::findOrFail($id);
    }

    public function deleteById($id)
    {
        DB::beginTransaction();
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }
}
