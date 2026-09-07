<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\EmployeeRequest;
use App\Http\Services\Admin\HRM\EmployeeService;
use App\Models\HRM\Department;
use App\Models\HRM\Designation;
use App\Models\HRM\HrmEmployee;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use ResponseTrait;
    public $employeeService;

    public function __construct()
    {
        $this->employeeService = new EmployeeService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $employees = HrmEmployee::with(['department', 'designation'])->orderBy('id', 'DESC');
            
            if ($request->filled('department_id')) {
                $employees->where('department_id', $request->department_id);
            }
            if ($request->filled('status')) {
                $employees->where('status', $request->status);
            }

            return datatables($employees)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('full_name', function ($data) {
                    return $data->first_name . ' ' . $data->last_name;
                })
                ->addColumn('department', function ($data) {
                    return $data->department->name ?? 'N/A';
                })
                ->addColumn('designation', function ($data) {
                    return $data->designation->name ?? 'N/A';
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == EMPLOYEE_STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-complete">' . __("Active") . '</div>';
                    } elseif ($data->status == EMPLOYEE_STATUS_ON_LEAVE) {
                        return '<div class="zBadge zBadge-warning">' . __("On Leave") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __("Terminated") . '</div>';
                    }
                })
                ->addColumn('action', function ($data) {
                    return
                        '<div class="inline-flex">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="' . route('admin.hrm.employees.show', $data->id) . '">
                                            ' . __('View') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="' . route('admin.hrm.employees.edit', $data->id) . '">
                                            ' . __('Edit') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.hrm.employees.destroy', [$data->id]) . '\', \'employeeDataTable\')">
                                            ' . __('Delete') . '
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>';
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        $data['title'] = __('Employees');
        $data['activeHrm'] = 'active';
        $data['activeEmployee'] = 'active';
        $data['activeEmployees'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['departments'] = Department::where('status', STATUS_ACTIVE)->get();
        return view('admin.hrm.employees.index', $data);
    }

    public function create()
    {
        $data['title'] = __('Add Employee');
        $data['activeHrm'] = 'active';
        $data['activeEmployee'] = 'active';
        $data['activeEmployees'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['departments'] = Department::where('status', STATUS_ACTIVE)->get();
        return view('admin.hrm.employees.create', $data);
    }

    public function store(EmployeeRequest $request)
    {
        $response = $this->employeeService->store($request);
        if ($response->getData()->success) {
            return redirect()->route('admin.hrm.employees.index')->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }

    public function show($id)
    {
        $data['title'] = __('Employee Details');
        $data['activeHrm'] = 'active';
        $data['activeEmployee'] = 'active';
        $data['activeEmployees'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['employee'] = HrmEmployee::with(['department', 'designation', 'attendances' => function ($q) {
            $q->latest()->take(30);
        }, 'leaveRequests' => function ($q) {
            $q->latest()->take(10);
        }, 'payrolls' => function ($q) {
            $q->latest()->take(6);
        }])->findOrFail($id);

        return view('admin.hrm.employees.show', $data);
    }

    public function edit($id)
    {
        $data['title'] = __('Edit Employee');
        $data['activeHrm'] = 'active';
        $data['activeEmployee'] = 'active';
        $data['activeEmployees'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['employee'] = HrmEmployee::findOrFail($id);
        $data['departments'] = Department::where('status', STATUS_ACTIVE)->get();
        $data['designations'] = Designation::where('department_id', $data['employee']->department_id)->where('status', STATUS_ACTIVE)->get();
        return view('admin.hrm.employees.create', $data);
    }

    public function update(EmployeeRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        $response = $this->employeeService->store($request);
        if ($response->getData()->success) {
            return redirect()->route('admin.hrm.employees.index')->with('success', $response->getData()->message);
        }
        return back()->with('error', $response->getData()->message);
    }

    public function destroy($id)
    {
        return $this->employeeService->destroy($id);
    }

    public function getDesignations(Request $request)
    {
        $designations = Designation::where('department_id', $request->department_id)
            ->where('status', STATUS_ACTIVE)
            ->get(['id', 'name']);
        return response()->json($designations);
    }
}
