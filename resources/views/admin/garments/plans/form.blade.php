<form class="ajax reset" action="{{ $plan ? route('admin.garments.plans.update', $plan->id) : route('admin.garments.plans.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($plan) @method('put') @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-3"><div><h4 class="fs-20 fw-600 lh-28 text-1b1c17 mb-1">{{ $plan ? __('Edit Production Plan') : __('Add Production Plan') }}</h4><p class="buyer-modal-subtitle mb-0">{{ __('Allocate an order to a line and define its production capacity.') }}</p></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        <div class="primary-form mt-4">
            <div class="plan-form-section"><h5 class="plan-form-section-title"><i class="fa-solid fa-industry"></i>{{ __('Line Allocation') }}</h5><div class="row gy-3">
                <div class="col-md-7"><div class="form-group"><label class="form-label">{{ __('Order') }} <span class="required">*</span></label><select class="form-control" name="order_id" required><option value="">{{ __('Select Order') }}</option>@foreach($orders as $orderOption)<option value="{{ $orderOption->id }}" @selected(old('order_id', $plan?->order_id) == $orderOption->id)>{{ $orderOption->order_number }} | {{ $orderOption->style?->style_code }} | {{ $orderOption->buyer?->company_name }}</option>@endforeach</select></div></div>
                <div class="col-md-5"><div class="form-group"><label class="form-label">{{ __('Line Name') }} <span class="required">*</span></label><input type="text" class="form-control" name="line_name" value="{{ old('line_name', $plan?->line_name) }}" placeholder="{{ __('e.g. Sewing Line 01') }}" required></div></div>
            </div></div>

            <div class="plan-form-section"><h5 class="plan-form-section-title"><i class="fa-solid fa-chart-column"></i>{{ __('Quantity & Capacity') }}</h5><div class="row gy-3">
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Planned Quantity') }} <span class="required">*</span></label><input type="number" min="1" class="form-control" name="planned_quantity" value="{{ old('planned_quantity', $plan?->planned_quantity) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Daily Target') }} <span class="required">*</span></label><input type="number" min="1" class="form-control" name="daily_target" value="{{ old('daily_target', $plan?->daily_target) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Capacity / Day') }}</label><input type="number" min="0" class="form-control" name="capacity_per_day" value="{{ old('capacity_per_day', $plan?->capacity_per_day ?? 0) }}"></div></div>
            </div></div>

            <div class="plan-form-section"><h5 class="plan-form-section-title"><i class="fa-solid fa-calendar-days"></i>{{ __('Planning Timeline') }}</h5><div class="row gy-3">
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Start Date') }} <span class="required">*</span></label><input type="date" class="form-control" name="start_date" value="{{ old('start_date', $plan?->start_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('End Date') }} <span class="required">*</span></label><input type="date" class="form-control" name="end_date" value="{{ old('end_date', $plan?->end_date?->format('Y-m-d')) }}" required></div></div>
                <div class="col-md-4"><div class="form-group"><label class="form-label">{{ __('Status') }} <span class="required">*</span></label><select class="form-control" name="status" required>@foreach(garmentPlanStatuses() as $statusValue => $statusData)<option value="{{ $statusValue }}" @selected(old('status', $plan?->status ?? GARMENT_PLAN_STATUS_DRAFT) == $statusValue)>{{ __($statusData[0]) }}</option>@endforeach</select></div></div>
                <div class="col-12"><div class="form-group"><label class="form-label">{{ __('Notes') }}</label><textarea class="form-control" name="notes" rows="3" placeholder="{{ __('Add line allocation or capacity notes') }}">{{ old('notes', $plan?->notes) }}</textarea></div></div>
            </div></div>
        </div>
        <div class="plan-modal-footer"><button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button><button type="submit" class="primary-btn"><i class="fa-solid fa-check me-2"></i>{{ $plan ? __('Update Plan') : __('Save Plan') }}</button></div>
    </div>
</form>
