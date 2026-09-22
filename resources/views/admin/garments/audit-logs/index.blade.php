@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.4405 14.9222L11.9944 13.4762Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882 9.46824 11.6882 6.72982Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search audit logs...') }}" />
                    <select class="form-control w-auto" id="auditLogModuleFilter" style="height: 38px; border-radius: 8px; font-size: 13px;">
                        <option value="">{{ __('All Modules') }}</option>
                        @foreach(['Supplier', 'Purchase Order', 'Email', 'Email Template', 'Queue'] as $module)
                            <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                        @endforeach
                    </select>
                    <select class="form-control w-auto" id="auditLogActionFilter" style="height: 38px; border-radius: 8px; font-size: 13px;">
                        <option value="">{{ __('All Actions') }}</option>
                        @foreach(['created', 'updated', 'deleted'] as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst($action) }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" id="audit-logs-route" value="{{ route('admin.garments.audit-logs.index') }}">
                </div>

                <table class="display primary-table dataTable dtr-inline" id="auditLogsTable">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Module') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('IP Address') }}</th>
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
<script src="{{ asset('admin/js/garment-audit-logs.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
