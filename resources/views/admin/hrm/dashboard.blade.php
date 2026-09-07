@extends('auto_posts.admin.layouts.admin')
@push('title') {{ __('HRM Dashboard') }} @endpush

@section('content')

<div class="section-title">
    <h2 class="title">{{ __('HRM Dashboard') }}</h2>
    <span class="text-muted" style="font-size:1.3rem;">{{ now()->format('l, d F Y') }}</span>
</div>

{{-- KPI Cards (card-box style matching super admin) --}}
<div class="row gy-4 mb-20">

    {{-- Total Employees --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#02BCFF"/>
                    <path d="M19 19C21.2091 19 23 17.2091 23 15C23 12.7909 21.2091 11 19 11C16.7909 11 15 12.7909 15 15C15 17.2091 16.7909 19 19 19Z" stroke="white" stroke-width="1.5"/>
                    <path d="M11 27C11 23.134 14.134 20 18 20H20C23.866 20 27 23.134 27 27" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $totalEmployees }}</h2>
                <h3>{{ __('Total Employees') }}</h3>
            </div>
            <span class="card-status up">
                <a href="{{ route('admin.hrm.employees.index') }}" style="color:inherit; text-decoration:none;">{{ __('View All') }}</a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    {{-- Today Present --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                    <path d="M13 19L17 23L25 15" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $todayPresent }}</h2>
                <h3>{{ __("Today's Present") }}</h3>
            </div>
            <span class="card-status {{ $todayPresent > 0 ? 'up' : 'down' }}">
                <a href="{{ route('admin.hrm.attendance.index') }}" style="color:inherit; text-decoration:none;">{{ __('Attendance') }}</a>
                <span class="arrow"><i class="fa-solid fa-arrow-{{ $todayPresent > 0 ? 'up' : 'down' }}"></i></span>
            </span>
        </div>
    </div>

    {{-- Pending Leaves --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                    <path d="M19 13V19L22 22" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $pendingLeaves }}</h2>
                <h3>{{ __('Pending Leaves') }}</h3>
            </div>
            <span class="card-status {{ $pendingLeaves > 0 ? 'down' : 'up' }}">
                <a href="{{ route('admin.hrm.leaves.index') }}" style="color:inherit; text-decoration:none;">{{ __('Review') }}</a>
                <span class="arrow"><i class="fa-solid fa-arrow-{{ $pendingLeaves > 0 ? 'up' : 'up' }}"></i></span>
            </span>
        </div>
    </div>

    {{-- Monthly Payroll --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0D0D0D"/>
                    <path d="M13 15H25M13 19H25M13 23H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11 13C11 11.8954 11.8954 11 13 11H25C26.1046 11 27 11.8954 27 13V25C27 26.1046 26.1046 27 25 27H13C11.8954 27 11 26.1046 11 25V13Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 style="font-size:2rem;">{{ showPrice($monthlyPayroll) }}</h2>
                <h3>{{ __('Monthly Payroll') }}</h3>
            </div>
            <span class="card-status up">
                <a href="{{ route('admin.hrm.payroll.index') }}" style="color:inherit; text-decoration:none;">{{ __('View Payroll') }}</a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

</div>

{{-- Second row: Today details + Department count --}}
<div class="row gy-4 mb-20">

    {{-- Today Late --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FFC402"/>
                    <path d="M19 13V19" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M19 22V23" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $todayLate }}</h2>
                <h3>{{ __('Today Late') }}</h3>
            </div>
            <span class="card-status {{ $todayLate > 0 ? 'down' : 'up' }}">
                {{ $todayLate }} {{ __('Late') }}
                <span class="arrow"><i class="fa-solid fa-arrow-{{ $todayLate > 0 ? 'down' : 'up' }}"></i></span>
            </span>
        </div>
    </div>

    {{-- Today Absent --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                    <path d="M15 15L23 23M23 15L15 23" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $todayAbsent }}</h2>
                <h3>{{ __('Today Absent') }}</h3>
            </div>
            <span class="card-status {{ $todayAbsent > 0 ? 'down' : 'up' }}">
                {{ $todayAbsent }} {{ __('Absent') }}
                <span class="arrow"><i class="fa-solid fa-arrow-{{ $todayAbsent > 0 ? 'down' : 'up' }}"></i></span>
            </span>
        </div>
    </div>

    {{-- Total Departments --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                    <path d="M11 27V15L19 11L27 15V27" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 27V22H22V27" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M15 18H15.01M19 18H19.01M23 18H23.01" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $totalDepartments }}</h2>
                <h3>{{ __('Departments') }}</h3>
            </div>
            <span class="card-status up">
                <a href="{{ route('admin.hrm.departments.index') }}" style="color:inherit; text-decoration:none;">{{ __('Manage') }}</a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    {{-- Department Stats Donut --}}
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#6B02FF"/>
                    <path d="M19 11V19L24 24" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ $totalEmployees - $todayPresent }}</h2>
                <h3>{{ __('Not Checked In') }}</h3>
            </div>
            <span class="card-status {{ ($totalEmployees - $todayPresent) > 0 ? 'down' : 'up' }}">
                {{ __('Today') }}
                <span class="arrow"><i class="fa-solid fa-arrow-{{ ($totalEmployees - $todayPresent) > 0 ? 'down' : 'up' }}"></i></span>
            </span>
        </div>
    </div>

</div>

{{-- Bottom Section: Recent Employees + Pending Leaves + Department Stats --}}
<div class="row gy-4">

    {{-- Recent Employees --}}
    <div class="col-xl-6 col-lg-6">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title">{{ __('Recent Employees') }}</h3>
                <a href="{{ route('admin.hrm.employees.index') }}" class="text-primary" style="font-size:1.2rem;">{{ __('View All') }}</a>
            </div>
            <table class="table primary-table w-100">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Department') }}</th>
                        <th>{{ __('Designation') }}</th>
                        <th>{{ __('Status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentEmployees as $emp)
                    <tr>
                        <td>
                            <strong>{{ $emp->first_name }} {{ $emp->last_name }}</strong><br>
                            <small class="text-muted">{{ $emp->employee_code }}</small>
                        </td>
                        <td>{{ $emp->department->name ?? 'N/A' }}</td>
                        <td>{{ $emp->designation->name ?? 'N/A' }}</td>
                        <td>
                            @if($emp->status == EMPLOYEE_STATUS_ACTIVE)
                                <span class="zBadge zBadge-complete">{{ __('Active') }}</span>
                            @else
                                <span class="zBadge zBadge-deactive">{{ ucfirst($emp->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-3">{{ __('No employees found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending Leave Requests --}}
    <div class="col-xl-6 col-lg-6">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title">{{ __('Pending Leave Requests') }}</h3>
                <a href="{{ route('admin.hrm.leaves.index') }}" class="text-primary" style="font-size:1.2rem;">{{ __('View All') }}</a>
            </div>
            <table class="table primary-table w-100">
                <thead>
                    <tr>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Days') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLeaveList as $leave)
                    <tr>
                        <td>{{ $leave->employee->first_name ?? '' }} {{ $leave->employee->last_name ?? '' }}</td>
                        <td>{{ ucfirst($leave->leave_type) }}</td>
                        <td>{{ $leave->days_count }}</td>
                        <td>{{ optional($leave->start_date)->format('d M') }}</td>
                        <td>
                            <a href="{{ route('admin.hrm.leaves.approve', $leave->id) }}"
                               class="zBadge zBadge-complete">{{ __('Approve') }}</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">{{ __('No pending leaves') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Department Headcount --}}
    <div class="col-12">
        <div class="section-wrap">
            <div class="section-small-title mb-20">
                <h3 class="title">{{ __('Department Headcount') }}</h3>
            </div>
            <div class="row gy-3">
                @forelse($departmentStats as $dept)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                    <div class="stats">
                        <div class="stat-box w-100" style="max-width:100%;">
                            <strong>{{ $dept->employees_count }}</strong>
                            {{ $dept->name }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center text-muted">{{ __('No departments found') }}</div>
                @endforelse
            </div>
        </div>
    </div>

</div>

@endsection
