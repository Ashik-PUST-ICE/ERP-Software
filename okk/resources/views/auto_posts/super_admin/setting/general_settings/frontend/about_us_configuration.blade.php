<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('About Us Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="settingCommonHandler">
        @csrf
        <div class="row gy-4">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Section Title') }}<span class="required">*</span></label>
                    <input type="text" name="about_us_section_title" value="{{ getOption('about_us_section_title') }}"
                        class="form-control" placeholder="{{ __('Enter section title') }}" required>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    <label class="form-label">{{ __('Section Subtitle') }}</label>
                    <input type="text" name="about_us_section_subtitle" value="{{ getOption('about_us_section_subtitle') }}"
                        class="form-control" placeholder="{{ __('Enter section subtitle') }}">
                </div>
            </div>

            <div class="col-12">
            <div class="form-group">
                <label class="form-label">{{ __('Section Description') }}</label>
                <textarea name="about_us_section_description" class="form-control"
                    placeholder="{{ __('Describe the about us section') }}" rows="4">{!! getOption('about_us_section_description') !!}</textarea>
            </div>
        </div>

            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label">{{ __('About Us Image') }}</label>
                    <div class="zImage-upload-details mw-100">
                        
                        <div class="upload-img-box">
                            <img src="@if(getOption('about_us_image')){{ getSettingImage('about_us_image') }}@else{{ asset('assets/images/no-image.jpg') }}@endif" />
                            <input type="file" name="about_us_image" id="about_us_image" accept="image/*"
                                onchange="previewFile(this)" />
                        </div>
                    </div>
                    @if ($errors->has('about_us_image'))
                    <span class="text-danger">
                        <i class="fas fa-exclamation-triangle"></i> {{ $errors->first('about_us_image') }}
                    </span>
                    @endif
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
