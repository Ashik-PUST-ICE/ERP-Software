@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ __('Coupons') }}
@endpush

@section('content')

<div class="section-title">
    <h2 class="title">{{ __('Coupons') }}</h2>
    <button type="button" class="primary-btn" onclick="openCouponModal()">
        <i class="fa fa-plus me-2"></i>{{ __('Add New Coupon') }}
    </button>
</div>
<div class="section-wrap">
    <div class="table-waraper">
        <div class="search-input-wrap">
            <label class="icon" for="searchData">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </label>
            <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search By Name...') }}" />
        </div>
        <input type="hidden" id="coupons-data-route" value="{{ route('super_admin.coupons.data') }}">

        <table class="display primary-table dataTable dtr-inline" id="couponsDataTable">
            <thead>
                <tr>
                    <th class="keep-show">{{ __('SL') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Code') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Amount') }}</th>
                    <th>{{ __('Start Date') }}</th>
                    <th>{{ __('End Date') }}</th>
                    <th>{{ __('Usage Limit') }}</th>
                    <th>{{ __('Used') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="keep-show">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="coupons-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
    </div>
</div>


<!-- Coupon Modal -->
<div class="modal fade primary-modal" id="coupon-modal" tabindex="-1" aria-labelledby="couponModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="couponModalLabel">{{ __('Add New Coupon') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="coupon-form" method="POST" action="{{ route('super_admin.coupons.store') }}"
                data-store="{{ route('super_admin.coupons.store') }}"
                data-update="{{ route('super_admin.coupons.update', ':id') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <div class="modal-body">
                    <div class="primary-form">
                        <div class="row gy-4">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">{{ __('Coupon Name') }}<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="{{ __('Enter coupon name') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="code" class="form-label">{{ __('Coupon Code') }}<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="code" name="code"
                                        placeholder="{{ __('Enter coupon code') }}" required
                                        style="text-transform: uppercase;">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="discount_type" class="form-label">{{ __('Discount Type') }}<span
                                            class="required">*</span></label>
                                    <select class="select form-control wide sf-select-without-search" id="discount_type"
                                        name="discount_type" required>
                                        <option value="fixed">{{ __('Fixed Amount') }}</option>
                                        <option value="percentage">{{ __('Percentage') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">{{ __('Discount Amount') }}<span
                                            class="required">*</span></label>
                                    <input type="number" class="form-control" id="amount" name="amount"
                                        placeholder="{{ __('Enter discount amount') }}" step="0.01" min="0" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">{{ __('Start Date') }}<span
                                            class="required">*</span></label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">{{ __('End Date') }}<span
                                            class="required">*</span></label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="minimum_spend" class="form-label">{{ __('Minimum Spend') }}</label>
                                    <input type="number" class="form-control" id="minimum_spend" name="minimum_spend"
                                        placeholder="{{ __('Enter minimum spend') }}" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="usage_limit_per_coupon"
                                        class="form-label">{{ __('Usage Limit Per Coupon') }}</label>
                                    <input type="number" class="form-control" id="usage_limit_per_coupon"
                                        name="usage_limit_per_coupon" placeholder="{{ __('Enter global usage limit') }}"
                                        min="0">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="usage_limit_per_customer"
                                        class="form-label">{{ __('Usage Limit Per Customer') }}</label>
                                    <input type="number" class="form-control" id="usage_limit_per_customer"
                                        name="usage_limit_per_customer"
                                        placeholder="{{ __('Enter per-user usage limit') }}" min="0">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">{{ __('Status') }}<span
                                            class="required">*</span></label>
                                    <select class="select form-control wide sf-select-without-search" id="status"
                                        name="status" required>
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="primary-btn" id="submit-btn">{{ __('Create Coupon') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



@push('script')
<script src="{{ asset('super_admin/js/coupons.js') }}"></script>
@endpush