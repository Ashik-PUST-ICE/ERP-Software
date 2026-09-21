<form class="ajax reset" action="{{ $buyer ? route('admin.garments.buyers.update', $buyer->id) : route('admin.garments.buyers.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($buyer)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $buyer ? __('Edit Buyer') : __('Add Buyer') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Buyer Code') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="buyer_code" value="{{ old('buyer_code', $buyer?->buyer_code) }}" placeholder="{{ __('e.g. BUY-001') }}" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Company Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $buyer?->company_name) }}" placeholder="{{ __('Enter buyer company name') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Country') }}</label>
                        <input type="text" class="form-control" name="country" value="{{ old('country', $buyer?->country) }}" placeholder="{{ __('e.g. United States') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Currency') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="currency" value="{{ old('currency', $buyer?->currency ?? 'USD') }}" placeholder="{{ __('e.g. USD') }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ STATUS_ACTIVE }}" @selected(($buyer?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE)>{{ __('Active') }}</option>
                            <option value="{{ STATUS_DEACTIVATE }}" @selected(($buyer?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE)>{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Contact Person') }}</label>
                        <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $buyer?->contact_person) }}" placeholder="{{ __('Primary contact name') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $buyer?->email) }}" placeholder="{{ __('name@company.com') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Phone Number') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $buyer?->phone) }}" placeholder="{{ __('Contact phone number') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Payment Terms') }}</label>
                        <input type="text" class="form-control" name="payment_terms" value="{{ old('payment_terms', $buyer?->payment_terms) }}" placeholder="{{ __('e.g. 60 days after shipment') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Office Address') }}</label>
                        <textarea class="form-control" name="office_address" rows="2" placeholder="{{ __('Buyer office address') }}">{{ old('office_address', $buyer?->office_address) }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="{{ __('Internal notes...') }}">{{ old('notes', $buyer?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $buyer ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
