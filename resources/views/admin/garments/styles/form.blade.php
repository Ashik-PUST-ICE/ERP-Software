<form class="ajax reset" action="{{ $style ? route('admin.garments.styles.update', $style->id) : route('admin.garments.styles.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($style)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $style ? __('Edit Style') : __('Add Style') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Style Code') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="style_code" value="{{ old('style_code', $style?->style_code) }}" placeholder="{{ __('e.g. ST-2026-001') }}" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Style Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="style_name" value="{{ old('style_name', $style?->style_name) }}" placeholder="{{ __('e.g. Basic Polo Shirt') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Product Type') }}</label>
                        <input type="text" class="form-control" name="product_type" value="{{ old('product_type', $style?->product_type) }}" placeholder="{{ __('e.g. Knit, Woven, Denim') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Season') }}</label>
                        <input type="text" class="form-control" name="season" value="{{ old('season', $style?->season) }}" placeholder="{{ __('e.g. SS26') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ STATUS_ACTIVE }}" @selected(($style?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE)>{{ __('Active') }}</option>
                            <option value="{{ STATUS_DEACTIVATE }}" @selected(($style?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE)>{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="{{ __('Describe the garment style and specifications') }}">{{ old('description', $style?->description) }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add internal notes...') }}">{{ old('notes', $style?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $style ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
