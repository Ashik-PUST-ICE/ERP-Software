<form class="ajax reset" action="{{ $defect?route('admin.garments.defects.update',$defect->id):route('admin.garments.defects.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($defect)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $defect?__('Edit Defect Record'):__('Add Defect Record') }}</h4>
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
                                <option value="{{ $order->id }}" @selected(old('order_id',$defect?->order_id)==$order->id)>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Inline QC Reference') }}</label>
                        <select class="form-control" name="inline_qc_id">
                            <option value="">{{ __('None') }}</option>
                            @foreach($qcs as $qc)
                                <option value="{{ $qc->id }}" @selected(old('inline_qc_id',$defect?->inline_qc_id)==$qc->id)>{{ $qc->inspection_point }} - {{ $qc->inspection_date?->format('d M Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Defect Type') }} <span class="required">*</span></label>
                        <input class="form-control" name="defect_type" value="{{ old('defect_type',$defect?->defect_type) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Section') }} <span class="required">*</span></label>
                        <input class="form-control" name="section" value="{{ old('section',$defect?->section) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Defect Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="defect_quantity" value="{{ old('defect_quantity',$defect?->defect_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Rejected Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="rejected_quantity" value="{{ old('rejected_quantity',$defect?->rejected_quantity??0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Reported Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="reported_date" value="{{ old('reported_date',$defect?->reported_date?->format('Y-m-d')??now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Root Cause') }}</label>
                        <input class="form-control" name="root_cause" value="{{ old('root_cause',$defect?->root_cause) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentDefectStatuses() as $value=>$status)
                                <option value="{{ $value }}" @selected(old('status',$defect?->status??GARMENT_DEFECT_STATUS_OPEN)==$value)>{{ __($status[0]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Corrective Action') }}</label>
                        <textarea class="form-control" name="corrective_action" rows="3">{{ old('corrective_action',$defect?->corrective_action) }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2">{{ old('notes',$defect?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $defect?__('Update'):__('Save') }}</button>
        </div>
    </div>
</form>
