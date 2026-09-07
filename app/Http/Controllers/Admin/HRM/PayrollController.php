<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\PayrollRequest;
use App\Http\Requests\Admin\HRM\PayrollPaymentRequest;
use App\Http\Services\Admin\HRM\PayrollService;
use App\Models\HRM\Payroll;
use Illuminate\Http\Request;

class PayrollController extends Controller
{
    public $payrollService;

    public function __construct()
    {
        $this->payrollService = new PayrollService;
    }

    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));

        if ($request->ajax()) {
            $payrolls = Payroll::with('employee.department')
                ->where('payroll_month', $month)
                ->orderBy('id', 'DESC');

            return datatables($payrolls)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('employee', function ($data) {
                    return $data->employee->first_name . ' ' . $data->employee->last_name;
                })
                ->addColumn('department', function ($data) {
                    return $data->employee->department->name ?? 'N/A';
                })
                ->addColumn('net_salary', function ($data) {
                    return showPrice($data->net_salary);
                })
                ->addColumn('payment_status', function ($data) {
                    if ($data->payment_status == PAYMENT_STATUS_PAID) {
                        return '<div class="zBadge zBadge-complete">' . __("Paid") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-warning">' . __("Unpaid") . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $actions = '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end">';
                    
                    if ($data->payment_status == PAYMENT_STATUS_PENDING) {
                        $actions .= '<li><a class="dropdown-item" href="' . route('admin.hrm.payroll.markPaid', $data->id) . '">' . __('Mark as Paid') . '</a></li>';
                    } else {
                        $actions .= '<li><span class="dropdown-item text-muted">' . __('No Actions') . '</span></li>';
                    }
                    
                    $actions .= '</ul></div></div>';
                    return $actions;
                })
                ->rawColumns(['action', 'payment_status'])
                ->make(true);
        }

        $data['title'] = __('Payroll');
        $data['activeHrm'] = 'active';
        $data['activePayroll'] = 'active';
        $data['activePayrolls'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['month'] = $month;
        
        $data['summary'] = [
            'total_basic' => Payroll::where('payroll_month', $month)->sum('basic_salary'),
            'total_allowances' => Payroll::where('payroll_month', $month)->sum('allowances'),
            'total_deductions' => Payroll::where('payroll_month', $month)->sum('deductions'),
            'total_net' => Payroll::where('payroll_month', $month)->sum('net_salary'),
            'paid_count' => Payroll::where('payroll_month', $month)->where('payment_status', PAYMENT_STATUS_PAID)->count(),
            'unpaid_count' => Payroll::where('payroll_month', $month)->where('payment_status', PAYMENT_STATUS_PENDING)->count(),
        ];

        return view('admin.hrm.payroll.index', $data);
    }

    public function generate(PayrollRequest $request)
    {
        $response = $this->payrollService->generate($request->month);
        $data = $response->getData();
        if ($data->status) {
            return back()->with('success', $data->message);
        }
        return back()->with('error', $data->message);
    }

    public function markPaid(PayrollPaymentRequest $request, $id)
    {
        $response = $this->payrollService->markPaid($id, $request->payment_method);
        $data = $response->getData();
        if ($data->status) {
            return back()->with('success', $data->message);
        }
        return back()->with('error', $data->message);
    }

    public function bulkPay(PayrollRequest $request)
    {
        $response = $this->payrollService->bulkPay($request->month);
        $data = $response->getData();
        if ($data->status) {
            return back()->with('success', $data->message);
        }
        return back()->with('error', $data->message);
    }
}
