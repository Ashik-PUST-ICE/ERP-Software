<form class="ajax reset" action="{{ $qc?route('admin.garments.inline-qc.update',$qc->id):route('admin.garments.inline-qc.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($qc)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $qc?__('Edit Inline QC'):__('Add Inline QC') }}</h4>
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
                                <option value="{{ $order->id }}" @selected(old('order_id',$qc?->order_id)==$order->id)>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Inspection Point') }} <span class="required">*</span></label>
                        <input class="form-control" name="inspection_point" value="{{ old('inspection_point',$qc?->inspection_point) }}" placeholder="{{ __('Sewing line, operation or checkpoint') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="inspection_date" value="{{ old('inspection_date',$qc?->inspection_date?->format('Y-m-d')??now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Checked Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="checked_quantity" value="{{ old('checked_quantity',$qc?->checked_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Passed Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="passed_quantity" value="{{ old('passed_quantity',$qc?->passed_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Defect Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="defect_quantity" value="{{ old('defect_quantity',$qc?->defect_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Inspector') }}</label>
                        <input class="form-control" name="inspector_name" value="{{ old('inspector_name',$qc?->inspector_name) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentQcStatuses() as $value=>$status)
                                <option value="{{ $value }}" @selected(old('status',$qc?->status??GARMENT_QC_STATUS_PENDING)==$value)>{{ __($status[0]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="3">{{ old('notes',$qc?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $qc?__('Update'):__('Save') }}</button>
        </div>
    </div>
</form>
