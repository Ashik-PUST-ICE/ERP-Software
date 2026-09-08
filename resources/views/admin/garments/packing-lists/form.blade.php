<form class="ajax reset" action="{{ $packing ? route('admin.garments.packing-lists.update', $packing->id) : route('admin.garments.packing-lists.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($packing)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $packing ? __('Edit Packing Row') : __('Add Packing Row') }}</h4>
                <p class="buyer-modal-subtitle mb-0">{{ __('Record carton-wise packing details for an order.') }}</p>
            </div>
            <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        </div>
        <div class="primary-form mt-4 packing-form">
            <div class="packing-form-section">
                <div class="packing-form-section-heading"><span class="packing-form-icon"><i class="fa-solid fa-box-open"></i></span><div><h5>{{ __('Packing Reference') }}</h5><p>{{ __('Select the order and record when this carton was packed.') }}</p></div></div>
                <div class="row gy-3">
                <div class="col-md-6"><div class="form-group">
                    <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                    <select class="form-control" name="order_id" required><option value="">{{ __('Select Order') }}</option>
                        @foreach($orders as $order)<option value="{{ $order->id }}" @selected(old('order_id', $packing?->order_id) == $order->id)>{{ $order->order_number }}</option>@endforeach
                    </select>
                </div></div>
                <div class="col-md-6"><div class="form-group">
                    <label class="form-label">{{ __('Packing Date') }} <span class="required">*</span></label>
                    <input type="date" class="form-control" name="packing_date" value="{{ old('packing_date', $packing?->packing_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                </div></div>
                </div>
            </div>
            <div class="packing-form-section">
                <div class="packing-form-section-heading"><span class="packing-form-icon"><i class="fa-solid fa-shirt"></i></span><div><h5>{{ __('Carton Contents') }}</h5><p>{{ __('Identify the carton contents by number, color, size and quantity.') }}</p></div></div>
                <div class="row gy-3">
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Carton Number') }} <span class="required">*</span></label><input class="form-control" name="carton_number" value="{{ old('carton_number', $packing?->carton_number) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Color') }}</label><input class="form-control" name="color" value="{{ old('color', $packing?->color) }}"></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Size') }}</label><input class="form-control" name="size" value="{{ old('size', $packing?->size) }}"></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Quantity') }} <span class="required">*</span></label><input type="number" min="1" class="form-control" name="quantity" value="{{ old('quantity', $packing?->quantity ?? 1) }}" required></div></div>
                </div>
            </div>
            <div class="packing-form-section">
                <div class="packing-form-section-heading"><span class="packing-form-icon"><i class="fa-solid fa-weight-hanging"></i></span><div><h5>{{ __('Weight & Dimensions') }}</h5><p>{{ __('Use the shipping measurements for the completed carton.') }}</p></div></div>
                <div class="row gy-3">
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Net Weight') }} <span class="required">*</span></label><input type="number" min="0" step="0.001" class="form-control" name="net_weight" value="{{ old('net_weight', $packing?->net_weight ?? 0) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Gross Weight') }} <span class="required">*</span></label><input type="number" min="0" step="0.001" class="form-control" name="gross_weight" value="{{ old('gross_weight', $packing?->gross_weight ?? 0) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Length') }}</label><input type="number" min="0" step="0.01" class="form-control" name="carton_length" value="{{ old('carton_length', $packing?->carton_length) }}"></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Width') }}</label><input type="number" min="0" step="0.01" class="form-control" name="carton_width" value="{{ old('carton_width', $packing?->carton_width) }}"></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Height') }}</label><input type="number" min="0" step="0.01" class="form-control" name="carton_height" value="{{ old('carton_height', $packing?->carton_height) }}"></div></div>
                </div>
            </div>
            <div class="packing-form-section">
                <div class="packing-form-section-heading"><span class="packing-form-icon"><i class="fa-solid fa-clipboard-check"></i></span><div><h5>{{ __('Status & Notes') }}</h5><p>{{ __('Keep packing progress and operational notes up to date.') }}</p></div></div>
                <div class="row gy-3">
                <div class="col-md-6"><div class="form-group">
                    <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                    <select class="form-control" name="status" required>@foreach(garmentPackingStatuses() as $value => $status)<option value="{{ $value }}" @selected(old('status', $packing?->status ?? GARMENT_PACKING_STATUS_DRAFT) == $value)>{{ __($status[0]) }}</option>@endforeach</select>
                </div></div>
                <div class="col-12"><div class="form-group"><label class="form-label">{{ __('Notes') }}</label><textarea class="form-control" name="notes" rows="3">{{ old('notes', $packing?->notes) }}</textarea></div></div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $packing ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
