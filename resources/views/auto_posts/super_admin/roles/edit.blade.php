<form class="ajax reset" action="{{ route('super_admin.roles.update', $role->id) }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{__('Update Role')}}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name" class="form-label">{{ __('Role Name') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" name="name" id="name" value="{{$role->display_name}}" placeholder="{{ __('Enter role name') }}" required>
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
