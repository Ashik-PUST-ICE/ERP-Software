<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\GrnRequest;
use App\Http\Services\Admin\Garments\GrnService;
use App\Models\Garments\Grn;
use App\Models\Garments\Material;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class GrnController extends Controller
{
    use ResponseTrait;

    public function __construct(public GrnService $grnService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $grns = Grn::with('material')->orderByDesc('received_date')->orderByDesc('id');

            return datatables($grns)
                ->addIndexColumn()
                ->addColumn('sl', function ($grn) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('material_name', fn ($grn) => e($grn->material?->item_name ?? 'N/A'))
                ->addColumn('accepted_display', fn ($grn) => number_format((float) $grn->accepted_quantity, 4) . ' ' . e($grn->material?->unit ?? ''))
                ->addColumn('received_date_display', fn ($grn) => $grn->received_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($grn) {
                    [$label, $class] = garmentGrnStatuses()[$grn->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', function ($grn) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.grns.edit', $grn->id) . '\', \'#edit-grn-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.grns.destroy', $grn->id) . '\', \'garmentGrnDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.grns.index', [
            'title' => __('Goods Received Notes'),
            'materials' => Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get(),
            'activeGarments' => 'active',
            'activeGarmentGrns' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(GrnRequest $request)
    {
        return $this->grnService->store($request);
    }

    public function edit($id)
    {
        $grn = Grn::findOrFail($id);
        $materials = Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get();
        return view('admin.garments.grns.form', compact('grn', 'materials'));
    }

    public function update(GrnRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->grnService->store($request);
    }

    public function destroy($id)
    {
        return $this->grnService->destroy($id);
    }
}
