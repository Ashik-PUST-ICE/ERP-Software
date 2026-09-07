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
                            placeholder="{{ __('Search Attendance...') }}" />
                    </div>

                    <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                        <div class="select-wrap" style="min-width: 170px;">
                            <input type="date" class="form-control form-control sf-select wide" id="filterDate" value="{{ $date }}" style="height: 42px; border-radius: 10px; font-size: 13px;">
                        </div>
                        <div class="select-wrap" style="min-width: 170px;">
                            <select class="form-select form-control sf-select wide" id="filterStatus" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value="">{{ __('All Status') }}</option>
                                <option value="{{ ATTENDANCE_STATUS_PRESENT }}">{{ __('Present') }}</option>
                                <option value="{{ ATTENDANCE_STATUS_LATE }}">{{ __('Late') }}</option>
                                <option value="{{ ATTENDANCE_STATUS_ABSENT }}">{{ __('Absent') }}</option>
                                <option value="{{ ATTENDANCE_STATUS_ON_LEAVE }}">{{ __('On Leave') }}</option>
                            </select>
                        </div>
                        <div class="select-wrap" style="min-width: 200px;">
                            <select class="form-select form-control sf-select wide" id="filterDepartment" style="height: 42px; border-radius: 10px; font-size: 13px;">
                                <option value="">{{ __('All Departments') }}</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="attendance-data-route" value="{{ route('admin.hrm.attendance.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="attendanceDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __("SL") }}</th>
                            <th>{{ __("Employee Code") }}</th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Department") }}</th>
                            <th>{{ __("Check In") }}</th>
                            <th>{{ __("Check Out") }}</th>
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
<script src="{{ asset('admin/js/hrm-attendance.js') }}"></script>
@endpush
