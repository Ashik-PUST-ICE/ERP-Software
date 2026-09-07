<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\LeaveFormRequest;
use App\Http\Services\Admin\HRM\LeaveService;
use App\Models\HRM\HrmEmployee;
use App\Models\HRM\LeaveRequest as LeaveModel;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public $leaveService;

    public function __construct()
    {
        $this->leaveService = new LeaveService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $leaves = LeaveModel::with('employee.department')->orderBy('id', 'DESC');
            
            if ($request->filled('status')) {
                $leaves->where('status', $request->status);
            }
            if ($request->filled('employee_id')) {
                $leaves->where('employee_id', $request->employee_id);
            }

            return datatables($leaves)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('employee', function ($data) {
                    return $data->employee->first_name . ' ' . $data->employee->last_name;
                })
                ->addColumn('leave_type', function ($data) {
                    return ucfirst($data->leave_type);
                })
                ->addColumn('duration', function ($data) {
                    return $data->start_date . ' to ' . $data->end_date . ' (' . $data->days_count . ' Days)';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == LEAVE_STATUS_APPROVED) {
                        return '<div class="zBadge zBadge-complete">' . __("Approved") . '</div>';
                    } elseif ($data->status == LEAVE_STATUS_REJECTED) {
                        return '<div class="zBadge zBadge-deactive">' . __("Rejected") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-warning">' . __("Pending") . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    $actions = '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end">';
                    
                    if ($data->status == LEAVE_STATUS_PENDING) {
                        $actions .= '<li><a class="dropdown-item" href="' . route('admin.hrm.leaves.approve', $data->id) . '">' . __('Approve') . '</a></li>';
                        $actions .= '<li><a class="dropdown-item" href="javascript:void(0)" onclick="rejectLeave(\'' . route('admin.hrm.leaves.reject', $data->id) . '\')">' . __('Reject') . '</a></li>';
                    } else {
                        $actions .= '<li><span class="dropdown-item text-muted">' . __('No Actions') . '</span></li>';
                    }
                    
                    $actions .= '</ul></div></div>';
                    return $actions;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $data['title'] = __('Leave Requests');
        $data['activeHrm'] = 'active';
        $data['activeLeave'] = 'active';
        $data['activeLeaves'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['employees'] = HrmEmployee::where('status', EMPLOYEE_STATUS_ACTIVE)->get(['id', 'first_name', 'last_name']);
        $data['pendingCount'] = LeaveModel::where('status', LEAVE_STATUS_PENDING)->count();

        return view('admin.hrm.leaves.index', $data);
    }

    public function store(LeaveFormRequest $request)
    {
        $response = $this->leaveService->store($request);
        if ($response->getData()->status) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }

    public function approve($id)
    {
        $response = $this->leaveService->approve($id);
        if ($response->getData()->status) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }

    public function reject(Request $request, $id)
    {
        $response = $this->leaveService->reject($id, $request->admin_note);
        if ($response->getData()->status) {
            return back()->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }
}
