<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        return response()->json(Warehouse::where('status', STATUS_ACTIVE)->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        return response()->json(Warehouse::create($request->validate([
            'code' => 'required|string|max:30|unique:garment_warehouses,code',
            'name' => 'required|string|max:120',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|in:' . STATUS_ACTIVE . ',' . STATUS_DEACTIVATE,
        ])), 201);
    }
}
