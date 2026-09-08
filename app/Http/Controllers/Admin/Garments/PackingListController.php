<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\PackingListRequest;
use App\Http\Services\Admin\Garments\PackingListService;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\PackingList;
use Illuminate\Http\Request;

class PackingListController extends Controller
{
    public function __construct(public PackingListService $packingService)
    {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $rows = PackingList::with('order')->orderByDesc('packing_date')->orderByDesc('id');

            return datatables($rows)
                ->addIndexColumn()
                ->addColumn('sl', function ($row) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? 'N/A'))
                ->addColumn('color_size', fn ($row) => e(trim(($row->color ?? '') . ' / ' . ($row->size ?? ''), ' /')))
                ->addColumn('weight_display', fn ($row) => $row->net_weight . ' / ' . $row->gross_weight)
                ->addColumn('date_display', fn ($row) => $row->packing_date?->format('d M Y') ?? 'N/A')
                ->addColumn('status', function ($row) {
                    [$label, $class] = garmentPackingStatuses()[$row->status] ?? ['Unknown', 'zBadge-warning'];
                    return '<div class="zBadge ' . $class . '">' . __($label) . '</div>';
                })
                ->addColumn('action', fn ($row) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.packing-lists.edit', $row->id) . '\', \'#edit-packing-list-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.packing-lists.destroy', $row->id) . '\', \'garmentPackingListDataTable\')">' . __('Delete') . '</a></li></ul></div></div>')
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('admin.garments.packing-lists.index', [
            'title' => __('Packing Lists'),
            'orders' => GarmentOrder::orderByDesc('id')->get(),
            'activeGarments' => 'active',
            'activeGarmentPacking' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(PackingListRequest $request)
    {
        return $this->packingService->store($request);
    }

    public function edit($id)
    {
        $packing = PackingList::findOrFail($id);
        $orders = GarmentOrder::orderByDesc('id')->get();
        return view('admin.garments.packing-lists.form', compact('packing', 'orders'));
    }

    public function update(PackingListRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        return $this->packingService->store($request);
    }

    public function destroy($id)
    {
        return $this->packingService->destroy($id);
    }
}
