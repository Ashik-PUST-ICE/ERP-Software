<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\FinalInspection;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\ProductionPlan;
use App\Models\Garments\SewingProduction;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ProductionAnalyticsController extends Controller
{
    public function index()
    {
        $summary = $this->summary();

        return view('admin.garments.analytics.index', [
            'title' => __('Production Analytics'),
            'summary' => $summary,
            'activeGarments' => 'active',
            'activeGarmentAnalytics' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function data(): JsonResponse
    {
        return response()->json($this->summary());
    }

    private function summary(): array
    {
        $from = Carbon::today()->subDays(13);
        $production = SewingProduction::whereDate('production_date', '>=', $from)
            ->selectRaw('production_date, SUM(total_output) as output, SUM(daily_target) as target')
            ->groupBy('production_date')
            ->orderBy('production_date')
            ->get();
        $maxTarget = max(1, (int) $production->max('target'));

        $planned = ProductionPlan::sum('planned_quantity');
        $produced = SewingProduction::sum('total_output');
        $inspected = FinalInspection::sum('lot_quantity');
        $rejected = FinalInspection::sum('rejected_quantity');

        return [
            'kpis' => [
                'orders' => GarmentOrder::count(),
                'planned_quantity' => (int) $planned,
                'produced_quantity' => (int) $produced,
                'achievement' => $planned > 0 ? round(($produced / $planned) * 100, 1) : 0,
                'rejection_rate' => $inspected > 0 ? round(($rejected / $inspected) * 100, 1) : 0,
            ],
            'order_status' => GarmentOrder::selectRaw('status, COUNT(*) as total')
                ->groupBy('status')->orderBy('status')->get()
                ->map(fn ($row) => ['status' => (int) $row->status, 'total' => (int) $row->total])->values(),
            'daily_output' => $production->map(fn ($row) => [
                'date' => Carbon::parse($row->production_date)->format('d M'),
                'output' => (int) $row->output,
                'target' => (int) $row->target,
                'target_width' => min(100, ((int) $row->target / $maxTarget) * 100),
                'output_width' => min(100, ((int) $row->output / $maxTarget) * 100),
            ])->values(),
        ];
    }
}
