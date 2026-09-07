<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Footer Left Content Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}" method="POST"
        data-handler="commonResponseForModal">
        @csrf
        <div class="row gy-4">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Footer Left Text') }}</label>
                    <textarea name="footer_left_text" class="form-control" rows="4"
                        placeholder="{{ __('Enter footer left text...') }}">{{ getOption('footer_left_text') }}</textarea>
                    <p class="mt-2 text-muted mb-0">{{ __('Leave empty to show nothing by default.') }}</p>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Save') }}</button>
        </div>
    </form>
</div>
