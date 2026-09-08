<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\InlineQcRequest;
use App\Http\Services\Admin\Garments\InlineQcService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\InlineQc;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class InlineQcController extends Controller
{
    use ResponseTrait;

    public function __construct(public InlineQcService $qcService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = InlineQc::with('order')
                ->orderByDesc('inspection_date')
                ->orderByDesc('id');

            return datatables($rows)
                ->addIndexColumn()
                ->addColumn('sl', function ($row) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('date_display', fn ($row) => $row->inspection_date?->format('d M Y') ?? 'N/A')
                ->addColumn('result_display', fn ($row) => $row->passed_quantity . ' / ' . $row->checked_quantity)
                ->addColumn('status', function ($row) {
                    [$label, $class] = garmentQcStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.inline-qc.edit', $row->id) . '\', \'#edit-inline-qc-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.inline-qc.destroy', $row->id) . '\', \'garmentInlineQcDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.inline-qc.index', [
            'title' => __('Inline QC'),
            'orders' => GarmentOrder::orderByDesc('id')->get(),
            'activeGarments' => 'active',
            'activeGarmentInlineQc' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(InlineQcRequest $request)
    {
        return $this->qcService->store($request);
    }

    public function edit($id)
    {
        $qc = InlineQc::findOrFail($id);
        $orders = GarmentOrder::orderByDesc('id')->get();
        return view('admin.garments.inline-qc.form', compact('qc', 'orders'));
    }

    public function update(InlineQcRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->qcService->store($request);
    }

    public function destroy($id)
    {
        return $this->qcService->destroy($id);
    }
}
