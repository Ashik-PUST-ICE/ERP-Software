@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        {{-- Summary Cards --}}
        <div class="row gy-3 mb-4">
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Total Basic') }}</p>
                    <h4 class="fw-700">{{ showPrice($summary['total_basic']) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Total Allowances') }}</p>
                    <h4 class="fw-700 text-success">{{ showPrice($summary['total_allowances']) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Total Deductions') }}</p>
                    <h4 class="fw-700 text-danger">{{ showPrice($summary['total_deductions']) }}</h4>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Net Payroll') }}</p>
                    <h4 class="fw-700 text-primary">{{ showPrice($summary['total_net']) }}</h4>
                </div>
            </div>
        </div>

        <div class="section-wrap">
            {{-- Controls --}}
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form method="GET" action="{{ route('admin.hrm.payroll.index') }}" class="d-flex gap-2">
                    <input type="month" class="form-control" name="month" value="{{ $month }}">
                    <button type="submit" class="primary-btn">{{ __('Filter') }}</button>
                </form>

                <form method="POST" action="{{ route('admin.hrm.payroll.generate') }}" class="ms-auto">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit" class="primary-btn">
                        <i class="fa fa-cog me-1"></i>{{ __('Generate Payroll') }}
                    </button>
                </form>

                @if($summary['unpaid_count'] > 0)
                <form method="POST" action="{{ route('admin.hrm.payroll.bulkPay') }}">
                    @csrf
                    <input type="hidden" name="month" value="{{ $month }}">
                    <button type="submit" class="primary-btn"
                        onclick="return confirm('{{ __('Mark all unpaid as paid?') }}')">
                        <i class="fa fa-check me-1"></i>{{ __('Bulk Pay') }} ({{ $summary['unpaid_count'] }})
                    </button>
                </form>
                @endif
            </div>

            <div class="d-flex gap-3 mb-3">
                <span class="zBadge zBadge-complete">{{ $summary['paid_count'] }} {{ __('Paid') }}</span>
                <span class="zBadge zBadge-warning">{{ $summary['unpaid_count'] }} {{ __('Unpaid') }}</span>
            </div>

            <div class="table-waraper mt-3">
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
                        placeholder="{{ __('Search Payroll...') }}" />
                </div>
                <input type="hidden" id="payroll-data-route" value="{{ route('admin.hrm.payroll.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="payrollDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __("SL") }}</th>
                            <th>{{ __("Employee") }}</th>
                            <th>{{ __("Department") }}</th>
                            <th>{{ __("Basic") }}</th>
                            <th>{{ __("Allowances") }}</th>
                            <th>{{ __("Deductions") }}</th>
                            <th>{{ __("Net Salary") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th class="keep-show">{{ __("Action") }}</th>
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
<script src="{{ asset('admin/js/hrm-payroll.js') }}"></script>
@endpush
