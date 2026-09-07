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

            <div class="table-waraper">
                <input type="hidden" id="payroll-data-route" value="{{ route('admin.hrm.payroll.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="payrollDataTable">
                    <thead>
                        <tr>
                            <th>{{ __("SL") }}</th>
                            <th>{{ __("Employee") }}</th>
                            <th>{{ __("Department") }}</th>
                            <th>{{ __("Basic") }}</th>
                            <th>{{ __("Allowances") }}</th>
                            <th>{{ __("Deductions") }}</th>
                            <th>{{ __("Net Salary") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th>{{ __("Action") }}</th>
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
<script>
$(document).ready(function () {
    var currentMonth = '{{ $month }}';
    $('#payrollDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#payroll-data-route').val(),
            type: 'GET',
            data: function (d) { d.month = currentMonth; }
        },
        columns: [
            { data: 'sl', name: 'sl', orderable: false, searchable: false },
            { data: 'employee', name: 'employee' },
            { data: 'department', name: 'department' },
            { data: 'basic_salary', name: 'basic_salary' },
            { data: 'allowances', name: 'allowances' },
            { data: 'deductions', name: 'deductions' },
            { data: 'net_salary', name: 'net_salary' },
            { data: 'payment_status', name: 'payment_status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });
});
</script>
@endpush
