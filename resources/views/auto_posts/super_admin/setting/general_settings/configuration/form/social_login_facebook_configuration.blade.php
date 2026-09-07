<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Social Login (Facebook) Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<form class="ajax" action="{{route('super_admin.setting.common.settings.update')}}" method="POST"
      enctype="multipart/form-data" data-handler="commonResponseForModal">
    @csrf
    
    <div class="primary-form">
        <div class="row gy-3">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Facebook Client ID') }}</label>
                    <input type="text" name="facebook_client_id" id="facebook_client_id"
                           value="{{getOption('facebook_client_id')}}" class="form-control" placeholder="{{ __('Facebook Client ID') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Facebook Client Secret') }}</label>
                    <input type="text" name="facebook_client_secret" id="facebook_client_secret"
                           value="{{getOption('facebook_client_secret')}}" class="form-control" placeholder="{{ __('Facebook Client Secret') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="alert alert-info">
                    <strong>{{ __('Set callback URL') }}:</strong> {{ url('/auth/facebook/callback') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Buttons -->
    <div class="btn-list mt-4 pt-3 border-top">
        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="primary-btn">{{ __('Save') }}</button>
    </div>
</form>
