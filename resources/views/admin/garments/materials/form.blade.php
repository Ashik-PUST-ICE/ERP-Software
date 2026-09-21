<form class="ajax reset" action="{{ $material ? route('admin.garments.materials.update', $material->id) : route('admin.garments.materials.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($material)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $material ? __('Edit Material') : __('Add Material') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Item Code') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="item_code" value="{{ old('item_code', $material?->item_code) }}" placeholder="{{ __('e.g. FAB-001') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Barcode') }}</label>
                        <input type="text" class="form-control" name="barcode" value="{{ old('barcode', $material?->barcode) }}" placeholder="{{ __('Auto-generated if blank') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ STATUS_ACTIVE }}" @selected(($material?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE)>{{ __('Active') }}</option>
                            <option value="{{ STATUS_DEACTIVATE }}" @selected(($material?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE)>{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Item Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="item_name" value="{{ old('item_name', $material?->item_name) }}" placeholder="{{ __('e.g. Cotton Jersey 180 GSM') }}" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">{{ __('Category') }} <span class="required">*</span></label>
                        <select class="form-control" name="category" required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach(garmentMaterialCategories() as $categoryValue => $categoryLabel)
                                <option value="{{ $categoryValue }}" @selected(old('category', $material?->category) == $categoryValue)>
                                    {{ __($categoryLabel) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">{{ __('Unit') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="unit" value="{{ old('unit', $material?->unit ?? 'pcs') }}" placeholder="{{ __('e.g. kg, meter, pcs') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Opening Stock') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="opening_stock" value="{{ old('opening_stock', $material?->opening_stock ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Current Stock') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="current_stock" value="{{ old('current_stock', $material?->current_stock ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Reorder Level') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="reorder_level" value="{{ old('reorder_level', $material?->reorder_level ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Warehouse') }}</label>
                        <input type="text" class="form-control" name="warehouse" value="{{ old('warehouse', $material?->warehouse) }}" placeholder="{{ __('e.g. Main Store') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Location / Rack') }}</label>
                        <input type="text" class="form-control" name="location" value="{{ old('location', $material?->location) }}" placeholder="{{ __('e.g. Rack A-03') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add material notes...') }}">{{ old('notes', $material?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $material ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
