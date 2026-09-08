<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Exports\GarmentStockMovementExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GarmentExportController extends Controller
{
    public function stockMovements(Request $request)
    {
        return Excel::download(new GarmentStockMovementExport($request->integer('material_id') ?: null), 'garment-stock-movements.xlsx');
    }
}
