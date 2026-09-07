@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <a href="{{ route('admin.hrm.employees.index') }}" class="primary-btn">
        <i class="fa fa-arrow-left me-2"></i>{{ __('Back to List') }}
    </a>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            @php $isEdit = isset($employee); @endphp
            <form action="{{ $isEdit ? route('admin.hrm.employees.update', $employee->id) : route('admin.hrm.employees.store') }}"
                method="POST">
                @csrf
                @if($isEdit) @method('PUT') @endif

                <div class="primary-form">
                    <div class="row gy-3">
                        {{-- Personal Info --}}
                        <div class="col-12">
                            <h5 class="fw-600 mb-2">{{ __('Personal Information') }}</h5>
                            <hr>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('First Name') }} <span class="required">*</span></label>
                                <input type="text" class="form-control" name="first_name"
                                    value="{{ old('first_name', $employee->first_name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Last Name') }} <span class="required">*</span></label>
                                <input type="text" class="form-control" name="last_name"
                                    value="{{ old('last_name', $employee->last_name ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Email') }} <span class="required">*</span></label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email', $employee->email ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" name="phone"
                                    value="{{ old('phone', $employee->phone ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Gender') }}</label>
                                <select class="form-control" name="gender">
                                    <option value="">{{ __('Select') }}</option>
                                    <option value="{{ GENDER_MALE }}" {{ old('gender', $employee->gender ?? '') == GENDER_MALE ? 'selected' : '' }}>{{ __('Male') }}</option>
                                    <option value="{{ GENDER_FEMALE }}" {{ old('gender', $employee->gender ?? '') == GENDER_FEMALE ? 'selected' : '' }}>{{ __('Female') }}</option>
                                    <option value="{{ GENDER_OTHER }}" {{ old('gender', $employee->gender ?? '') == GENDER_OTHER ? 'selected' : '' }}>{{ __('Other') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Date of Birth') }}</label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth', $employee->date_of_birth ?? '') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Joining Date') }} <span class="required">*</span></label>
                                <input type="date" class="form-control" name="joining_date"
                                    value="{{ old('joining_date', $employee->joining_date ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Address') }}</label>
                                <textarea class="form-control" name="address" rows="2">{{ old('address', $employee->address ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- Job Info --}}
                        <div class="col-12 mt-3">
                            <h5 class="fw-600 mb-2">{{ __('Job Information') }}</h5>
                            <hr>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Department') }} <span class="required">*</span></label>
                                <select class="form-control" name="department_id" id="department_id" required>
                                    <option value="">{{ __('Select Department') }}</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}"
                                            {{ old('department_id', $employee->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Designation') }} <span class="required">*</span></label>
                                <select class="form-control" name="designation_id" id="designation_id" required>
                                    <option value="">{{ __('Select Designation') }}</option>
                                    @if($isEdit && isset($designations))
                                        @foreach($designations as $desig)
                                            <option value="{{ $desig->id }}" {{ $employee->designation_id == $desig->id ? 'selected' : '' }}>{{ $desig->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Employment Type') }} <span class="required">*</span></label>
                                <select class="form-control" name="employment_type" required>
                                    <option value="{{ EMPLOYMENT_TYPE_FULL_TIME }}" {{ old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_FULL_TIME ? 'selected' : '' }}>{{ __('Full Time') }}</option>
                                    <option value="{{ EMPLOYMENT_TYPE_PART_TIME }}" {{ old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_PART_TIME ? 'selected' : '' }}>{{ __('Part Time') }}</option>
                                    <option value="{{ EMPLOYMENT_TYPE_CONTRACT }}" {{ old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_CONTRACT ? 'selected' : '' }}>{{ __('Contract') }}</option>
                                    <option value="{{ EMPLOYMENT_TYPE_INTERN }}" {{ old('employment_type', $employee->employment_type ?? '') == EMPLOYMENT_TYPE_INTERN ? 'selected' : '' }}>{{ __('Intern') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Basic Salary') }} <span class="required">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="basic_salary"
                                    value="{{ old('basic_salary', $employee->basic_salary ?? '') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="{{ EMPLOYEE_STATUS_ACTIVE }}" {{ old('status', $employee->status ?? EMPLOYEE_STATUS_ACTIVE) == EMPLOYEE_STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Active') }}</option>
                                    <option value="{{ EMPLOYEE_STATUS_ON_LEAVE }}" {{ old('status', $employee->status ?? '') == EMPLOYEE_STATUS_ON_LEAVE ? 'selected' : '' }}>{{ __('On Leave') }}</option>
                                    <option value="{{ EMPLOYEE_STATUS_TERMINATED }}" {{ old('status', $employee->status ?? '') == EMPLOYEE_STATUS_TERMINATED ? 'selected' : '' }}>{{ __('Terminated') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-list mt-4 pt-3 border-top">
                    <a href="{{ route('admin.hrm.employees.index') }}" class="primary-btn">{{ __('Cancel') }}</a>
                    <button type="submit" class="primary-btn">{{ $isEdit ? __('Update Employee') : __('Save Employee') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function () {
    $('#department_id').on('change', function () {
        var deptId = $(this).val();
        var $desigSelect = $('#designation_id');
        $desigSelect.html('<option value="">{{ __("Loading...") }}</option>');
        if (deptId) {
            $.get('{{ route("admin.hrm.employees.getDesignations") }}', { department_id: deptId }, function (data) {
                var options = '<option value="">{{ __("Select Designation") }}</option>';
                $.each(data, function (i, item) {
                    options += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $desigSelect.html(options);
            });
        } else {
            $desigSelect.html('<option value="">{{ __("Select Designation") }}</option>');
        }
    });
});
</script>
@endpush
