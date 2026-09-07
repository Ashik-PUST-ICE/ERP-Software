<form id="edit-user-package-form" method="POST"
    action="{{ route('super_admin.packages.update_user_package', $userPackage->id) }}">
    @csrf
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Edit User Package Status') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label for="status" class="form-label">{{ __('Status') }}<span class="required">*</span></label>
                        <select class="select form-control wide sf-select-without-search" id="status" name="status"
                            required>
                            <option value="{{ STATUS_ACTIVE }}"
                                {{ $userPackage->status == STATUS_ACTIVE ? 'selected' : '' }}>{{ __('Active') }}
                            </option>
                            <option value="{{ STATUS_CANCELLED }}"
                                {{ $userPackage->status == STATUS_CANCELLED ? 'selected' : '' }}>{{ __('Deactive') }}
                            </option>
                            <option value="{{ STATUS_REFUND }}"
                                {{ $userPackage->status == STATUS_REFUND ? 'selected' : '' }}>{{ __('Refund') }}
                            </option>
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
<script src="{{ asset('super_admin/js/edit-user-package.js') }}"></script>