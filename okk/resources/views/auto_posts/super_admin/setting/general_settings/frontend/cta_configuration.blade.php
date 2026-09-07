<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('CTA Section Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.frontend.landing-page.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label">{{ __('CTA Title') }}</label>
                    <input type="text" name="landing_cta_title" class="form-control"
                        value="{{ getOption('landing_cta_title', '') }}"
                        placeholder="{{ __('Enter CTA title') }}">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label">{{ __('CTA Button Text') }}</label>
                    <input type="text" name="landing_cta_button_text" class="form-control"
                        value="{{ getOption('landing_cta_button_text', '') }}"
                        placeholder="{{ __('Enter CTA button text') }}">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label">{{ __('CTA Description') }}</label>
                    <textarea name="landing_cta_description" class="form-control" rows="2"
                        placeholder="{{ __('Enter CTA description') }}">{{ getOption('landing_cta_description', '') }}</textarea>
                </div>
            </div>

            <div class="col-12 mt-3">
                <h5 class="mb-2">{{ __('Images') }}</h5>
            </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label d-block">{{ __('CTA Background Image') }}</label>
                    <div class="zImage-upload-details mw-100">
                        <div class="upload-img-box">
                            <img src="@if(getOption('landing_cta_background')){{ getSettingImage('landing_cta_background') }}@else{{ asset('assets/images/cta-bg.png') }}@endif" />
                            <input type="file" name="landing_cta_background" accept="image/*"
                                onchange="previewFile(this)" />
                        </div>
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
</div>