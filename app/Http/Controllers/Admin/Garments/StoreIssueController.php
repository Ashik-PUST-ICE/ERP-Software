<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\StoreIssueRequest;
use App\Http\Services\Admin\Garments\StoreIssueService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Material;
use App\Models\Garments\StoreIssue;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class StoreIssueController extends Controller
{
    use ResponseTrait;

    public function __construct(public StoreIssueService $issueService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $issues = StoreIssue::with(['material', 'order'])->orderByDesc('issue_date')->orderByDesc('id');
            return datatables($issues)
                ->addIndexColumn()
                ->addColumn('sl', function ($issue) { static $count = 0; return ++$count; })
                ->addColumn('material_name', fn ($issue) => e($issue->material?->item_name ?? 'N/A'))
                ->addColumn('order_number', fn ($issue) => e($issue->order?->order_number ?? 'General Store'))
                ->addColumn('net_display', fn ($issue) => number_format((float) $issue->net_quantity, 4) . ' ' . e($issue->material?->unit ?? ''))
                ->addColumn('issue_date_display', fn ($issue) => $issue->issue_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($issue) {
                    [$label, $class] = garmentIssueStatuses()[$issue->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($issue) {
                    return '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.issues.edit', $issue->id) . '\', \'#edit-issue-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.issues.destroy', $issue->id) . '\', \'garmentStoreIssueDataTable\')">' . __('Delete') . '</a></li></ul></div></div>';
                })
                ->rawColumns(['status', 'action'])->make(true);
        }

        return view('admin.garments.issues.index', [
            'title' => __('Store Issue Management'),
            'materials' => Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get(),
            'orders' => GarmentOrder::orderByDesc('id')->get(),
            'activeGarments' => 'active', 'activeGarmentIssues' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(StoreIssueRequest $request) { return $this->issueService->store($request); }

    public function edit($id)
    {
        $issue = StoreIssue::findOrFail($id);
        $materials = Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get();
        $orders = GarmentOrder::orderByDesc('id')->get();
        return view('admin.garments.issues.form', compact('issue', 'materials', 'orders'));
    }

    public function update(StoreIssueRequest $request, $id) { $request->merge(['id' => $id]); return $this->issueService->store($request); }
    public function destroy($id) { return $this->issueService->destroy($id); }
}
