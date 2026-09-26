@extends('auto_posts.super_admin.layouts.app')

@push('title')
{{ $title }}
@endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.landing-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <form class="ajax primary-form" action="{{ route('super_admin.setting.frontend.landing-page.update') }}"
                method="POST" enctype="multipart/form-data" data-handler="commonResponseForModal">
                @csrf
                <div class="row gy-4">
                    <div class="col-12">
                        <h4 class="mb-3">{{ __('Section Status') }}</h4>
                        <p class="text-muted small">{{ __('Show or hide sections on the landing page.') }}</p>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Title') }}</label>
                            <input type="text" name="landing_hero_title" class="form-control"
                                value="{{ getOption('landing_hero_title', 'All-in-One Garments ERP Software for Modern Factories.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Subtitle') }}</label>
                            <input type="text" name="landing_hero_subtitle" class="form-control"
                                value="{{ getOption('landing_hero_subtitle', 'Streamline HRM, inventory, production, payroll, and accounting — all in one powerful ERP built for garment manufacturers.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Primary Button Text') }}</label>
                            <input type="text" name="landing_hero_button_text" class="form-control"
                                value="{{ getOption('landing_hero_button_text', 'Request a Demo') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Primary Button URL') }}</label>
                            <input type="text" name="landing_hero_button_url" class="form-control"
                                value="{{ getOption('landing_hero_button_url', route('register')) }}">
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <h4 class="mb-3">{{ __('Section Titles') }}</h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Features Section Title') }}</label>
                            <input type="text" name="landing_features_title" class="form-control"
                                value="{{ getOption('landing_features_title', 'Powerful ERP Modules to Run Your Entire Business.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('ERP Modules Section Title') }}</label>
                            <input type="text" name="landing_post_management_title" class="form-control"
                                value="{{ getOption('landing_post_management_title', 'Core ERP Modules Built for Garment Manufacturers.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Campaign Section Title') }}</label>
                            <input type="text" name="landing_campaign_title" class="form-control"
                                value="{{ getOption('landing_campaign_title', 'Why Garment Factories Choose Our ERP.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Tools Section Title') }}</label>
                            <input type="text" name="landing_tools_title" class="form-control"
                                value="{{ getOption('landing_tools_title', 'Everything You Need to Manage Garment Production.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Pricing Section Title') }}</label>
                            <input type="text" name="landing_pricing_title" class="form-control"
                                value="{{ getOption('landing_pricing_title', 'Simple ERP Pricing for Every Factory Size.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Testimonials Section Title') }}</label>
                            <input type="text" name="landing_testimonials_title" class="form-control"
                                value="{{ getOption('landing_testimonials_title', 'What Our Customers Are Saying.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('Blog Section Title') }}</label>
                            <input type="text" name="landing_blog_title" class="form-control"
                                value="{{ getOption('landing_blog_title', 'Latest ERP Insights & Garment Industry Updates.') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('FAQ Section Title') }}</label>
                            <input type="text" name="landing_faq_title" class="form-control"
                                value="{{ getOption('landing_faq_title', 'Frequently Asked Questions.') }}">
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <h4 class="mb-3">{{ __('CTA Section') }}</h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('CTA Title') }}</label>
                            <input type="text" name="landing_cta_title" class="form-control"
                                value="{{ getOption('landing_cta_title', 'Ready to Modernize Your Garment Factory?') }}">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">{{ __('CTA Button Text') }}</label>
                            <input type="text" name="landing_cta_button_text" class="form-control"
                                value="{{ getOption('landing_cta_button_text', 'Start Your Free Trial') }}">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-label">{{ __('CTA Description') }}</label>
                            <textarea name="landing_cta_description" class="form-control"
                                rows="3">{{ getOption('landing_cta_description', 'Streamline your entire garment manufacturing process with our all-in-one ERP. From HRM to production to accounting — one platform, full control.') }}</textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <h4 class="mb-3">{{ __('Images') }}</h4>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block">{{ __('Hero Main Image') }}</label>
                            @if(getOption('landing_hero_image'))
                            <div class="mb-2">
                                <img src="{{ getOption('landing_hero_image') }}" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            @endif
                            <input type="file" name="landing_hero_image" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block">{{ __('Hero Background Image') }}</label>
                            @if(getOption('landing_hero_background'))
                            <div class="mb-2">
                                <img src="{{ getOption('landing_hero_background') }}" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            @endif
                            <input type="file" name="landing_hero_background" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label
                                class="form-label d-block">{{ __('Post Management - Manage All Posts Image') }}</label>
                            @if(getOption('landing_post_manage_all_image'))
                            <div class="mb-2">
                                <img src="{{ getOption('landing_post_manage_all_image') }}" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            @endif
                            <input type="file" name="landing_post_manage_all_image" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block">{{ __('CTA Background Image') }}</label>
                            @if(getOption('landing_cta_background'))
                            <div class="mb-2">
                                <img src="{{ getOption('landing_cta_background') }}" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            @endif
                            <input type="file" name="landing_cta_background" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block">{{ __('CTA Connect Image 1') }}</label>
                            @if(getOption('landing_cta_connect_one'))
                            <div class="mb-2">
                                <img src="{{ getOption('landing_cta_connect_one') }}" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            @endif
                            <input type="file" name="landing_cta_connect_one" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block">{{ __('CTA Connect Image 2') }}</label>
                            @if(getOption('landing_cta_connect_two'))
                            <div class="mb-2">
                                <img src="{{ getOption('landing_cta_connect_two') }}" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            @endif
                            <input type="file" name="landing_cta_connect_two" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="primary-btn">
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection