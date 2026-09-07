@extends('auto_posts.super_admin.layouts.app')
@push('admin-style')
<link rel="stylesheet" href="{{ asset('admin/styles/main.css') }}">
@endpush
@push('title')
{{ $title }}
@endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title)    }}</h2>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <input type="hidden" id="statusChangeRoute"
                value="{{ route('super_admin.setting.configuration-settings.update') }}">
            <input type="hidden" id="configureUrl"
                value="{{ route('super_admin.setting.configuration-settings.configure') }}">

            <form class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}" method="POST"
                enctype="multipart/form-data" data-handler="settingCommonHandler">
                @csrf

                <div class="table-responsive">
                    <table class="display data-table primary-table">
                        <thead>
                            <tr>
                                <th class="keep-show">{{ __('Frontend Feature') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th class="keep-show text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Homepage Hero Section') }}</h4>
                                        <p>({{ __('Control the visibility of the homepage hero section') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'homepage_hero_status')" value="1"
                                            {{ getOption('homepage_hero_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="homepage_hero_status" id="homepage_hero_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('homepage_hero_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Features Section') }}</h4>
                                        <p>({{ __('Show/hide the features section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'features_section_status')" value="1"
                                            {{ getOption('features_section_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="features_section_status" id="features_section_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('features_section_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Post Management Section') }}</h4>
                                        <p>({{ __('Show/hide the post management section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_post_management_status')"
                                            value="1"
                                            {{ getOption('landing_post_management_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_post_management_status" id="landing_post_management_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_post_management_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Campaign Calendar Section') }}</h4>
                                        <p>({{ __('Show/hide the campaign calendar section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_campaign_status')" value="1"
                                            {{ getOption('landing_campaign_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_campaign_status" id="landing_campaign_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_campaign_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Our Tool Section') }}</h4>
                                        <p>({{ __('Show/hide the tools section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_tools_status')" value="1"
                                            {{ getOption('landing_tools_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_tools_status" id="landing_tools_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_tools_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Pricing Section') }}</h4>
                                        <p>({{ __('Show/hide the pricing section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_pricing_status')" value="1"
                                            {{ getOption('landing_pricing_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_pricing_status" id="landing_pricing_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_pricing_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Testimonials Section') }}</h4>
                                        <p>({{ __('Show/hide the testimonials section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_testimonials_status')" value="1"
                                            {{ getOption('landing_testimonials_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_testimonials_status" id="landing_testimonials_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_testimonials_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Blog Section') }}</h4>
                                        <p>({{ __('Show/hide the blog section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_blog_status')" value="1"
                                            {{ getOption('landing_blog_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_blog_status" id="landing_blog_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <a href="{{ route('super_admin.setting.blogs.index') }}" class="primary-btn"
                                            title="{{ __('Manage Blogs') }}">
                                            {{ __('Configure') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('FAQ Section') }}</h4>
                                        <p>({{ __('Show/hide the FAQ section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_faq_status')" value="1"
                                            {{ getOption('landing_faq_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_faq_status" id="landing_faq_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_faq_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('CTA Section') }}</h4>
                                        <p>({{ __('Show/hide the CTA section on homepage') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_cta_status')" value="1"
                                            {{ getOption('landing_cta_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="landing_cta_status" id="landing_cta_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_cta_status')"
                                            title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Footer Left Content') }}</h4>
                                        <p>({{ __('Set footer left text shown under logo') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'footer_left_status')" value="1"
                                            {{ getOption('footer_left_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="footer_left_status" id="footer_left_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('footer_content')" title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Footer Social Media') }}</h4>
                                        <p>({{ __('Configure social media links in footer') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'footer_social_status')" value="1"
                                            {{ getOption('footer_social_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="footer_social_status" id="footer_social_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('footer_social')" title="{{ __('Configure') }}">
                                            {{ __('Configure') }}
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4>{{ __('Footer Right Links') }}</h4>
                                        <p>({{ __('Show/hide footer right menu links') }})</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'footer_right_status')" value="1"
                                            {{ getOption('footer_right_status', 1)==STATUS_ACTIVE ? 'checked' : '' }}
                                            name="footer_right_status" id="footer_right_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <a href="{{ route('super_admin.setting.menu.footer-right') }}" class="primary-btn"
                                            title="{{ __('Manage Footer Right Menus') }}">
                                            {{ __('Manage Menus') }}
                                        </a>
                                    </div>
                                </td>
                            </tr> -->

                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Configure Modal -->
<div class="modal fade zModalTwo" id="configureModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content zModalTwo-content">

        </div>
    </div>
</div>


@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/frontend.css') }}">
@endpush

@push('script')
<script src="{{ asset('admin/js/configuration.js') }}"></script>
@endpush
@endsection