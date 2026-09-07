<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{__('Cookie Configuration')}}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<form class="ajax" action="{{ route('super_admin.setting.common.settings.update') }}" method="post"
      class="form-horizontal" data-handler="commonResponseForModal">
    @csrf
    
    <div class="primary-form">
        <div class="row gy-3">
            <div class="col-12">
                <div class="form-group">
                    <label class="form-label">{{__('Cookie Consent Text')}}</label>
                    <textarea class="form-control" name="cookie_consent_text" rows="6" placeholder="{{__('Cookie Consent Text')}}">{{getOption('cookie_consent_text')}}</textarea>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Buttons -->
    <div class="btn-list mt-4 pt-3 border-top">
        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="submit" class="primary-btn">{{ __('Update') }}</button>
    </div>
</form>
