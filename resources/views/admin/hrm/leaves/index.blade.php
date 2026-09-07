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
                        placeholder="{{ __('Search Leave Requests...') }}" />
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap ms-auto">
                    <div class="select-wrap" style="min-width: 170px;">
                        <select class="form-select form-control sf-select wide" id="filterStatus" style="height: 42px; border-radius: 10px; font-size: 13px;">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="{{ LEAVE_STATUS_PENDING }}">{{ __('Pending') }}</option>
                            <option value="{{ LEAVE_STATUS_APPROVED }}">{{ __('Approved') }}</option>
                            <option value="{{ LEAVE_STATUS_REJECTED }}">{{ __('Rejected') }}</option>
                        </select>
                    </div>
                    <div class="select-wrap" style="min-width: 200px;">
                        <select class="form-select form-control sf-select wide" id="filterEmployee" style="height: 42px; border-radius: 10px; font-size: 13px;">
                            <option value="">{{ __('All Employees') }}</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-waraper">
                <input type="hidden" id="leave-data-route" value="{{ route('admin.hrm.leaves.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="leaveDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __("SL") }}</th>
                            <th>{{ __("Employee") }}</th>
                            <th>{{ __("Type") }}</th>
                            <th>{{ __("Duration") }}</th>
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

{{-- Add Leave Modal --}}
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" action="{{ route('admin.hrm.leaves.store') }}" class="ajax reset" data-handler="commonResponseWithPageLoad">
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
            <form method="POST" id="rejectForm" class="ajax reset" data-handler="commonResponseWithPageLoad">
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
<script src="{{ asset('admin/js/hrm-leaves.js') }}"></script>
@endpush
