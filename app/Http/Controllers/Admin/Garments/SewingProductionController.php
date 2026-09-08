<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\SewingProductionRequest;
use App\Http\Services\Admin\Garments\SewingProductionService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\SewingProduction;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SewingProductionController extends Controller
{
    use ResponseTrait;

    public function __construct(public SewingProductionService $sewingService) {}

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $productions = SewingProduction::with(['order.style'])->orderByDesc('production_date')->orderByDesc('id');
            return datatables($productions)->addIndexColumn()->addColumn('sl', function ($row) { static $count = 0; return ++$count; })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('style_code', fn ($row) => e($row->order?->style?->style_code ?? 'N/A'))
                ->addColumn('date_display', fn ($row) => $row->production_date?->format('d M Y') ?? 'N/A')
                ->addColumn('output_display', fn ($row) => number_format($row->total_output) . ' / ' . number_format($row->daily_target))
                ->addColumn('status', function ($row) { [$label, $class] = garmentSewingStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning']; return '<div class="zBadge ' . $class . '">' . __($label) . '</div>'; })
                ->addColumn('action', function ($row) { return '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.sewing.edit', $row->id) . '\', \'#edit-sewing-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.sewing.destroy', $row->id) . '\', \'garmentSewingDataTable\')">' . __('Delete') . '</a></li></ul></div></div>'; })
                ->rawColumns(['status', 'action'])->make(true);
        }

        return view('admin.garments.sewing.index', ['title' => __('Sewing Line Production'), 'orders' => GarmentOrder::with('style')->orderByDesc('id')->get(), 'activeGarments' => 'active', 'activeGarmentSewing' => 'active', 'showGarmentsMenu' => 'show']);
    }

    public function store(SewingProductionRequest $request) { return $this->sewingService->store($request); }
    public function edit($id) { $production = SewingProduction::findOrFail($id); $orders = GarmentOrder::with('style')->orderByDesc('id')->get(); return view('admin.garments.sewing.form', compact('production', 'orders')); }
    public function update(SewingProductionRequest $request, $id) { $request->merge(['id' => $id]); return $this->sewingService->store($request); }
    public function destroy($id) { return $this->sewingService->destroy($id); }
}
