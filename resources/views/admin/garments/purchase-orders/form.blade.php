<form class="ajax reset" action="{{ $purchaseOrder ? route('admin.garments.purchase-orders.update', $purchaseOrder->id) : route('admin.garments.purchase-orders.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($purchaseOrder)
        @method('put')
    @endif
    @php($existingItem = $purchaseOrder?->items?->first())
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $purchaseOrder ? __('Edit Purchase Order') : __('Add Purchase Order') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Supplier') }} <span class="required">*</span></label>
                        <select class="form-control" name="supplier_id" required>
                            <option value="">{{ __('Select Supplier') }}</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" @selected(old('supplier_id', $purchaseOrder?->supplier_id) == $supplier->id)>
                                    {{ $supplier->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('PO Number') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="po_number" value="{{ old('po_number', $purchaseOrder?->po_number) }}" placeholder="{{ __('e.g. PO-2026-001') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="order_date" value="{{ old('order_date', $purchaseOrder?->order_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Expected Date') }}</label>
                        <input type="date" class="form-control" name="expected_date" value="{{ old('expected_date', $purchaseOrder?->expected_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Total Amount') }} <span class="required">*</span></label>
                        <input type="number" min="0" step="0.01" class="form-control" name="total_amount" value="{{ old('total_amount', $purchaseOrder?->total_amount ?? 0) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ STATUS_PENDING }}" @selected(old('status', $purchaseOrder?->status) == STATUS_PENDING)>{{ __('Pending') }}</option>
                            <option value="{{ STATUS_ACTIVE }}" @selected(old('status', $purchaseOrder?->status) == STATUS_ACTIVE)>{{ __('Approved') }}</option>
                            <option value="{{ STATUS_CANCELLED }}" @selected(old('status', $purchaseOrder?->status) == STATUS_CANCELLED)>{{ __('Cancelled') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <input type="text" class="form-control" name="notes" value="{{ old('notes', $purchaseOrder?->notes) }}" placeholder="{{ __('Remarks / terms...') }}">
                    </div>
                </div>

                <div class="col-12"><hr class="my-2"></div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Material Line Item') }}</label>
                        <select class="form-control" name="items[0][material_id]">
                            <option value="">{{ __('Optional material line') }}</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" @selected($existingItem?->material_id == $material->id)>
                                    {{ $material->item_code }} - {{ $material->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Quantity') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="items[0][quantity]" value="{{ $existingItem?->quantity }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Unit Rate') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="items[0][unit_rate]" value="{{ $existingItem?->unit_rate }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $purchaseOrder ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
