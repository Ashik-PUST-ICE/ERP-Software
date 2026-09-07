@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.hrm.employees.edit', $employee->id) }}" class="primary-btn">
            <i class="fa fa-edit me-2"></i>{{ __('Edit') }}
        </a>
        <a href="{{ route('admin.hrm.employees.index') }}" class="primary-btn">
            <i class="fa fa-arrow-left me-2"></i>{{ __('Back') }}
        </a>
    </div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        {{-- Profile Card --}}
        <div class="section-wrap mb-4">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center"
                        style="width:80px;height:80px;font-size:2rem;color:#fff;">
                        {{ strtoupper(substr($employee->first_name,0,1)) }}{{ strtoupper(substr($employee->last_name,0,1)) }}
                    </div>
                </div>
                <div class="col-md-10">
                    <h4 class="mb-1">{{ $employee->first_name }} {{ $employee->last_name }}
                        @if($employee->status == EMPLOYEE_STATUS_ACTIVE)
                            <span class="zBadge zBadge-complete ms-2">{{ __('Active') }}</span>
                        @elseif($employee->status == EMPLOYEE_STATUS_ON_LEAVE)
                            <span class="zBadge zBadge-warning ms-2">{{ __('On Leave') }}</span>
                        @else
                            <span class="zBadge zBadge-deactive ms-2">{{ __('Terminated') }}</span>
                        @endif
                    </h4>
                    <p class="text-muted mb-0">{{ $employee->designation->name ?? 'N/A' }} &bull; {{ $employee->department->name ?? 'N/A' }}</p>
                    <p class="text-muted mb-0">{{ $employee->employee_code }} &bull; {{ $employee->email }}</p>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3">{{ __('Personal Information') }}</h5>
                    <table class="table table-borderless">
                        <tr><td class="text-muted">{{ __('Phone') }}</td><td>{{ $employee->phone ?? 'N/A' }}</td></tr>
                        <tr><td class="text-muted">{{ __('Gender') }}</td><td>{{ ucfirst($employee->gender ?? 'N/A') }}</td></tr>
                        <tr><td class="text-muted">{{ __('Date of Birth') }}</td><td>{{ $employee->date_of_birth ?? 'N/A' }}</td></tr>
                        <tr><td class="text-muted">{{ __('Address') }}</td><td>{{ $employee->address ?? 'N/A' }}</td></tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3">{{ __('Job Information') }}</h5>
                    <table class="table table-borderless">
                        <tr><td class="text-muted">{{ __('Joining Date') }}</td><td>{{ $employee->joining_date }}</td></tr>
                        <tr><td class="text-muted">{{ __('Employment Type') }}</td><td>{{ ucfirst(str_replace('_', ' ', $employee->employment_type)) }}</td></tr>
                        <tr><td class="text-muted">{{ __('Basic Salary') }}</td><td>{{ showPrice($employee->basic_salary) }}</td></tr>
                    </table>
                </div>
            </div>

            {{-- Recent Attendance --}}
            <div class="col-md-6">
                <div class="section-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <h5 class="fw-600 mb-0">{{ __('Recent Attendance') }}</h5>
                        <select class="form-select form-control sf-select wide" id="attendanceFilterStatus" style="height: 36px; border-radius: 8px; font-size: 13px; min-width: 140px;">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="{{ ATTENDANCE_STATUS_PRESENT }}">{{ __('Present') }}</option>
                            <option value="{{ ATTENDANCE_STATUS_LATE }}">{{ __('Late') }}</option>
                            <option value="{{ ATTENDANCE_STATUS_ABSENT }}">{{ __('Absent') }}</option>
                            <option value="{{ ATTENDANCE_STATUS_ON_LEAVE }}">{{ __('On Leave') }}</option>
                        </select>
                    </div>
                    <input type="hidden" id="attendanceEmployeeId" value="{{ $employee->id }}">
                    <input type="hidden" id="attendance-history-route" value="{{ route('admin.hrm.employees.attendance.data', $employee->id) }}">
                    <table class="display primary-table dataTable dtr-inline" id="attendanceHistoryTable">
                        <thead>
                            <tr>
                                <th class="keep-show">{{ __("Date") }}</th>
                                <th>{{ __("Check In") }}</th>
                                <th>{{ __("Check Out") }}</th>
                                <th>{{ __("Status") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Leave --}}
            <div class="col-md-6">
                <div class="section-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <h5 class="fw-600 mb-0">{{ __('Recent Leaves') }}</h5>
                        <select class="form-select form-control sf-select wide" id="leaveFilterStatus" style="height: 36px; border-radius: 8px; font-size: 13px; min-width: 140px;">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="{{ LEAVE_STATUS_PENDING }}">{{ __('Pending') }}</option>
                            <option value="{{ LEAVE_STATUS_APPROVED }}">{{ __('Approved') }}</option>
                            <option value="{{ LEAVE_STATUS_REJECTED }}">{{ __('Rejected') }}</option>
                        </select>
                    </div>
                    <input type="hidden" id="leaveEmployeeId" value="{{ $employee->id }}">
                    <input type="hidden" id="leave-history-route" value="{{ route('admin.hrm.employees.leaves.data', $employee->id) }}">
                    <table class="display primary-table dataTable dtr-inline" id="leaveHistoryTable">
                        <thead>
                            <tr>
                                <th class="keep-show">{{ __("Type") }}</th>
                                <th>{{ __("Start Date") }}</th>
                                <th>{{ __("End Date") }}</th>
                                <th>{{ __("Days") }}</th>
                                <th>{{ __("Status") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Payroll --}}
            <div class="col-12">
                <div class="section-wrap">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
                        <h5 class="fw-600 mb-0">{{ __('Recent Payroll') }}</h5>
                        <select class="form-select form-control sf-select wide" id="payrollFilterStatus" style="height: 36px; border-radius: 8px; font-size: 13px; min-width: 140px;">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="{{ PAYMENT_STATUS_PAID }}">{{ __('Paid') }}</option>
                            <option value="{{ PAYMENT_STATUS_PENDING }}">{{ __('Unpaid') }}</option>
                        </select>
                    </div>
                    <input type="hidden" id="payrollEmployeeId" value="{{ $employee->id }}">
                    <input type="hidden" id="payroll-history-route" value="{{ route('admin.hrm.employees.payrolls.data', $employee->id) }}">
                    <table class="display primary-table dataTable dtr-inline" id="payrollHistoryTable">
                        <thead>
                            <tr>
                                <th class="keep-show">{{ __("Month") }}</th>
                                <th>{{ __("Basic") }}</th>
                                <th>{{ __("Allowances") }}</th>
                                <th>{{ __("Deductions") }}</th>
                                <th>{{ __("Net Salary") }}</th>
                                <th>{{ __("Status") }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/employee-show-attendance.js') }}?ver={{ env('VERSION', 0) }}"></script>
<script src="{{ asset('admin/js/employee-show-leaves.js') }}?ver={{ env('VERSION', 0) }}"></script>
<script src="{{ asset('admin/js/employee-show-payrolls.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
