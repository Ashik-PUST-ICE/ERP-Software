<div class="modal-body zModalTwo-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Edit Currency') }}</h4>
        <div class="mClose">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
            </button>
        </div>
    </div>

    <form class="ajax reset" action="{{ route('super_admin.setting.currencies.update', $currency->id) }}" method="post" id="edit-currency-form"
          data-handler="commonResponseForModal">
        @csrf
        @method('PATCH')
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="currency_code-edit" class="form-label">{{ __('Currency ISO Code') }}<span class="required">*</span></label>
                        <select id="sf-select-currency-edit" class="select form-control wide sf-select-without-search" name="currency_code" required>
                            <option value="">{{ __('Select Currency') }}</option>
                            @foreach(getCurrency() as $code => $currencyItem)
                                <option value="{{$code}}" {{ $code == $currency->currency_code ? 'selected' : '' }}>{{ $currencyItem }} ({{$code}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="symbol-edit" class="form-label">{{ __('Symbol') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" name="symbol" id="symbol-edit" placeholder="{{ __('Enter currency symbol') }}" value="{{ $currency->symbol }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="currency_placement-edit" class="form-label">{{ __('Currency Placement') }}<span class="required">*</span></label>
                        <select class="select form-control wide sf-select-without-search" name="currency_placement" id="currency_placement-edit" required>
                            <option value="">{{ __('Select Placement') }}</option>
                            <option value="before" {{ $currency->currency_placement == 'before' ? 'selected' : '' }}>{{ __('Before Amount') }}</option>
                            <option value="after" {{ $currency->currency_placement == 'after' ? 'selected' : '' }}>{{ __('After Amount') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="current_currency"
                                role="switch" id="flexCheckChecked-edit-{{$currency->id}}" {{ $currency->current_currency == STATUS_ACTIVE ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckChecked-edit-{{$currency->id}}">
                                {{ __('Set as Default Currency') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Update') }}</button>
        </div>
    </form>
</div>
