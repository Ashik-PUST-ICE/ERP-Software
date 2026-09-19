<form class="ajax reset" action="{{ $supplier ? route('admin.garments.suppliers.update', $supplier->id) : route('admin.garments.suppliers.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($supplier)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $supplier ? __('Edit Supplier') : __('Add Supplier') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Supplier Code') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="supplier_code" value="{{ old('supplier_code', $supplier?->supplier_code) }}" placeholder="{{ __('e.g. SUP-001') }}" required>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Company Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="company_name" value="{{ old('company_name', $supplier?->company_name) }}" placeholder="{{ __('Enter company name') }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Contact Person') }}</label>
                        <input type="text" class="form-control" name="contact_person" value="{{ old('contact_person', $supplier?->contact_person) }}" placeholder="{{ __('Primary contact') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Category') }}</label>
                        <input type="text" class="form-control" name="category" value="{{ old('category', $supplier?->category) }}" placeholder="{{ __('e.g. Fabric, Trims, Accessories') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Email') }}</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email', $supplier?->email) }}" placeholder="{{ __('supplier@domain.com') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Phone') }}</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone', $supplier?->phone) }}" placeholder="{{ __('Phone number') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ STATUS_ACTIVE }}" @selected(($supplier?->status ?? STATUS_ACTIVE) == STATUS_ACTIVE)>{{ __('Active') }}</option>
                            <option value="{{ STATUS_DEACTIVATE }}" @selected(($supplier?->status ?? STATUS_ACTIVE) == STATUS_DEACTIVATE)>{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Payment Terms') }}</label>
                        <input type="text" class="form-control" name="payment_terms" value="{{ old('payment_terms', $supplier?->payment_terms) }}" placeholder="{{ __('e.g. 30 days LC, Cash') }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Address') }}</label>
                        <textarea class="form-control" name="address" rows="2" placeholder="{{ __('Supplier address...') }}">{{ old('address', $supplier?->address) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ $supplier ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
