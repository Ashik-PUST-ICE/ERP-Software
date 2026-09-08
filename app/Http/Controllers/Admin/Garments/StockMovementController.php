<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\Material;
use App\Models\Garments\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $movements = StockMovement::with('material')
            ->when($request->filled('material_id'), fn ($query) => $query->where('material_id', $request->integer('material_id')))
            ->latest()->paginate(25)->withQueryString();

        return view('admin.garments.stock-movements.index', [
            'title' => __('Stock Movement Ledger'),
            'movements' => $movements,
            'materials' => Material::orderBy('item_name')->get(),
            'activeGarments' => 'active', 'activeGarmentStockMovements' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }
}
