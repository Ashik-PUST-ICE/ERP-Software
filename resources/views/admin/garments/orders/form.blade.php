<form class="ajax reset" action="{{ $order ? route('admin.garments.orders.update', $order->id) : route('admin.garments.orders.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($order)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $order ? __('Edit Order') : __('Add Order') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Buyer') }} <span class="required">*</span></label>
                        <select class="form-control" name="buyer_id" required>
                            <option value="">{{ __('Select Buyer') }}</option>
                            @foreach($buyers as $buyerOption)
                                <option value="{{ $buyerOption->id }}" @selected(old('buyer_id', $order?->buyer_id) == $buyerOption->id)>
                                    {{ $buyerOption->company_name }} ({{ $buyerOption->buyer_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Style') }} <span class="required">*</span></label>
                        <select class="form-control" name="style_id" required>
                            <option value="">{{ __('Select Style') }}</option>
                            @foreach($styles as $styleOption)
                                <option value="{{ $styleOption->id }}" @selected(old('style_id', $order?->style_id) == $styleOption->id)>
                                    {{ $styleOption->style_code }} - {{ $styleOption->style_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order / PO No.') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="order_number" value="{{ old('order_number', $order?->order_number) }}" placeholder="{{ __('e.g. PO-1001') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Quantity') }} <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="quantity" value="{{ old('quantity', $order?->quantity) }}" placeholder="{{ __('Units') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Unit Price') }} <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="unit_price" value="{{ old('unit_price', $order?->unit_price ?? 0) }}" placeholder="{{ __('FOB price') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="order_date" value="{{ old('order_date', $order?->order_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Delivery Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="delivery_date" value="{{ old('delivery_date', $order?->delivery_date?->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentOrderStatuses() as $statusValue => $statusData)
                                <option value="{{ $statusValue }}" @selected(old('status', $order?->status ?? GARMENT_ORDER_STATUS_PENDING) == $statusValue)>
                                    {{ __($statusData[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Product Description') }}</label>
                        <textarea class="form-control" name="product_description" rows="2" placeholder="{{ __('Describe garment type, color, size range...') }}">{{ old('product_description', $order?->product_description) }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Internal Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add notes for merchandising or production...') }}">{{ old('notes', $order?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $order ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
