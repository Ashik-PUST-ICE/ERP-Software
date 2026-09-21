<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Material;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MaterialScannerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $materials = Material::query()->orderByDesc('id');

            return DataTables::of($materials)
                ->addIndexColumn()
                ->addColumn('current_stock', fn ($material) => (float) $material->current_stock)
                ->make(true);
        }

        return view('admin.garments.materials.scanner', [
            'title' => __('Material Barcode Scanner'),
            'activeGarments' => 'active',
            'activeGarmentMaterials' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function lookup(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:100']);
        $material = Material::where('barcode', $data['code'])->orWhere('item_code', $data['code'])->first();

        return $material
            ? response()->json(['material' => $material])
            : response()->json(['message' => __('Material not found.')], 404);
    }
}
