<form class="ajax reset" action="{{ $finishing ? route('admin.garments.finishing.update', $finishing->id) : route('admin.garments.finishing.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($finishing)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $finishing ? __('Edit Finishing Entry') : __('Add Finishing Entry') }}</h4>
                <p class="buyer-modal-subtitle mb-0">{{ __('Track finished, rework and rejected quantities by order.') }}</p>
            </div>
            <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
        </div>
        <div class="primary-form mt-4">
            <div class="row gy-3">
                <div class="col-md-6"><div class="form-group">
                    <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                    <select class="form-control" name="order_id" required><option value="">{{ __('Select Order') }}</option>
                        @foreach($orders as $order)<option value="{{ $order->id }}" @selected(old('order_id', $finishing?->order_id) == $order->id)>{{ $order->order_number }}</option>@endforeach
                    </select>
                </div></div>
                <div class="col-md-6"><div class="form-group">
                    <label class="form-label">{{ __('Finishing Date') }} <span class="required">*</span></label>
                    <input type="date" class="form-control" name="finishing_date" value="{{ old('finishing_date', $finishing?->finishing_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                </div></div>
                @foreach([
                    'received_quantity' => __('Received Qty'),
                    'passed_quantity' => __('Passed Qty'),
                    'rework_quantity' => __('Rework Qty'),
                    'rejected_quantity' => __('Rejected Qty'),
                ] as $field => $label)
                    <div class="col-md-3"><div class="form-group">
                        <label class="form-label">{{ $label }} <span class="required">*</span></label>
                        <input type="number" min="0" class="form-control" name="{{ $field }}" value="{{ old($field, $finishing?->{$field} ?? 0) }}" required>
                    </div></div>
                @endforeach
                <div class="col-md-6"><div class="form-group">
                    <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                    <select class="form-control" name="status" required>@foreach(garmentFinishingStatuses() as $value => $status)<option value="{{ $value }}" @selected(old('status', $finishing?->status ?? GARMENT_FINISHING_STATUS_PENDING) == $value)>{{ __($status[0]) }}</option>@endforeach</select>
                </div></div>
                <div class="col-12"><div class="form-group">
                    <label class="form-label">{{ __('Remarks') }}</label>
                    <textarea class="form-control" name="remarks" rows="3">{{ old('remarks', $finishing?->remarks) }}</textarea>
                </div></div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $finishing ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
