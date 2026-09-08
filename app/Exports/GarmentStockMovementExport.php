<?php

namespace App\Exports;

use App\Models\Garments\StockMovement;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GarmentStockMovementExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private readonly ?int $materialId = null) {}

    public function query()
    {
        return StockMovement::with('material')
            ->when($this->materialId, fn ($query) => $query->where('material_id', $this->materialId))
            ->latest();
    }

    public function headings(): array
    {
        return ['Date', 'Material', 'Movement Type', 'Quantity', 'Balance After', 'Reference'];
    }

    public function map($movement): array
    {
        return [$movement->created_at->format('Y-m-d H:i'), $movement->material?->item_code, $movement->movement_type, $movement->quantity, $movement->balance_after, $movement->reference_type . ' #' . $movement->reference_id];
    }
}
