<form class="ajax reset" action="{{ $production ? route('admin.garments.sewing.update', $production->id) : route('admin.garments.sewing.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($production)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $production ? __('Edit Sewing Production') : __('Add Sewing Production') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value="">{{ __('Select Order') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $production?->order_id) == $order->id)>
                                    {{ $order->order_number }} | {{ $order->style?->style_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label class="form-label">{{ __('Line Name') }} <span class="required">*</span></label>
                        <input class="form-control" name="line_name" value="{{ old('line_name', $production?->line_name) }}" placeholder="{{ __('e.g. Line 01') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Production Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="production_date" value="{{ old('production_date', $production?->production_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentSewingStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $production?->status ?? GARMENT_SEWING_STATUS_RUNNING) == $value)>
                                    {{ __($status[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('WIP Quantity') }}</label>
                        <input type="number" min="0" class="form-control" name="wip_quantity" value="{{ old('wip_quantity', $production?->wip_quantity ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Daily Target') }}</label>
                        <input type="number" min="0" class="form-control" name="daily_target" value="{{ old('daily_target', $production?->daily_target ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Hourly Target') }}</label>
                        <input type="number" min="0" class="form-control" name="hourly_target" value="{{ old('hourly_target', $production?->hourly_target ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Hourly Output') }}</label>
                        <input type="number" min="0" class="form-control" name="hourly_output" value="{{ old('hourly_output', $production?->hourly_output ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{ __('Total Output') }}</label>
                        <input type="number" min="0" class="form-control" name="total_output" value="{{ old('total_output', $production?->total_output ?? 0) }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Sewing production notes...') }}">{{ old('notes', $production?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $production ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
