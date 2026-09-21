<form class="ajax reset" action="{{ $issue ? route('admin.garments.issues.update', $issue->id) : route('admin.garments.issues.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($issue)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $issue ? __('Edit Store Issue') : __('Issue Material') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Issue Number') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="issue_number" value="{{ old('issue_number', $issue?->issue_number) }}" placeholder="{{ __('e.g. ISS-2026-001') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Material') }} <span class="required">*</span></label>
                        <select class="form-control" name="material_id" required>
                            <option value="">{{ __('Select Material') }}</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" @selected(old('material_id', $issue?->material_id) == $material->id)>
                                    {{ $material->item_code }} - {{ $material->item_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }}</label>
                        <select class="form-control" name="order_id">
                            <option value="">{{ __('General Store / No Order') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $issue?->order_id) == $order->id)>
                                    {{ $order->order_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Section') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="section" value="{{ old('section', $issue?->section) }}" placeholder="{{ __('e.g. Cutting, Sewing, Finishing') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Line Name') }}</label>
                        <input type="text" class="form-control" name="line_name" value="{{ old('line_name', $issue?->line_name) }}" placeholder="{{ __('e.g. Line 01') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Issue Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="issue_date" value="{{ old('issue_date', $issue?->issue_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Issued Qty') }} <span class="required">*</span></label>
                        <input type="number" min="0.0001" step="0.0001" class="form-control issue-qty" id="issued-quantity" name="issued_quantity" value="{{ old('issued_quantity', $issue?->issued_quantity ?? 0) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Returned Qty') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control issue-qty" id="returned-quantity" name="returned_quantity" value="{{ old('returned_quantity', $issue?->returned_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentIssueStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $issue?->status ?? GARMENT_ISSUE_STATUS_ISSUED) == $value)>
                                    {{ __($status[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add issue or return notes...') }}">{{ old('notes', $issue?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $issue ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
