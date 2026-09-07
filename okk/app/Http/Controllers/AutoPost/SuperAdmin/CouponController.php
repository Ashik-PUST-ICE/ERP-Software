<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\CouponRequest;
use App\Http\Services\CouponService;
use App\Traits\ResponseTrait;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CouponController extends Controller
{
    use ResponseTrait;

    private $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['title'] = __('Coupons');
        $data['breadcrumb'] = __('Billing Center') . ' / ' . __('Coupons');
        $data['activeCoupons'] = 'active';
        $data['showCoupon'] = 'show';
        return view('auto_posts.super_admin.coupons.index', $data);
    }

    /**
     * Get coupons data for DataTable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $coupons = Coupon::orderBy('id', 'DESC');

        return datatables($coupons)
            ->addColumn('sl', function ($data) {
                static $count = 0;
                return ++$count;
            })
            ->addColumn('code', function ($data) {
                return '<code>' . $data->code . '</code>';
            })
            ->addColumn('type', function ($data) {
                return '<span class="badge package-badge">' . ucfirst($data->discount_type) . '</span>';
            })
            ->addColumn('amount', function ($data) {
                if ($data->discount_type === 'fixed') {
                    return '$' . number_format($data->amount, 2);
                } else {
                    return $data->amount . '%';
                }
            })
            ->addColumn('start_date', function ($data) {
                return $data->start_date->format('M d, Y');
            })
            ->addColumn('end_date', function ($data) {
                return $data->end_date->format('M d, Y');
            })
            ->addColumn('usage_limit', function ($data) {
                return $data->usage_limit_per_coupon ? $data->usage_limit_per_coupon : __('Unlimited');
            })
            ->addColumn('used', function ($data) {
                return $data->used_count;
            })
            ->editColumn('status', function ($data) {
                if ($data->status) {
                    return '<span class="status active">' . __('Active') . '</span>';
                } else {
                    return '<span class="status failed">' . __('Inactive') . '</span>';
                }
            })
            ->addColumn('action', function ($data) {
                $couponJson = htmlspecialchars(json_encode([
                    'id' => $data->id,
                    'name' => $data->name,
                    'code' => $data->code,
                    'discount_type' => $data->discount_type,
                    'amount' => $data->amount,
                    'start_date' => $data->start_date->format('Y-m-d'),
                    'end_date' => $data->end_date->format('Y-m-d'),
                    'minimum_spend' => $data->minimum_spend,
                    'usage_limit_per_coupon' => $data->usage_limit_per_coupon,
                    'usage_limit_per_customer' => $data->usage_limit_per_customer,
                    'status' => $data->status,
                ]), ENT_QUOTES, 'UTF-8');
                
                return '<div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="openCouponModal(' . $data->id . ', ' . $couponJson . ')">
                                        ' . __('Edit') . '
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('super_admin.coupons.destroy', $data->id) . '\', \'couponsDataTable\')">
                                        ' . __('Delete') . '
                                    </a>
                                </li>
                            </ul>
                        </div>';
            })
            ->rawColumns(['code', 'type', 'status', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Redirect to index page as we use modal for create/edit
        return redirect()->route('super_admin.coupons.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CouponRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CouponRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->couponService->store($request);
            DB::commit();

            if ($request->ajax()) {
                return $this->success([], __('Coupon created successfully.'));
            }

            return redirect()->route('super_admin.coupons.index')->with('success', __('Coupon created successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return $this->error([], getErrorMessage($e, $e->getMessage()));
            }
            
            return redirect()->back()->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Return coupon data as JSON for AJAX requests, otherwise redirect
        if (request()->ajax()) {
            $coupon = $this->couponService->getById($id);
            return $this->success($coupon, __('Coupon data loaded successfully.'));
        }
        
        // Redirect to index page as we use modal for create/edit
        return redirect()->route('super_admin.coupons.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CouponRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CouponRequest $request, $id)
    {
        try {
            DB::beginTransaction();
            $coupon = $this->couponService->getById($id);
            $this->couponService->update($request, $id);
            DB::commit();

            if ($request->ajax()) {
                return $this->success([], __('Coupon updated successfully.'));
            }

            return redirect()->route('super_admin.coupons.index')->with('success', __('Coupon updated successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return $this->error([], getErrorMessage($e, $e->getMessage()));
            }
            
            return redirect()->back()->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $coupon = $this->couponService->getById($id);
            $this->couponService->deleteById($id);
            DB::commit();
            
            if (request()->ajax()) {
                return $this->success([], __('Coupon deleted successfully.'));
            }
            
            return redirect()->route('super_admin.coupons.index')->with('success', __('Coupon deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->ajax()) {
                return $this->error([], getErrorMessage($e, $e->getMessage()));
            }
            
            return redirect()->back()->with('error', getErrorMessage($e, $e->getMessage()));
        }
    }
}