<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\DepartmentRequest;
use App\Http\Services\Admin\HRM\DepartmentService;
use App\Models\HRM\Department;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use ResponseTrait;
    public $departmentService;

    public function __construct()
    {
        $this->departmentService = new DepartmentService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::withCount('employees')->orderBy('id', 'DESC');
            return datatables($departments)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('status', function ($data) {
                    if ($data->status == STATUS_ACTIVE) {
                        return '<div class="zBadge zBadge-complete">' . __("Active") . '</div>';
                    } else {
                        return '<div class="zBadge zBadge-deactive">' . __("Deactivate") . '</div>';
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
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.hrm.departments.edit', $data->id) . '\', \'#edit-modal\')">
                                            ' . __('Edit') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.hrm.departments.destroy', [$data->id]) . '\', \'departmentDataTable\')">
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

        $data['title'] = __('Departments');
        $data['activeHrm'] = 'active';
        $data['activeDepartment'] = 'active';
        $data['activeDepartments'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        return view('admin.hrm.departments.index', $data);
    }

    public function store(DepartmentRequest $request)
    {
        return $this->departmentService->store($request);
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.hrm.departments.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->departmentService->store($request);
    }

    public function destroy($id)
    {
        return $this->departmentService->destroy($id);
    }
}
