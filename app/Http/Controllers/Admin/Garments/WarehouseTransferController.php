<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Material;
use App\Models\Garments\Warehouse;
use App\Models\Garments\WarehouseTransfer;
use App\Models\Garments\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseTransferController extends Controller
{
    public function index()
    {
        return response()->json(WarehouseTransfer::with('material')->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate(['material_id' => 'required|exists:garment_materials,id', 'from_warehouse_id' => 'required|exists:garment_warehouses,id|different:to_warehouse_id', 'to_warehouse_id' => 'required|exists:garment_warehouses,id', 'quantity' => 'required|numeric|min:0.0001', 'transfer_date' => 'required|date', 'notes' => 'nullable|string']);
        return DB::transaction(function () use ($data) {
            $material = Material::lockForUpdate()->findOrFail($data['material_id']);
            if ($material->warehouse_id != $data['from_warehouse_id'] || (float) $material->current_stock < (float) $data['quantity']) {
                abort(422, __('Insufficient stock or source warehouse mismatch.'));
            }
            $material->decrement('current_stock', $data['quantity']);
            $transfer = WarehouseTransfer::create($data + ['status' => STATUS_ACTIVE]);
            StockMovement::create(['material_id' => $material->id, 'warehouse_id' => $data['from_warehouse_id'], 'movement_type' => 'transfer_out', 'quantity' => -$data['quantity'], 'balance_after' => $material->fresh()->current_stock, 'reference_type' => 'Warehouse Transfer', 'reference_id' => $transfer->id]);
            return response()->json($transfer, 201);
        });
    }
}
