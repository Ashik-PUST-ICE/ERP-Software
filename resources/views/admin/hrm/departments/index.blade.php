@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Add Department') }}
    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <input type="hidden" id="department-data-route" value="{{ route('admin.hrm.departments.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="departmentDataTable">
                    <thead>
                        <tr>
                            <th>{{ __("SL") }}</th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Code") }}</th>
                            <th>{{ __("Employees") }}</th>
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

<!-- Add Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="{{ route('admin.hrm.departments.store') }}" method="post"
                data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Add Department') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Department Name') }} <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="{{ __('e.g. Human Resources') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Code') }} <span class="required">*</span></label>
                                    <input type="text" class="form-control" name="code" placeholder="{{ __('e.g. HR') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Description') }}</label>
                                    <textarea class="form-control" name="description" rows="2" placeholder="{{ __('Short description...') }}"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                                    <select class="form-control" name="status" required>
                                        <option value="{{ STATUS_ACTIVE }}">{{ __('Active') }}</option>
                                        <option value="{{ STATUS_DEACTIVATE }}">{{ __('Deactivate') }}</option>
                                    </select>
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

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        var table = $('#departmentDataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#department-data-route').val(),
                type: 'GET',
            },
            columns: [
                { data: 'sl', name: 'sl', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'code', name: 'code' },
                { data: 'employees_count', name: 'employees_count' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });
    });
</script>
@endpush
