<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\CuttingRequest;
use App\Http\Services\Admin\Garments\CuttingService;
use App\Models\Garments\Cutting;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Material;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class CuttingController extends Controller
{
    use ResponseTrait;
    public function __construct(public CuttingService $cuttingService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cuttings = Cutting::with(['order.style', 'material'])->orderByDesc('cutting_date')->orderByDesc('id');
            return datatables($cuttings)->addIndexColumn()->addColumn('sl', function ($row) { static $count = 0; return ++$count; })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('style_code', fn ($row) => e($row->order?->style?->style_code ?? 'N/A'))
                ->addColumn('cut_display', fn ($row) => number_format($row->cut_quantity))
                ->addColumn('cutting_date_display', fn ($row) => $row->cutting_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($row) { [$label, $class] = garmentCuttingStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning']; return '<div class="zBadge ' . $class . '">' . __($label) . '</div>'; })
                ->addColumn('action', function ($row) { return '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.cutting.edit', $row->id) . '\', \'#edit-cutting-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.cutting.destroy', $row->id) . '\', \'garmentCuttingDataTable\')">' . __('Delete') . '</a></li></ul></div></div>'; })
                ->rawColumns(['status', 'action'])->make(true);
        }
        return view('admin.garments.cutting.index', ['title' => __('Cutting Section'), 'orders' => GarmentOrder::with('style')->orderByDesc('id')->get(), 'materials' => Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get(), 'activeGarments' => 'active', 'activeGarmentCutting' => 'active', 'showGarmentsMenu' => 'show']);
    }

    public function store(CuttingRequest $request) { return $this->cuttingService->store($request); }
    public function edit($id) { $cutting = Cutting::findOrFail($id); $orders = GarmentOrder::with('style')->orderByDesc('id')->get(); $materials = Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get(); return view('admin.garments.cutting.form', compact('cutting', 'orders', 'materials')); }
    public function update(CuttingRequest $request, $id) { $request->merge(['id' => $id]); return $this->cuttingService->store($request); }
    public function destroy($id) { return $this->cuttingService->destroy($id); }
}
