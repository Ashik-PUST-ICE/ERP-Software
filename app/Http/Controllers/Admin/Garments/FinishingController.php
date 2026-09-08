<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\FinishingEntryRequest;
use App\Http\Services\Admin\Garments\FinishingEntryService;
use App\Models\Garments\FinishingEntry;
use App\Models\Garments\GarmentOrder;
use Illuminate\Http\Request;

class FinishingController extends Controller
{
    public function __construct(public FinishingEntryService $finishingService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = FinishingEntry::with('order')->orderByDesc('finishing_date')->orderByDesc('id');

            return datatables($rows)
                ->addIndexColumn()
                ->addColumn('sl', function ($row) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('quantity_display', fn ($row) => $row->passed_quantity . ' / ' . $row->received_quantity)
                ->addColumn('date_display', fn ($row) => $row->finishing_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($row) {
                    [$label, $class] = garmentFinishingStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', fn ($row) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.finishing.edit', $row->id) . '\', \'#edit-finishing-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.finishing.destroy', $row->id) . '\', \'garmentFinishingDataTable\')">' . __('Delete') . '</a></li></ul></div></div>')
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.finishing.index', [
            'title' => __('Finishing Entries'),
            'orders' => GarmentOrder::orderByDesc('id')->get(),
            'activeGarments' => 'active',
            'activeGarmentFinishing' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(FinishingEntryRequest $request)
    {
        return $this->finishingService->store($request);
    }

    public function edit($id)
    {
        $finishing = FinishingEntry::findOrFail($id);
        $orders = GarmentOrder::orderByDesc('id')->get();
        return view('admin.garments.finishing.form', compact('finishing', 'orders'));
    }

    public function update(FinishingEntryRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->finishingService->store($request);
    }

    public function destroy($id)
    {
        return $this->finishingService->destroy($id);
    }
}
