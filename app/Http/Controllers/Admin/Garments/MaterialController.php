<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\MaterialRequest;
use App\Http\Services\Admin\Garments\MaterialService;
use App\Models\Garments\Material;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MaterialController extends Controller
{
    use ResponseTrait;

    public function __construct(public MaterialService $materialService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $materials = Material::query()->orderBy('item_name')->orderByDesc('id');

            return datatables($materials)
                ->addIndexColumn()
                ->addColumn('sl', function ($material) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('category_label', fn ($material) => __(garmentMaterialCategories()[$material->category] ?? $material->category))
                ->addColumn('stock_display', fn ($material) => number_format((float) $material->current_stock, 4) . ' ' . e($material->unit))
                ->addColumn('stock_status', function ($material) {
                    if ((float) $material->current_stock <= (float) $material->reorder_level) {
                        return '<div class="zBadge zBadge-warning">' . __('Low Stock') . '</div>';
                    }
                    return '<div class="zBadge zBadge-complete">' . __('In Stock') . '</div>';
                })
                ->addColumn('status', function ($material) {
                    return $material->status == STATUS_ACTIVE
                        ? '<div class="zBadge zBadge-complete">' . __('Active') . '</div>'
                        : '<div class="zBadge zBadge-deactive">' . __('Deactivate') . '</div>';
                })
                ->addColumn('action', function ($material) {
                    return '<div class="inline-flex"><div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="' . route('admin.garments.materials.label', $material->id) . '" target="_blank">' . __('Print Barcode / QR') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.materials.edit', $material->id) . '\', \'#edit-material-modal\')">' . __('Edit') . '</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.materials.destroy', $material->id) . '\', \'garmentMaterialDataTable\')">' . __('Delete') . '</a></li>
                        </ul>
                    </div></div>';
                })
                ->rawColumns(['stock_status', 'status', 'action'])
                ->make(true);
        }

        return view('admin.garments.materials.index', [
            'title' => __('Raw Material Inventory'),
            'activeGarments' => 'active',
            'activeGarmentMaterials' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(MaterialRequest $request)
    {
        if (!$request->filled('barcode')) {
            $request->merge(['barcode' => 'MAT-' . strtoupper(Str::random(10))]);
        }

        return $this->materialService->store($request);
    }

    public function label($id)
    {
        return view('admin.garments.materials.label', [
            'material' => Material::findOrFail($id),
        ]);
    }

    public function edit($id)
    {
        $material = Material::findOrFail($id);
        return view('admin.garments.materials.form', compact('material'));
    }

    public function update(MaterialRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        if (!$request->filled('barcode')) {
            $request->merge(['barcode' => 'MAT-' . strtoupper(Str::random(10))]);
        }
        return $this->materialService->store($request);
    }

    public function destroy($id)
    {
        return $this->materialService->destroy($id);
    }
}
