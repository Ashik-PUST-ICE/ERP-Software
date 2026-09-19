<form class="ajax reset" action="{{ $entry ? route('admin.garments.accounting.update', $entry->id) : route('admin.garments.accounting.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($entry)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $entry ? __('Edit Accounting Entry') : __('Add Accounting Entry') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }}</label>
                        <select class="form-control" name="order_id">
                            <option value="">{{ __('Not linked') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $entry?->order_id) == $order->id)>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Entry Type') }} <span class="required">*</span></label>
                        <select class="form-control" name="entry_type" required>
                            @foreach(garmentAccountingTypes() as $value => $label)
                                <option value="{{ $value }}" @selected(old('entry_type', $entry?->entry_type) == $value)>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Account Code') }} <span class="required">*</span></label>
                        <input class="form-control" name="account_code" value="{{ old('account_code', $entry?->account_code) }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Account Name') }} <span class="required">*</span></label>
                        <input class="form-control" name="account_name" value="{{ old('account_name', $entry?->account_name) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Entry Date') }} <span class="required">*</span></label>
                        <input type="date" class="form-control" name="entry_date" value="{{ old('entry_date', $entry?->entry_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Debit') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="debit" value="{{ old('debit', $entry?->debit ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Credit') }}</label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="credit" value="{{ old('credit', $entry?->credit ?? 0) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Reference') }}</label>
                        <input class="form-control" name="reference" value="{{ old('reference', $entry?->reference) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentAccountingStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $entry?->status ?? GARMENT_ACCOUNTING_ENTRY_DRAFT) == $value)>{{ __($status[0]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description', $entry?->description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $entry ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
