@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <a href="{{ route('admin.hrm.employees.create') }}" class="primary-btn">
        <i class="fa fa-plus me-2"></i>{{ __('Add Employee') }}
    </a>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                {{-- Filters & Search --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                    <div class="search-input-wrap mb-0 flex-grow-1" style="max-width: 380px;">
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
                            placeholder="{{ __('Search Employees...') }}" />
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                        <div class="select-wrap" style="min-width: 200px;">
                            <select class="form-select form-control sf-select wide" id="filterDepartment" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value="">{{ __('All Departments') }}</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="select-wrap" style="min-width: 170px;">
                            <select class="form-select form-control sf-select wide" id="filterStatus" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="{{ EMPLOYEE_STATUS_ACTIVE }}">{{ __('Active') }}</option>
                                <option value="{{ EMPLOYEE_STATUS_ON_LEAVE }}">{{ __('On Leave') }}</option>
                                <option value="{{ EMPLOYEE_STATUS_TERMINATED }}">{{ __('Terminated') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="employee-data-route" value="{{ route('admin.hrm.employees.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="employeeDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __("SL") }}</th>
                            <th>{{ __("Employee Code") }}</th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Department") }}</th>
                            <th>{{ __("Designation") }}</th>
                            <th>{{ __("Phone") }}</th>
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
<script src="{{ asset('admin/js/hrm-employees.js') }}"></script>
@endpush
