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
                {{-- Filters --}}
                <div class="row mb-3 gy-2">
                    <div class="col-md-4">
                        <select class="form-control" id="filterDepartment">
                            <option value="">{{ __('All Departments') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-control" id="filterStatus">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="{{ EMPLOYEE_STATUS_ACTIVE }}">{{ __('Active') }}</option>
                            <option value="{{ EMPLOYEE_STATUS_ON_LEAVE }}">{{ __('On Leave') }}</option>
                            <option value="{{ EMPLOYEE_STATUS_TERMINATED }}">{{ __('Terminated') }}</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" id="employee-data-route" value="{{ route('admin.hrm.employees.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="employeeDataTable">
                    <thead>
                        <tr>
                            <th>{{ __("SL") }}</th>
                            <th>{{ __("Employee Code") }}</th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Department") }}</th>
                            <th>{{ __("Designation") }}</th>
                            <th>{{ __("Phone") }}</th>
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
        var table = $('#employeeDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#employee-data-route').val(),
                type: 'GET',
                data: function (d) {
                    d.department_id = $('#filterDepartment').val();
                    d.status = $('#filterStatus').val();
                }
            },
            columns: [
                { data: 'sl', name: 'sl', orderable: false, searchable: false },
                { data: 'employee_code', name: 'employee_code' },
                { data: 'full_name', name: 'first_name' },
                { data: 'department', name: 'department.name' },
                { data: 'designation', name: 'designation.name' },
                { data: 'phone', name: 'phone' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#filterDepartment, #filterStatus').change(function () {
            table.ajax.reload();
        });
    });
</script>
@endpush
