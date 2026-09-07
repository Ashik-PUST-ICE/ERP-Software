<form class="ajax reset" action="{{ route('admin.hrm.designations.update', $designation->id) }}" method="post"
    data-handler="commonResponseWithPageLoad">
    @method('put')
    @csrf
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Edit Designation') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Department') }} <span class="required">*</span></label>
                        <select class="form-control" name="department_id" required>
                            <option value="">{{ __('Select Department') }}</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ $designation->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="form-label">{{ __('Designation Name') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $designation->name }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">{{ __('Code') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" name="code" value="{{ $designation->code }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea class="form-control" name="description" rows="2">{{ $designation->description }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <option value="{{ STATUS_ACTIVE }}" {{ $designation->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="{{ STATUS_DEACTIVATE }}" {{ $designation->status == STATUS_DEACTIVATE ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Update') }}</button>
        </div>
    </div>
</form>
