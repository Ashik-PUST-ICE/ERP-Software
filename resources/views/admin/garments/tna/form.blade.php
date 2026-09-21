<form class="ajax reset" action="{{ $task ? route('admin.garments.tna.update', $task->id) : route('admin.garments.tna.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($task)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $task ? __('Edit TNA Task') : __('Add TNA Task') }}</h4>
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
                            @foreach($orders as $orderOption)
                                <option value="{{ $orderOption->id }}" @selected(old('order_id', $task?->order_id) == $orderOption->id)>
                                    {{ $orderOption->order_number }} | {{ $orderOption->style?->style_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Responsible Employee') }}</label>
                        <select class="form-control" name="employee_id">
                            <option value="">{{ __('Unassigned') }}</option>
                            @foreach($employees as $employeeOption)
                                <option value="{{ $employeeOption->id }}" @selected(old('employee_id', $task?->employee_id) == $employeeOption->id)>
                                    {{ $employeeOption->full_name }} ({{ $employeeOption->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Task Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="task_name" value="{{ old('task_name', $task?->task_name) }}" placeholder="{{ __('e.g. PP Meeting, Fabric In-house, Shipment') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Task Type') }}</label>
                        <input type="text" class="form-control" name="task_type" value="{{ old('task_type', $task?->task_type) }}" placeholder="{{ __('Merchandising, Production...') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Planned Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="planned_date" value="{{ old('planned_date', $task?->planned_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Actual Date') }}</label>
                        <input type="date" class="form-control" name="actual_date" value="{{ old('actual_date', $task?->actual_date?->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentTnaStatuses() as $statusValue => $statusData)
                                <option value="{{ $statusValue }}" @selected(old('status', $task?->status ?? GARMENT_TNA_STATUS_PENDING) == $statusValue)>
                                    {{ __($statusData[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Add milestone notes or dependencies...') }}">{{ old('notes', $task?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $task ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
