@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ $title }}</h2>
</div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">{{ __('Currency Settings') }}</h3>
                    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
                        <i class="fa fa-plus me-2"></i>{{ __('Add Currency') }}
                    </button>
                </div>
            </div>
            <div class="table-waraper">
                <div class="search-input-wrap">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData"
                        placeholder="{{ __('Search By Currency...') }}" />
                </div>
                <div id="currencies-table-container" data-pagination-route="{{ route('super_admin.setting.currencies.index') }}">
                    @include('auto_posts.super_admin.setting.currencies.partials.currencies_table')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal section start -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="{{ route('super_admin.setting.currencies.store') }}" method="post"
                data-handler="currencyHandler">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Add Currency') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="currency_code" class="form-label">{{ __('Currency ISO Code') }}<span
                                            class="required">*</span></label>
                                    <select id="sf-select-currency-add"
                                        class="select form-control wide sf-select-without-search" name="currency_code"
                                        required>
                                        <option value="">{{ __('Select Currency') }}</option>
                                        @foreach(getCurrency() as $code => $currencyItem)
                                        <option value="{{$code}}">{{ $currencyItem }} ({{$code}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="symbol" class="form-label">{{ __('Symbol') }}<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" name="symbol" id="symbol"
                                        placeholder="{{ __('Enter currency symbol') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="currency_placement"
                                        class="form-label">{{ __('Currency Placement') }}<span
                                            class="required">*</span></label>
                                    <select class="select form-control wide sf-select-without-search"
                                        name="currency_placement" id="currency_placement" required>
                                        <option value="">{{ __('Select Placement') }}</option>
                                        <option value="before">{{ __('Before Amount') }}</option>
                                        <option value="after">{{ __('After Amount') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            name="current_currency" role="switch" id="flexCheckChecked">
                                        <label class="form-check-label" for="flexCheckChecked">
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
                        <button type="submit" class="primary-btn">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Modal section end -->

<!-- Edit Modal section start -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="{{ route('super_admin.setting.currencies.update', 0) }}" method="post"
                id="edit-currency-form" data-handler="currencyHandler">
                @csrf
                @method('PATCH')
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Edit Currency') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="currency_code-edit"
                                        class="form-label">{{ __('Currency ISO Code') }}<span
                                            class="required">*</span></label>
                                    <select id="sf-select-currency-edit"
                                        class="select form-control wide sf-select-without-search" name="currency_code"
                                        required>
                                        <option value="">{{ __('Select Currency') }}</option>
                                        @foreach(getCurrency() as $code => $currencyItem)
                                        <option value="{{$code}}">{{ $currencyItem }} ({{$code}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="symbol-edit" class="form-label">{{ __('Symbol') }}<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" name="symbol" id="symbol-edit"
                                        placeholder="{{ __('Enter currency symbol') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="currency_placement-edit"
                                        class="form-label">{{ __('Currency Placement') }}<span
                                            class="required">*</span></label>
                                    <select class="select form-control wide sf-select-without-search"
                                        name="currency_placement" id="currency_placement-edit" required>
                                        <option value="">{{ __('Select Placement') }}</option>
                                        <option value="before">{{ __('Before Amount') }}</option>
                                        <option value="after">{{ __('After Amount') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            name="current_currency" role="switch" id="flexCheckChecked-edit">
                                        <label class="form-check-label" for="flexCheckChecked-edit">
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
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal section end -->
@endsection

@push('script')
<script src="{{ asset('admin/js/common-pagination.js') }}"></script>
<script src="{{ asset('admin/js/currencies.js') }}"></script>
@endpush