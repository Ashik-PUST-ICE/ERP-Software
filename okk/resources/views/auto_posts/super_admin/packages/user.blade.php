@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ $title }}
@endpush

@section('content')

<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#assignPackageModal">
        <i class="fa fa-plus me-2"></i>{{ __('Assign Package') }}
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

        <!-- Package Filter Tabs -->
        <div class="mb-3">
            <ul class="nav post-tabs" id="orderTab" role="tablist">
                        <li class="nav-item" role="presentation">
                    <button class="nav-link packageId active" data-bs-toggle="tab" type="button" value="All" role="tab">
                        {{ __('All') }}
                    </button>
                </li>
                @foreach($packages as $data)
                <li class="nav-item" role="presentation">
                    <button class="nav-link packageId" data-bs-toggle="tab" type="button" value="{{ $data->id }}"
                        role="tab">
                        {{ $data->name }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                        </div>

        <input type="hidden" id="packagesUserRoute" value="{{ route('super_admin.packages.user') }}">
        <table class="display primary-table dataTable dtr-inline" id="commonDataTable">
                            <thead>
                <tr>
                    <th class="keep-show">{{ __('SL') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Package Name') }}</th>
                    <th>{{ __('Gateway') }}</th>
                    <th>{{ __('Start Date') }}</th>
                    <th>{{ __('End Date') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="keep-show">{{ __('Action') }}</th>
                </tr>
                            </thead>
            <tbody>
            </tbody>
                        </table>
                    <div id="user-packages-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
    </div>
</div>

<!-- Assign Package Modal -->
<div class="modal fade zModalTwo" id="assignPackageModal" tabindex="-1" aria-labelledby="assignPackageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form id="assign-package-form" method="POST" action="{{ route('super_admin.packages.assign') }}">
                @csrf
                <input type="hidden" name="gateway" value="cash">
                <input type="hidden" name="currency" value="{{ currentCurrencyType() }}">
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Assign Package') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('User') }}<span class="required">*</span></label>
                                    <select name="user_id" class="select form-control wide sf-select-without-search"
                                        id="user_id" required>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name.' - '.$user->email }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Package') }}<span class="required">*</span></label>
                                    <select name="package_id" class="select form-control wide sf-select-without-search"
                                        id="package_id" required>
                                        @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Duration Type') }}<span
                                            class="required">*</span></label>
                                    <select name="duration_type"
                                        class="select form-control wide sf-select-without-search" id="duration_type"
                                        required>
                                        <option value="1">{{ __('Monthly') }}</option>
                                        <option value="2">{{ __('Yearly') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Assign') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('super_admin/js/user-packages.js') }}"></script>
@endpush
