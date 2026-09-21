<form class="ajax reset costing-form" action="{{ $costing ? route('admin.garments.costings.update', $costing->id) : route('admin.garments.costings.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($costing)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $costing ? __('Edit Costing') : __('Add Costing') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value="">{{ __('Select Order') }}</option>
                            @foreach($orders as $orderOption)
                                <option value="{{ $orderOption->id }}" @selected(old('order_id', $costing?->order_id) == $orderOption->id)>
                                    {{ $orderOption->order_number }} | {{ $orderOption->style?->style_code }} - {{ $orderOption->buyer?->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentCostingStatuses() as $statusValue => $statusData)
                                <option value="{{ $statusValue }}" @selected(old('status', $costing?->status ?? GARMENT_COSTING_STATUS_DRAFT) == $statusValue)>
                                    {{ __($statusData[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @foreach(['fabric_cost' => 'Fabric Cost', 'trims_cost' => 'Trims Cost', 'accessories_cost' => 'Accessories Cost', 'cm_cost' => 'CM Cost', 'washing_cost' => 'Washing Cost', 'printing_cost' => 'Printing Cost', 'embroidery_cost' => 'Embroidery Cost', 'overhead_cost' => 'Overhead Cost', 'other_cost' => 'Other Cost'] as $field => $label)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ __($label) }}</label>
                            <input type="number" min="0" step="0.0001" class="form-control costing-component" name="{{ $field }}" value="{{ old($field, $costing?->$field ?? 0) }}" data-costing-field="{{ $field }}">
                        </div>
                    </div>
                @endforeach

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Total Cost') }}</label>
                        <input type="text" class="form-control" id="costing-total-display" value="0.0000" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('FOB Price') }} <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="fob_price" id="costing-fob-input" value="{{ old('fob_price', $costing?->fob_price ?? 0) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Expected Profit') }}</label>
                        <input type="text" class="form-control" id="costing-profit-display" value="0.0000" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Margin') }}</label>
                        <input type="text" class="form-control" id="costing-margin-display" value="0.00%" readonly>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add costing assumptions or remarks...') }}">{{ old('notes', $costing?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $costing ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
