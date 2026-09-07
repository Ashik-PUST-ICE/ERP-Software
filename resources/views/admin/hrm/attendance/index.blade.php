@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        {{-- Stats Cards --}}
        <div class="row gy-3 mb-4">
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Present') }}</p>
                    <h3 class="text-success fw-700">{{ $stats['present'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Late') }}</p>
                    <h3 class="text-warning fw-700">{{ $stats['late'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('Absent') }}</p>
                    <h3 class="text-danger fw-700">{{ $stats['absent'] }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="section-wrap text-center">
                    <p class="text-muted mb-1">{{ __('On Leave') }}</p>
                    <h3 class="text-info fw-700">{{ $stats['on_leave'] }}</h3>
                </div>
            </div>
        </div>

        {{-- Controls --}}
        <div class="section-wrap">
            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                <form method="GET" action="{{ route('admin.hrm.attendance.index') }}" class="d-flex gap-2">
                    <input type="date" class="form-control" name="date" value="{{ $date }}">
                    <button type="submit" class="primary-btn">{{ __('Filter') }}</button>
                </form>

                <form method="POST" action="{{ route('admin.hrm.attendance.bulkMark') }}" class="d-flex gap-2 ms-auto">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <select class="form-control" name="status" required>
                        <option value="{{ ATTENDANCE_STATUS_PRESENT }}">{{ __('Present') }}</option>
                        <option value="{{ ATTENDANCE_STATUS_LATE }}">{{ __('Late') }}</option>
                        <option value="{{ ATTENDANCE_STATUS_ABSENT }}">{{ __('Absent') }}</option>
                    </select>
                    <button type="submit" class="primary-btn">{{ __('Bulk Mark') }}</button>
                </form>
            </div>

            <div class="table-waraper">
                <table class="display primary-table dtr-inline">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Employee') }}</th>
                            <th>{{ __('Department') }}</th>
                            <th>{{ __('Check In') }}</th>
                            <th>{{ __('Check Out') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $key => $employee)
                        @php $att = $employee->attendances->first(); @endphp
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $employee->first_name }} {{ $employee->last_name }}<br>
                                <small class="text-muted">{{ $employee->employee_code }}</small>
                            </td>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>
                            <td>{{ $att->check_in ?? '—' }}</td>
                            <td>{{ $att->check_out ?? '—' }}</td>
                            <td>
                                @if($att)
                                    @if($att->status == ATTENDANCE_STATUS_PRESENT)
                                        <span class="zBadge zBadge-complete">{{ __('Present') }}</span>
                                    @elseif($att->status == ATTENDANCE_STATUS_LATE)
                                        <span class="zBadge zBadge-warning">{{ __('Late') }}</span>
                                    @elseif($att->status == ATTENDANCE_STATUS_ABSENT)
                                        <span class="zBadge zBadge-deactive">{{ __('Absent') }}</span>
                                    @elseif($att->status == ATTENDANCE_STATUS_ON_LEAVE)
                                        <span class="zBadge zBadge-warning">{{ __('On Leave') }}</span>
                                    @else
                                        <span class="zBadge zBadge-warning">{{ ucfirst($att->status) }}</span>
                                    @endif
                                @else
                                    <span class="zBadge zBadge-deactive">{{ __('Absent') }}</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="primary-btn btn-sm"
                                    onclick="markAttendance({{ $employee->id }}, '{{ $date }}')">
                                    {{ __('Mark') }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Mark Attendance Modal --}}
<div class="modal fade zModalTwo" id="mark-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" action="{{ route('admin.hrm.attendance.mark') }}">
                @csrf
                <input type="hidden" name="employee_id" id="att_employee_id">
                <input type="hidden" name="date" id="att_date">
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 mb-0">{{ __('Mark Attendance') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Check In') }}</label>
                                    <input type="time" class="form-control" name="check_in">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Check Out') }}</label>
                                    <input type="time" class="form-control" name="check_out">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="{{ ATTENDANCE_STATUS_PRESENT }}">{{ __('Present') }}</option>
                                        <option value="{{ ATTENDANCE_STATUS_LATE }}">{{ __('Late') }}</option>
                                        <option value="{{ ATTENDANCE_STATUS_ABSENT }}">{{ __('Absent') }}</option>
                                        <option value="{{ ATTENDANCE_STATUS_HALF_DAY }}">{{ __('Half Day') }}</option>
                                        <option value="{{ ATTENDANCE_STATUS_ON_LEAVE }}">{{ __('On Leave') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Notes') }}</label>
                                    <textarea class="form-control" name="notes" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
function markAttendance(empId, date) {
    $('#att_employee_id').val(empId);
    $('#att_date').val(date);
    $('#mark-modal').modal('show');
}
</script>
@endpush
