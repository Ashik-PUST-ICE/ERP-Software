<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\PurchaseOrderRequest;
use App\Models\Garments\PurchaseOrder;
use App\Models\Garments\Supplier;
use App\Models\Garments\Material;
use Illuminate\Support\Facades\DB;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(PurchaseOrder::with('supplier')->latest())
                ->addIndexColumn()
                ->addColumn('supplier_name', fn ($po) => e($po->supplier->company_name))
                ->addColumn('status_label', fn ($po) => '<span class="zBadge zBadge-complete">' . e($po->status) . '</span>')
                ->addColumn('action', fn ($po) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="' . route('admin.garments.purchase-orders.print', $po->id) . '" target="_blank">' . __('Print') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.purchase-orders.edit', $po->id) . '\', \'#purchase-order-edit-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.purchase-orders.destroy', $po->id) . '\', \'purchaseOrderDataTable\')">' . __('Delete') . '</a></li></ul></div></div>')
                ->rawColumns(['status_label', 'action'])
                ->make(true);
        }

        return view('admin.garments.purchase-orders.index', [
            'title' => __('Purchase Orders'),
            'suppliers' => Supplier::where('status', STATUS_ACTIVE)->orderBy('company_name')->get(),
            'materials' => Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get(),
            'activeGarments' => 'active', 'activeGarmentPurchaseOrders' => 'active', 'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(PurchaseOrderRequest $request)
    {
        $this->saveOrder($request);
        return $this->success([], getMessage(CREATED_SUCCESSFULLY));
    }

    public function edit($id)
    {
        return view('admin.garments.purchase-orders.form', ['purchaseOrder' => PurchaseOrder::with('items')->findOrFail($id), 'suppliers' => Supplier::where('status', STATUS_ACTIVE)->orderBy('company_name')->get(), 'materials' => Material::where('status', STATUS_ACTIVE)->orderBy('item_name')->get()]);
    }

    public function print($id)
    {
        return view('admin.garments.purchase-orders.print', [
            'purchaseOrder' => PurchaseOrder::with(['supplier', 'items.material'])->findOrFail($id),
        ]);
    }

    public function update(PurchaseOrderRequest $request, $id)
    {
        $request->merge(['id' => $id]);
        $this->saveOrder($request, $id);
        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    private function saveOrder(PurchaseOrderRequest $request, ?int $id = null): void
    {
        DB::transaction(function () use ($request, $id) {
            $data = $request->validated();
            $items = collect($data['items'] ?? [])
                ->filter(fn ($item) => filled($item['material_id'] ?? null))
                ->values()
                ->all();
            unset($data['items']);
            $total = collect($items)->sum(fn ($item) => (float) $item['quantity'] * (float) $item['unit_rate']);
            if ($items) {
                $data['total_amount'] = $total;
            }
            $order = $id ? PurchaseOrder::findOrFail($id) : PurchaseOrder::create($data);
            if ($id) {
                $order->update($data);
                $order->items()->delete();
            }
            foreach ($items as $item) {
                $order->items()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                    'unit_rate' => $item['unit_rate'],
                    'line_total' => $item['quantity'] * $item['unit_rate'],
                ]);
            }
        });
    }

    public function destroy($id)
    {
        PurchaseOrder::findOrFail($id)->delete();
        return $this->success([], getMessage(DELETED_SUCCESSFULLY));
    }
}
