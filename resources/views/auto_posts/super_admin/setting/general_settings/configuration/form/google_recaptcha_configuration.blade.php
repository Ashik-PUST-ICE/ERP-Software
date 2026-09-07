<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Google Recaptcha Credentials') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<form class="ajax" action="{{route('super_admin.setting.common.settings.update')}}" method="post" class="form-horizontal"
      data-handler="commonResponseForModal">
    @csrf
    
    <div class="primary-form">
        <div class="row gy-3">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Google Recaptcha Site Key') }}</label>
                    <input type="text" name="google_recaptcha_site_key" id="google_recaptcha_site_key"
                           value="{{getOption('google_recaptcha_site_key')}}" class="form-control" placeholder="{{ __('Google Recaptcha Site Key') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Google Recaptcha Secret Key') }}</label>
                    <input type="text" name="google_recaptcha_secret_key" id="google_recaptcha_secret_key"
                           value="{{getOption('google_recaptcha_secret_key')}}" class="form-control" placeholder="{{ __('Google Recaptcha Secret Key') }}">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Buttons -->
    <div class="btn-list mt-4 pt-3 border-top">
        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="primary-btn">{{__('Update')}}</button>
    </div>
</form>
