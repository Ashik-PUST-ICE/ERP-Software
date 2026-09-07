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
                    <h5 class="fw-600 mb-3">{{ __('Recent Attendance') }}</h5>
                    <table class="table primary-table">
                        <thead><tr><th>{{ __('Date') }}</th><th>{{ __('Check In') }}</th><th>{{ __('Status') }}</th></tr></thead>
                        <tbody>
                            @forelse($employee->attendances as $att)
                            <tr>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->check_in ?? '—' }}</td>
                                <td>
                                    @if($att->status == ATTENDANCE_STATUS_PRESENT)
                                        <span class="zBadge zBadge-complete">{{ __('Present') }}</span>
                                    @elseif($att->status == ATTENDANCE_STATUS_LATE)
                                        <span class="zBadge zBadge-warning">{{ __('Late') }}</span>
                                    @elseif($att->status == ATTENDANCE_STATUS_ABSENT)
                                        <span class="zBadge zBadge-deactive">{{ __('Absent') }}</span>
                                    @else
                                        <span class="zBadge zBadge-warning">{{ ucfirst($att->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">{{ __('No records found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Leave --}}
            <div class="col-md-6">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3">{{ __('Recent Leaves') }}</h5>
                    <table class="table primary-table">
                        <thead><tr><th>{{ __('Type') }}</th><th>{{ __('Days') }}</th><th>{{ __('Status') }}</th></tr></thead>
                        <tbody>
                            @forelse($employee->leaveRequests as $leave)
                            <tr>
                                <td>{{ ucfirst($leave->leave_type) }}</td>
                                <td>{{ $leave->days_count }}</td>
                                <td>
                                    @if($leave->status == LEAVE_STATUS_APPROVED)
                                        <span class="zBadge zBadge-complete">{{ __('Approved') }}</span>
                                    @elseif($leave->status == LEAVE_STATUS_REJECTED)
                                        <span class="zBadge zBadge-deactive">{{ __('Rejected') }}</span>
                                    @else
                                        <span class="zBadge zBadge-warning">{{ __('Pending') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">{{ __('No records found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Payroll --}}
            <div class="col-12">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3">{{ __('Recent Payroll') }}</h5>
                    <table class="table primary-table">
                        <thead><tr><th>{{ __('Month') }}</th><th>{{ __('Basic') }}</th><th>{{ __('Allowances') }}</th><th>{{ __('Deductions') }}</th><th>{{ __('Net') }}</th><th>{{ __('Status') }}</th></tr></thead>
                        <tbody>
                            @forelse($employee->payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->payroll_month }}</td>
                                <td>{{ showPrice($payroll->basic_salary) }}</td>
                                <td>{{ showPrice($payroll->allowances) }}</td>
                                <td>{{ showPrice($payroll->deductions) }}</td>
                                <td>{{ showPrice($payroll->net_salary) }}</td>
                                <td>
                                    @if($payroll->payment_status == PAYMENT_STATUS_PAID)
                                        <span class="zBadge zBadge-complete">{{ __('Paid') }}</span>
                                    @else
                                        <span class="zBadge zBadge-warning">{{ __('Unpaid') }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted">{{ __('No records found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
