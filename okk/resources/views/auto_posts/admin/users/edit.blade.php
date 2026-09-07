<form class="ajax reset" action="{{ route('admin.users.update', $user->id) }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @method('PUT')
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{__('Edit User')}}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Name') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('Email') }}<span class="required">*</span></label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="mobile" class="form-label">{{ __('Mobile') }}</label>
                        <input type="text" class="form-control" name="mobile" value="{{ $user->mobile }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="status" class="form-label">{{ __('Status') }}<span class="required">*</span></label>
                        <select class="form-control select wide" name="status" required>
                            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="3" {{ $user->status == 3 ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
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
