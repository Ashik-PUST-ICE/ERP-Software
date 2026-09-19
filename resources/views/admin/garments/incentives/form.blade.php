<form class="ajax reset" action="{{ $incentive ? route('admin.garments.incentives.update', $incentive->id) : route('admin.garments.incentives.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($incentive)
        @method('put')
    @endif

    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $incentive ? __('Edit Incentive') : __('Add Incentive') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Worker Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="employee_name" value="{{ old('employee_name', $incentive?->employee_name) }}" placeholder="{{ __('e.g. Mohammad Rahim') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }}</label>
                        <select class="form-control" name="order_id">
                            <option value="">{{ __('Not linked') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $incentive?->order_id) == $order->id)>
                                    {{ $order->order_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Production Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="production_date" value="{{ old('production_date', $incentive?->production_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Operation') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="operation" value="{{ old('operation', $incentive?->operation) }}" placeholder="{{ __('e.g. Sewing') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Production Quantity') }} <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="production_quantity" value="{{ old('production_quantity', $incentive?->production_quantity ?? 1) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Piece Rate') }} <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="piece_rate" value="{{ old('piece_rate', $incentive?->piece_rate ?? 0) }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentIncentiveStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $incentive?->status ?? GARMENT_INCENTIVE_STATUS_DRAFT) == $value)>
                                    {{ __($status[0]) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Short description / notes...') }}">{{ old('notes', $incentive?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $incentive ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
