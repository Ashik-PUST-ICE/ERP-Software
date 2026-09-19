<form class="ajax reset" action="{{ $cutting ? route('admin.garments.cutting.update', $cutting->id) : route('admin.garments.cutting.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($cutting)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $cutting ? __('Edit Cutting Record') : __('Add Cutting Record') }}</h4>
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
                                <option value="{{ $order->id }}" @selected(old('order_id', $cutting?->order_id) == $order->id)>
                                    {{ $order->order_number }} | {{ $order->style?->style_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Fabric Material') }}</label>
                        <select class="form-control" name="material_id">
                            <option value="">{{ __('Select Material') }}</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" @selected(old('material_id', $cutting?->material_id) == $material->id)>
                                    {{ $material->item_code }} - {{ $material->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Marker Number') }}</label>
                        <input class="form-control" name="marker_number" value="{{ old('marker_number', $cutting?->marker_number) }}" placeholder="{{ __('e.g. MK-001') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Cutting Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="cutting_date" value="{{ old('cutting_date', $cutting?->cutting_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentCuttingStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $cutting?->status ?? GARMENT_CUTTING_STATUS_PLANNED) == $value)>
                                    {{ __($status[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Fabric Consumption') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="fabric_consumption" value="{{ old('fabric_consumption', $cutting?->fabric_consumption ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Marker Efficiency %') }}</label>
                        <input type="number" min="0" max="100" step="0.0001" class="form-control" name="marker_efficiency" value="{{ old('marker_efficiency', $cutting?->marker_efficiency ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Planned Cut Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="planned_cut_quantity" value="{{ old('planned_cut_quantity', $cutting?->planned_cut_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Cut Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="cut_quantity" value="{{ old('cut_quantity', $cutting?->cut_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Panel Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="panel_quantity" value="{{ old('panel_quantity', $cutting?->panel_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Cutting notes...') }}">{{ old('notes', $cutting?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $cutting ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
