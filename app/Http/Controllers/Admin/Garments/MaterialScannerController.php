<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Material;
use Illuminate\Http\Request;

class MaterialScannerController extends Controller
{
    public function index()
    {
        return view('admin.garments.materials.scanner', ['title' => __('Material Barcode Scanner'), 'activeGarments' => 'active', 'activeGarmentMaterials' => 'active', 'showGarmentsMenu' => 'show']);
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
