@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="row gy-4 mb-20 garment-dashboard-kpis">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-file-invoice-dollar"></i></span>
            <div class="card-info">
                <h2 id="kpiInvoiceTotal">--</h2>
                <h3>{{ __('Invoice Total') }}</h3>
            </div>
            <span class="card-status up">{{ __('Total Billed') }} <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span></span>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-circle-check"></i></span>
            <div class="card-info">
                <h2 id="kpiCollectedTotal">--</h2>
                <h3>{{ __('Collected') }}</h3>
            </div>
            <span class="card-status up">{{ __('Total Received') }} <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span></span>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-hand-holding-dollar"></i></span>
            <div class="card-info">
                <h2 id="kpiReceivableTotal">--</h2>
                <h3>{{ __('Receivable') }}</h3>
            </div>
            <span class="card-status up">{{ __('Outstanding Balance') }} <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span></span>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-money-bill-transfer"></i></span>
            <div class="card-info">
                <h2 id="kpiPayablesTotal">--</h2>
                <h3>{{ __('Payable Entries') }}</h3>
            </div>
            <span class="card-status up">{{ __('Accounts Payable') }} <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span></span>
        </div>
    </div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
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
                        placeholder="{{ __('Search Receivables...') }}" />
                </div>
                <input type="hidden" id="ap-ar-data-route" value="{{ route('admin.garments.ap-ar.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="apArDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('SL') }}</th>
                            <th>{{ __('Invoice') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Due Date') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Outstanding') }}</th>
                            <th class="keep-show">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/garment-ap-ar.js') }}"></script>
@endpush
