<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Garments\SupplierRequest;
use App\Models\Garments\Supplier;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use App\Services\Garments\AuditLogService;

class SupplierController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return datatables(Supplier::query()->latest())
                ->addIndexColumn()
                ->addColumn('status_label', fn ($supplier) => $supplier->status == STATUS_ACTIVE
                    ? '<span class="zBadge zBadge-complete">' . __('Active') . '</span>'
                    : '<span class="zBadge zBadge-deactive">' . __('Deactivate') . '</span>')
                ->addColumn('action', fn ($supplier) => '<div class="inline-flex"><div class="dropdown options-area"><a class="options-btn" href="#" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis"></i></a><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="javascript:void(0)" onclick="getEditModal(\'' . route('admin.garments.suppliers.edit', $supplier->id) . '\', \'#supplier-edit-modal\')">' . __('Edit') . '</a></li><li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.suppliers.destroy', $supplier->id) . '\', \'supplierDataTable\')">' . __('Delete') . '</a></li></ul></div></div>')
                ->rawColumns(['status_label', 'action'])
                ->make(true);
        }

        return view('admin.garments.suppliers.index', [
            'title' => __('Suppliers / Vendors'),
            'activeGarments' => 'active',
            'activeGarmentSuppliers' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(SupplierRequest $request)
    {
        $supplier = Supplier::create($request->validated());
        app(AuditLogService::class)->record('created', 'Supplier', $supplier->id, "Supplier {$supplier->supplier_code} created.");
        return $this->success([], getMessage(CREATED_SUCCESSFULLY));
    }

    public function edit($id)
    {
        return view('admin.garments.suppliers.form', ['supplier' => Supplier::findOrFail($id)]);
    }

    public function update(SupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $changes = $supplier->getDirty();
        $supplier->update($request->validated());
        app(AuditLogService::class)->record('updated', 'Supplier', $supplier->id, "Supplier {$supplier->supplier_code} updated.", $changes);
        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        app(AuditLogService::class)->record('deleted', 'Supplier', $supplier->id, "Supplier {$supplier->supplier_code} deleted.");
        return $this->success([], getMessage(DELETED_SUCCESSFULLY));
    }
}
