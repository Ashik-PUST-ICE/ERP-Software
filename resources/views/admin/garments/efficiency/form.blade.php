<form class="ajax reset" action="{{ $efficiency ? route('admin.garments.efficiency.update', $efficiency->id) : route('admin.garments.efficiency.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($efficiency)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $efficiency ? __('Edit Efficiency Record') : __('Add Efficiency Record') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Operator') }}</label>
                        <select class="form-control" name="employee_id">
                            <option value="">{{ __('Machine / Unassigned') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(old('employee_id', $efficiency?->employee_id) == $employee->id)>
                                    {{ $employee->full_name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }}</label>
                        <select class="form-control" name="order_id">
                            <option value="">{{ __('No Order') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $efficiency?->order_id) == $order->id)>
                                    {{ $order->order_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Machine Name') }}</label>
                        <input class="form-control" name="machine_name" value="{{ old('machine_name', $efficiency?->machine_name) }}" placeholder="{{ __('e.g. SNLS-001') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Line Name') }}</label>
                        <input class="form-control" name="line_name" value="{{ old('line_name', $efficiency?->line_name) }}" placeholder="{{ __('e.g. Line 01') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Work Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="work_date" value="{{ old('work_date', $efficiency?->work_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Working Minutes') }}</label>
                        <input type="number" min="0" class="form-control" name="working_minutes" value="{{ old('working_minutes', $efficiency?->working_minutes ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Target Output') }}</label>
                        <input type="number" min="0" class="form-control" name="target_output" value="{{ old('target_output', $efficiency?->target_output ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Actual Output') }}</label>
                        <input type="number" min="0" class="form-control" name="actual_output" value="{{ old('actual_output', $efficiency?->actual_output ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentEfficiencyStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $efficiency?->status ?? GARMENT_EFFICIENCY_STATUS_RECORDED) == $value)>
                                    {{ __($status[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Efficiency notes...') }}">{{ old('notes', $efficiency?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $efficiency ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
