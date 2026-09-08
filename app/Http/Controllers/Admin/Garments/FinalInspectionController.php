<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\FinalInspectionRequest;
use App\Http\Services\Admin\Garments\FinalInspectionService;
use App\Models\Garments\FinalInspection;
use App\Models\Garments\GarmentOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class FinalInspectionController extends Controller
{
    use ResponseTrait;

    public function __construct(public FinalInspectionService $inspectionService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = FinalInspection::with('order')
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
                ->addColumn('lot_display', fn ($row) => $row->sample_quantity . ' / ' . $row->lot_quantity)
                ->addColumn('result', function ($row) {
                    [$label, $class] = garmentInspectionResults()[$row->result] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.final-inspections.edit', $row->id) . '\', \'#edit-final-inspection-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.final-inspections.destroy', $row->id) . '\', \'garmentFinalInspectionDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['result', 'action'])
                ->make(true);
        }

        return view('admin.garments.final-inspections.index', [
            'title' => __('Final Inspection / AQL'),
            'orders' => GarmentOrder::orderByDesc('id')->get(),
            'activeGarments' => 'active',
            'activeGarmentFinalInspection' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(FinalInspectionRequest $request)
    {
        return $this->inspectionService->store($request);
    }

    public function edit($id)
    {
        $inspection = FinalInspection::findOrFail($id);
        $orders = GarmentOrder::orderByDesc('id')->get();
        return view('admin.garments.final-inspections.form', compact('inspection', 'orders'));
    }

    public function update(FinalInspectionRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->inspectionService->store($request);
    }

    public function destroy($id)
    {
        return $this->inspectionService->destroy($id);
    }
}
