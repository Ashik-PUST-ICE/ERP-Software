<form class="ajax reset grn-form" action="{{ $grn ? route('admin.garments.grns.update', $grn->id) : route('admin.garments.grns.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($grn)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $grn ? __('Edit GRN') : __('Add Goods Received Note') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('GRN Number') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="grn_number" value="{{ old('grn_number', $grn?->grn_number) }}" placeholder="{{ __('e.g. GRN-2026-001') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Material') }} <span class="required">*</span></label>
                        <select class="form-control" name="material_id" required>
                            <option value="">{{ __('Select Material') }}</option>
                            @foreach($materials as $materialOption)
                                <option value="{{ $materialOption->id }}" @selected(old('material_id', $grn?->material_id) == $materialOption->id)>
                                    {{ $materialOption->item_code }} - {{ $materialOption->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Received Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="received_date" value="{{ old('received_date', $grn?->received_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Supplier Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="supplier_name" value="{{ old('supplier_name', $grn?->supplier_name) }}" placeholder="{{ __('Enter supplier name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Purchase Order') }}</label>
                        <select class="form-control" name="purchase_order_id">
                            <option value="">{{ __('Not linked') }}</option>
                            @foreach($purchaseOrders as $purchaseOrder)
                                <option value="{{ $purchaseOrder->id }}" @selected(old('purchase_order_id', $grn?->purchase_order_id) == $purchaseOrder->id)>
                                    {{ $purchaseOrder->po_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Ordered Qty') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="ordered_quantity" value="{{ old('ordered_quantity', $grn?->ordered_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Received Qty') }} <span class="required">*</span></label>
                        <input type="number" min="0.0001" step="0.0001" class="form-control grn-quantity" id="grn-received-quantity" name="received_quantity" value="{{ old('received_quantity', $grn?->received_quantity ?? 0) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Rejected Qty') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control grn-quantity" id="grn-rejected-quantity" name="rejected_quantity" value="{{ old('rejected_quantity', $grn?->rejected_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Unit Cost') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="unit_cost" value="{{ old('unit_cost', $grn?->unit_cost ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentGrnStatuses() as $statusValue => $statusData)
                                <option value="{{ $statusValue }}" @selected(old('status', $grn?->status ?? GARMENT_GRN_STATUS_RECEIVED) == $statusValue)>
                                    {{ __($statusData[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Purchase Reference') }}</label>
                        <input type="text" class="form-control" name="purchase_reference" value="{{ old('purchase_reference', $grn?->purchase_reference) }}" placeholder="{{ __('PO or invoice reference') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add receiving or quality notes...') }}">{{ old('notes', $grn?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $grn ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
