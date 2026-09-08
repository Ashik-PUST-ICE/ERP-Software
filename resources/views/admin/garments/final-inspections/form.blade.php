<form class="ajax reset" action="{{ $inspection?route('admin.garments.final-inspections.update',$inspection->id):route('admin.garments.final-inspections.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($inspection)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $inspection?__('Edit Final Inspection'):__('Add Final Inspection') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form mt-4">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value="">{{ __('Select Order') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id',$inspection?->order_id)==$order->id)>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Inspection Lot') }} <span class="required">*</span></label>
                        <input class="form-control" name="inspection_lot" value="{{ old('inspection_lot',$inspection?->inspection_lot) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="inspection_date" value="{{ old('inspection_date',$inspection?->inspection_date?->format('Y-m-d')??now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Lot Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="lot_quantity" value="{{ old('lot_quantity',$inspection?->lot_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Sample Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="sample_quantity" value="{{ old('sample_quantity',$inspection?->sample_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('AQL Level') }}</label>
                        <input class="form-control" name="aql_level" value="{{ old('aql_level',$inspection?->aql_level) }}" placeholder="{{ __('e.g. 2.5') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Defect Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="defect_quantity" value="{{ old('defect_quantity',$inspection?->defect_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Rejected Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="rejected_quantity" value="{{ old('rejected_quantity',$inspection?->rejected_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Result') }} <span class="required">*</span></label>
                        <select class="form-control" name="result" required>
                            @foreach(garmentInspectionResults() as $value=>$status)
                                <option value="{{ $value }}" @selected(old('result',$inspection?->result??GARMENT_INSPECTION_RESULT_PENDING)==$value)>{{ __($status[0]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Inspector') }}</label>
                        <input class="form-control" name="inspector_name" value="{{ old('inspector_name',$inspection?->inspector_name) }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="3">{{ old('notes',$inspection?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $inspection?__('Update'):__('Save') }}</button>
        </div>
    </div>
</form>
