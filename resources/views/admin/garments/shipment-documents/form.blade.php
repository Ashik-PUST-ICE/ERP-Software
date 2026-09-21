<form class="ajax reset" action="{{ $document ? route('admin.garments.shipment-documents.update', $document->id) : route('admin.garments.shipment-documents.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($document)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $document ? __('Edit Shipment Document') : __('Add Shipment Document') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value="">{{ __('Select Order') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $document?->order_id) == $order->id)>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Document Type') }} <span class="required">*</span></label>
                        <select class="form-control" name="document_type" required>
                            @foreach(garmentShipmentDocumentTypes() as $value => $label)
                                <option value="{{ $value }}" @selected(old('document_type', $document?->document_type) == $value)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Document Number') }} <span class="required">*</span></label>
                        <input class="form-control" name="document_number" value="{{ old('document_number', $document?->document_number) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Document Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="document_date" value="{{ old('document_date', $document?->document_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Shipper') }}</label>
                        <input class="form-control" name="shipper" value="{{ old('shipper', $document?->shipper) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Consignee') }}</label>
                        <input class="form-control" name="consignee" value="{{ old('consignee', $document?->consignee) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Port of Loading') }}</label>
                        <input class="form-control" name="port_of_loading" value="{{ old('port_of_loading', $document?->port_of_loading) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Port of Discharge') }}</label>
                        <input class="form-control" name="port_of_discharge" value="{{ old('port_of_discharge', $document?->port_of_discharge) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Carrier') }}</label>
                        <input class="form-control" name="carrier" value="{{ old('carrier', $document?->carrier) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Shipment Date') }}</label>
                        <input type="date" class="form-control" name="shipment_date" value="{{ old('shipment_date', $document?->shipment_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentShipmentStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $document?->status ?? GARMENT_SHIPMENT_STATUS_DRAFT) == $value)>{{ __($status[0]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="3">{{ old('notes', $document?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $document ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
