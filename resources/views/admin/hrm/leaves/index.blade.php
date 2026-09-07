@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}
        @if($pendingCount > 0)
            <span class="zBadge zBadge-warning ms-2">{{ $pendingCount }} {{ __('Pending') }}</span>
        @endif
    </h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('New Request') }}
    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            {{-- Filters --}}
            <div class="row mb-3 gy-2">
                <div class="col-md-4">
                    <select class="form-control" id="filterStatus">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="{{ LEAVE_STATUS_PENDING }}">{{ __('Pending') }}</option>
                        <option value="{{ LEAVE_STATUS_APPROVED }}">{{ __('Approved') }}</option>
                        <option value="{{ LEAVE_STATUS_REJECTED }}">{{ __('Rejected') }}</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" id="filterEmployee">
                        <option value="">{{ __('All Employees') }}</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="table-waraper">
                <input type="hidden" id="leave-data-route" value="{{ route('admin.hrm.leaves.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="leaveDataTable">
                    <thead>
                        <tr>
                            <th>{{ __("SL") }}</th>
                            <th>{{ __("Employee") }}</th>
                            <th>{{ __("Type") }}</th>
                            <th>{{ __("Duration") }}</th>
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

{{-- Add Leave Modal --}}
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" action="{{ route('admin.hrm.leaves.store') }}">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 mb-0">{{ __('New Leave Request') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Employee') }} <span class="required">*</span></label>
                                    <select class="form-control" name="employee_id" required>
                                        <option value="">{{ __('Select Employee') }}</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Leave Type') }} <span class="required">*</span></label>
                                    <select class="form-control" name="leave_type" required>
                                        <option value="{{ LEAVE_TYPE_CASUAL }}">{{ __('Casual') }}</option>
                                        <option value="{{ LEAVE_TYPE_SICK }}">{{ __('Sick') }}</option>
                                        <option value="{{ LEAVE_TYPE_ANNUAL }}">{{ __('Annual') }}</option>
                                        <option value="{{ LEAVE_TYPE_MATERNITY }}">{{ __('Maternity') }}</option>
                                        <option value="{{ LEAVE_TYPE_OTHER }}">{{ __('Other') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Start Date') }} <span class="required">*</span></label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('End Date') }} <span class="required">*</span></label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Reason') }}</label>
                                    <textarea class="form-control" name="reason" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Submit') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade zModalTwo" id="reject-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" id="rejectForm">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 mb-0">{{ __('Reject Leave') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __('Admin Note') }}</label>
                        <textarea class="form-control" name="admin_note" rows="3"></textarea>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Reject') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    var table = $('#leaveDataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: $('#leave-data-route').val(),
            type: 'GET',
            data: function (d) {
                d.status = $('#filterStatus').val();
                d.employee_id = $('#filterEmployee').val();
            }
        },
        columns: [
            { data: 'sl', name: 'sl', orderable: false, searchable: false },
            { data: 'employee', name: 'employee' },
            { data: 'leave_type', name: 'leave_type' },
            { data: 'duration', name: 'duration', orderable: false, searchable: false },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    $('#filterStatus, #filterEmployee').change(function () { table.ajax.reload(); });
});

function rejectLeave(url) {
    $('#rejectForm').attr('action', url);
    $('#reject-modal').modal('show');
}
</script>
@endpush
