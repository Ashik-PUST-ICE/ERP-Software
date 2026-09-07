@extends('auto_posts.admin.layouts.admin')

@push('title') {{ __('HRM Dashboard') }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __('HRM Dashboard') }}</h2>
    <span class="text-muted">{{ now()->format('l, d F Y') }}</span>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        {{-- KPI Cards --}}
        <div class="row gy-3 mb-4">
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <i class="fa fa-users fa-2x text-primary mb-2"></i>
                    <p class="text-muted mb-1">{{ __('Total Employees') }}</p>
                    <h3 class="fw-700">{{ $totalEmployees }}</h3>
                    <a href="{{ route('admin.hrm.employees.index') }}" class="text-primary small">{{ __('View All') }}</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <i class="fa fa-check-circle fa-2x text-success mb-2"></i>
                    <p class="text-muted mb-1">{{ __("Today's Present") }}</p>
                    <h3 class="fw-700 text-success">{{ $todayPresent }}</h3>
                    <a href="{{ route('admin.hrm.attendance.index') }}" class="text-primary small">{{ __('View Attendance') }}</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <i class="fa fa-clock fa-2x text-warning mb-2"></i>
                    <p class="text-muted mb-1">{{ __('Pending Leaves') }}</p>
                    <h3 class="fw-700 text-warning">{{ $pendingLeaves }}</h3>
                    <a href="{{ route('admin.hrm.leaves.index') }}" class="text-primary small">{{ __('Review Leaves') }}</a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <i class="fa fa-building fa-2x text-info mb-2"></i>
                    <p class="text-muted mb-1">{{ __('Departments') }}</p>
                    <h3 class="fw-700 text-info">{{ $totalDepartments }}</h3>
                    <a href="{{ route('admin.hrm.departments.index') }}" class="text-primary small">{{ __('Manage') }}</a>
                </div>
            </div>
        </div>

        {{-- Today Summary Row --}}
        <div class="row gy-4 mb-4">
            <div class="col-md-4">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Today Late') }}</p>
                    <h4 class="fw-700 text-warning">{{ $todayLate }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Today Absent') }}</p>
                    <h4 class="fw-700 text-danger">{{ $todayAbsent }}</h4>
                </div>
            </div>
            <div class="col-md-4">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Monthly Payroll') }}</p>
                    <h4 class="fw-700 text-primary">{{ showPrice($monthlyPayroll) }}</h4>
                </div>
            </div>
        </div>

        <div class="row gy-4">
            {{-- Recent Employees --}}
            <div class="col-md-6">
                <div class="section-wrap">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-600 mb-0">{{ __('Recent Employees') }}</h5>
                        <a href="{{ route('admin.hrm.employees.index') }}" class="text-primary small">{{ __('View All') }}</a>
                    </div>
                    <table class="table primary-table">
                        <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Department') }}</th><th>{{ __('Status') }}</th></tr></thead>
                        <tbody>
                            @forelse($recentEmployees as $emp)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.hrm.employees.show', $emp->id) }}">
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </a><br>
                                    <small class="text-muted">{{ $emp->employee_code }}</small>
                                </td>
                                <td>{{ $emp->department->name ?? 'N/A' }}</td>
                                <td>
                                    @if($emp->status == EMPLOYEE_STATUS_ACTIVE)
                                        <span class="zBadge zBadge-complete">{{ __('Active') }}</span>
                                    @else
                                        <span class="zBadge zBadge-deactive">{{ ucfirst($emp->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted">{{ __('No employees found') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pending Leaves --}}
            <div class="col-md-6">
                <div class="section-wrap">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-600 mb-0">{{ __('Pending Leave Requests') }}</h5>
                        <a href="{{ route('admin.hrm.leaves.index') }}" class="text-primary small">{{ __('View All') }}</a>
                    </div>
                    <table class="table primary-table">
                        <thead><tr><th>{{ __('Employee') }}</th><th>{{ __('Type') }}</th><th>{{ __('Days') }}</th><th>{{ __('Action') }}</th></tr></thead>
                        <tbody>
                            @forelse($pendingLeaveList as $leave)
                            <tr>
                                <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                <td>{{ ucfirst($leave->leave_type) }}</td>
                                <td>{{ $leave->days_count }}</td>
                                <td>
                                    <a href="{{ route('admin.hrm.leaves.approve', $leave->id) }}"
                                        class="zBadge zBadge-complete">{{ __('Approve') }}</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted">{{ __('No pending leaves') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Department Stats --}}
            <div class="col-12">
                <div class="section-wrap">
                    <h5 class="fw-600 mb-3">{{ __('Department Headcount') }}</h5>
                    <div class="row gy-3">
                        @forelse($departmentStats as $dept)
                        <div class="col-md-3">
                            <div class="text-center p-3 border rounded">
                                <h5 class="fw-600">{{ $dept->name }}</h5>
                                <h3 class="text-primary">{{ $dept->employees_count }}</h3>
                                <small class="text-muted">{{ __('Employees') }}</small>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted">{{ __('No departments found') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
