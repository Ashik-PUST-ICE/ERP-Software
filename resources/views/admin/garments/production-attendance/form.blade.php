<form class="ajax reset" action="{{ $attendance ? route('admin.garments.production-attendance.update', $attendance->id) : route('admin.garments.production-attendance.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($attendance)
        @method('put')
    @endif

    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $attendance ? __('Edit Production Attendance') : __('Add Production Attendance') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Employee') }} <span class="required">*</span></label>
                        <select class="form-control" name="employee_id" required>
                            <option value="">{{ __('Select Employee') }}</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}" @selected(old('employee_id', $attendance?->employee_id) == $employee->id)>
                                    {{ $employee->full_name }} ({{ $employee->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Production Line') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="line_name" value="{{ old('line_name', $attendance?->line_name) }}" placeholder="{{ __('e.g. Line 01 / Sewing A') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }}</label>
                        <select class="form-control" name="order_id">
                            <option value="">{{ __('Not linked') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $attendance?->order_id) == $order->id)>
                                    {{ $order->order_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="attendance_date" value="{{ old('attendance_date', $attendance?->attendance_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="1" @selected(old('status', $attendance?->status ?? 1) == 1)>{{ __('Present') }}</option>
                            <option value="2" @selected(old('status', $attendance?->status) == 2)>{{ __('Absent') }}</option>
                            <option value="3" @selected(old('status', $attendance?->status) == 3)>{{ __('Leave') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Production Qty') }}</label>
                        <input type="number" min="0" class="form-control" name="production_quantity" value="{{ old('production_quantity', $attendance?->production_quantity ?? 0) }}" placeholder="{{ __('0') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Working Hours') }}</label>
                        <input type="number" min="0" max="24" step="0.01" class="form-control" name="working_hours" value="{{ old('working_hours', $attendance?->working_hours ?? 8) }}" placeholder="{{ __('8') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Short description...') }}">{{ old('notes', $attendance?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $attendance ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
