<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Blog Section Configuration') }}</h4>
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
                    <label class="form-label">{{ __('Section Title') }}</label>
                    <input type="text" name="landing_blog_title" class="form-control"
                        value="{{ getOption('landing_blog_title', 'Latest From Our Blog.') }}"
                        placeholder="{{ __('Enter blog section title') }}">
                </div>
            </div>

            <div class="col-12 mt-3">
                <h5 class="mb-2">{{ __('Blog 1') }}</h5>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('Title') }} ({{ __('Blog') }} 1)</label>
                    <input type="text" name="landing_blog_post1_title" class="form-control"
                        value="{{ getOption('landing_blog_post1_title', __('How AI Improves Social Media Scheduling.')) }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('Date') }} ({{ __('Blog') }} 1)</label>
                    <input type="text" name="landing_blog_post1_date" class="form-control"
                        value="{{ getOption('landing_blog_post1_date', 'Mar 20, 2026') }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('URL') }} ({{ __('Blog') }} 1)</label>
                    <input type="text" name="landing_blog_post1_url" class="form-control"
                        value="{{ getOption('landing_blog_post1_url', '#') }}">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label d-block">{{ __('Image') }} ({{ __('Blog') }} 1)</label>
                    <div class="zImage-upload-details mw-100">
                        <div class="upload-img-box">
                            <img
                                src="@if(getOption('landing_blog_post1_image')){{ getSettingImage('landing_blog_post1_image') }}@else{{ asset('assets/images/blog/blog-1.webp') }}@endif" />
                            <input type="file" name="landing_blog_post1_image" accept="image/*"
                                onchange="previewFile(this)" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <h5 class="mb-2">{{ __('Blog 2') }}</h5>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('Title') }} ({{ __('Blog') }} 2)</label>
                    <input type="text" name="landing_blog_post2_title" class="form-control"
                        value="{{ getOption('landing_blog_post2_title', __('The Future of Social Media Management with AI.') ) }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('Date') }} ({{ __('Blog') }} 2)</label>
                    <input type="text" name="landing_blog_post2_date" class="form-control"
                        value="{{ getOption('landing_blog_post2_date', 'Mar 20, 2026') }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('URL') }} ({{ __('Blog') }} 2)</label>
                    <input type="text" name="landing_blog_post2_url" class="form-control"
                        value="{{ getOption('landing_blog_post2_url', '#') }}">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label d-block">{{ __('Image') }} ({{ __('Blog') }} 2)</label>
                    <div class="zImage-upload-details mw-100">
                        <div class="upload-img-box">
                            <img
                                src="@if(getOption('landing_blog_post2_image')){{ getSettingImage('landing_blog_post2_image') }}@else{{ asset('assets/images/blog/blog-2.webp') }}@endif" />
                            <input type="file" name="landing_blog_post2_image" accept="image/*"
                                onchange="previewFile(this)" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-3">
                <h5 class="mb-2">{{ __('Blog 3') }}</h5>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('Title') }} ({{ __('Blog') }} 3)</label>
                    <input type="text" name="landing_blog_post3_title" class="form-control"
                        value="{{ getOption('landing_blog_post3_title', __('General & technical support Envato Support Policy.') ) }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('Date') }} ({{ __('Blog') }} 3)</label>
                    <input type="text" name="landing_blog_post3_date" class="form-control"
                        value="{{ getOption('landing_blog_post3_date', 'Mar 20, 2025') }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label class="form-label">{{ __('URL') }} ({{ __('Blog') }} 3)</label>
                    <input type="text" name="landing_blog_post3_url" class="form-control"
                        value="{{ getOption('landing_blog_post3_url', '#') }}">
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label d-block">{{ __('Image') }} ({{ __('Blog') }} 3)</label>
                    <div class="zImage-upload-details mw-100">
                        <div class="upload-img-box">
                            <img
                                src="@if(getOption('landing_blog_post3_image')){{ getSettingImage('landing_blog_post3_image') }}@else{{ asset('assets/images/blog/blog-3.webp') }}@endif" />
                            <input type="file" name="landing_blog_post3_image" accept="image/*"
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