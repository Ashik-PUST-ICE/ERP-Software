<?php

namespace App\Http\Controllers\Admin\HRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HRM\DesignationRequest;
use App\Http\Services\Admin\HRM\DesignationService;
use App\Models\HRM\Department;
use App\Models\HRM\Designation;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    use ResponseTrait;
    public $designationService;

    public function __construct()
    {
        $this->designationService = new DesignationService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $designations = Designation::with('department')->withCount('employees')->orderBy('id', 'DESC');
            return datatables($designations)
                ->addIndexColumn()
                ->addColumn('sl', function ($data) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('department', function ($data) {
                    return $data->department->name ?? 'N/A';
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
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.hrm.designations.edit', $data->id) . '\', \'#edit-modal\')">
                                            ' . __('Edit') . '
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.hrm.designations.destroy', [$data->id]) . '\', \'designationDataTable\')">
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

        $data['title'] = __('Designations');
        $data['activeHrm'] = 'active';
        $data['activeDesignation'] = 'active';
        $data['activeDesignations'] = 'active';
        $data['showHrmMenu'] = 'show';
        $data['showHRMMenu'] = 'show';
        $data['departments'] = Department::where('status', STATUS_ACTIVE)->get();
        return view('admin.hrm.designations.index', $data);
    }

    public function store(DesignationRequest $request)
    {
        return $this->designationService->store($request);
    }

    public function edit($id)
    {
        $designation = Designation::findOrFail($id);
        $departments = Department::where('status', STATUS_ACTIVE)->get();
        return view('admin.hrm.designations.edit', compact('designation', 'departments'));
    }

    public function update(DesignationRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->designationService->store($request);
    }

    public function destroy($id)
    {
        return $this->designationService->destroy($id);
    }
}
