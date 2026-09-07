<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Homepage Hero Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.frontend.landing-page.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Hero Title') }}<span class="required">*</span></label>
                    <input type="text" name="landing_hero_title"
                        value="{{ getOption('landing_hero_title', 'AI-Powered Social Media Scheduling Made Simple.') }}"
                        class="form-control" placeholder="{{ __('Enter compelling hero title') }}" required>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Hero Subtitle') }}<span class="required">*</span></label>
                    <input type="text" name="landing_hero_subtitle"
                        value="{{ getOption('landing_hero_subtitle', 'Plan, create, and publish smarter content automatically — all from one powerful, easy-to-use dashboard.') }}"
                        class="form-control" placeholder="{{ __('Enter engaging subtitle') }}" required>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Hero Button Text') }}</label>
                    <input type="text" name="landing_hero_button_text"
                        value="{{ getOption('landing_hero_button_text', 'Get Started For Free') }}" class="form-control"
                        placeholder="{{ __('Button text') }}">
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Hero Button URL') }}</label>
                    <input type="text" name="landing_hero_button_url"
                        value="{{ getOption('landing_hero_button_url', route('register')) }}" class="form-control"
                        placeholder="{{ __('Button URL') }}">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label d-block">{{ __('Hero Main Image') }}</label>
                    <div class="zImage-upload-details mw-100">
                        <div class="upload-img-box">
                            <img class="hero-image-preview-main"
                                src="@if(getOption('landing_hero_image')){{ getSettingImage('landing_hero_image') }}@else{{ asset('assets/images/dashboard.svg') }}@endif" />
                            <input type="file" name="landing_hero_image" accept="image/*"
                                onchange="previewFile(this)" />


                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label class="form-label d-block">{{ __('Hero Background Image') }}</label>
                    <div class="zImage-upload-details mw-100">
                        <div class="upload-img-box">
                            <img class="hero-image-preview-bg"
                                src="@if(getOption('landing_hero_background')){{ getSettingImage('landing_hero_background') }}@else{{ asset('assets/images/banner-bg.webp') }}@endif" />
                            <input type="file" name="landing_hero_background" accept="image/*"
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