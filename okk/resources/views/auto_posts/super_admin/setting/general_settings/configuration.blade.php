@extends('auto_posts.super_admin.layouts.app')
@push('admin-style')
<link rel="stylesheet" href="{{ asset('admin/styles/main.css') }}">
@endpush
@push('title')
{{ $title }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <input type="hidden" id="statusChangeRoute"
                value="{{ route('super_admin.setting.configuration-settings.update') }}">
            <input type="hidden" id="configureUrl"
                value="{{ route('super_admin.setting.configuration-settings.configure') }}">
            <input type="hidden" id="helpUrl" value="{{ route('super_admin.setting.configuration-settings.help') }}">

            <form class="ajax" action="{{ route('super_admin.setting.configuration-settings.update') }}" method="POST"
                enctype="multipart/form-data" data-handler="settingCommonHandler">
                @csrf

                <table class="display data-table primary-table">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('Extension') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="keep-show text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Email Verification') }}</h4>
                                    <p>({{ __('If you enable Email Verification, new user have to verify the email to access this system.') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'email_verification_status')" value="1"
                                        {{ getOption('email_verification_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="email_verification_status" id="email_verification_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn"
                                        onclick="configureModal('email_verification_status')"
                                        title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('email_verification_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('E-mail credentials status') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for sending email') }})</p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'app_mail_status')" value="1"
                                        {{ getOption('app_mail_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="app_mail_status" id="app_mail_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn"
                                        onclick="configureModal('app_mail_status')" title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('app_mail_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('SMS credentials status') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for sending sms') }})</p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'app_sms_status')" value="1"
                                        {{ getOption('app_sms_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="app_sms_status" id="app_sms_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn" onclick="configureModal('app_sms_status')"
                                        title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('app_sms_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Pusher credentials status') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for pusher') }})</p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'pusher_status')" value="1"
                                        {{ getOption('pusher_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="pusher_status" id="pusher_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn" onclick="configureModal('pusher_status')"
                                        title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('pusher_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Social Login (Google)') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for Google. User can use our gmail account and sign in') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'google_login_status')" value="1"
                                        {{ getOption('google_login_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="google_login_status" id="google_login_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn"
                                        onclick="configureModal('google_login_status')" title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('google_login_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Social Login (Facebook)') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for Facebook. User can use our facebook account and sign in') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'facebook_login_status')" value="1"
                                        {{ getOption('facebook_login_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="facebook_login_status" id="facebook_login_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn"
                                        onclick="configureModal('facebook_login_status')" title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('facebook_login_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Google Recaptcha Credentials') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for google recaptcha credentials') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'google_recaptcha_status')" value="1"
                                        {{ getOption('google_recaptcha_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="google_recaptcha_status" id="google_recaptcha_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn"
                                        onclick="configureModal('google_recaptcha_status')"
                                        title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('google_recaptcha_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Google Analytics') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for google analytics.') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'google_analytics_status')" value="1"
                                        {{ getOption('google_analytics_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="google_analytics_status" id="google_analytics_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn"
                                        onclick="configureModal('google_analytics_status')"
                                        title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('google_analytics_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Cookie Consent') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for cookie consent settings. User Can manage cookie consent setting') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'cookie_status')" value="1"
                                        {{ getOption('cookie_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="cookie_status" id="cookie_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn" onclick="configureModal('cookie_status')"
                                        title="{{ __('Configure') }}">
                                        {{ __('Configure') }}
                                    </button>
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('cookie_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Google 2fa') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for google 2fa. By wearing it you will know how this setting works') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'two_factor_googleauth_status')" value="1"
                                        {{ getOption('two_factor_googleauth_status', 0)==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="two_factor_googleauth_status" id="two_factor_googleauth_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('two_factor_googleauth_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Preloader') }}</h4>
                                    <p>({{ __('If you enable preloader, the preloader will be show before load the content.') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'app_preloader_status')" value="1"
                                        {{ getOption('app_preloader_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="app_preloader_status" id="app_preloader_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('app_preloader_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Show Language Switcher') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for show language switcher. By wearing it you will know how this setting works.') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'show_language_switcher')" value="1"
                                        {{ getOption('show_language_switcher')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="show_language_switcher" id="show_language_switcher">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('show_language_switcher')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('App Debug') }}</h4>
                                    <p>({{ __('If you enable this.No warning message will be shown for any error. By wearing it you will know how this setting works.') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'app_debug')" value="1"
                                        {{ getOption('app_debug')==STATUS_ACTIVE ? 'checked' : '' }} name="app_debug"
                                        id="app_debug">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('app_debug')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Force SSL') }}</h4>
                                    <p>({{ __('If you enable this Force SSL will be enable.') }})</p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'force_ssl')" value="1"
                                        {{ getOption('force_ssl')==STATUS_ACTIVE ? 'checked' : '' }} name="force_ssl"
                                        id="force_ssl">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('force_ssl')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="email-extension">
                                    <h4>{{ __('Coupon System') }}</h4>
                                    <p>({{ __('If you enable this. The system will enable for coupon system. Users can apply coupons during subscription.') }})
                                    </p>
                                </div>
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="form-check-input"
                                        onchange="changeSettingStatus(this,'coupon_system_status')" value="1"
                                        {{ getOption('coupon_system_status')==STATUS_ACTIVE ? 'checked' : '' }}
                                        name="coupon_system_status" id="coupon_system_status">
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <div class="btn-list mt-0">
                                    <button type="button" class="primary-btn btn-secondary"
                                        onclick="helpModal('coupon_system_status')" title="{{ __('Help') }}">
                                        {{ __('Help') }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <!-- <div class="table-responsive">
                </div> -->
            </form>
        </div>
    </div>
</div>

<!-- Configuration section start -->
<div class="modal fade zModalTwo" id="configureModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content zModalTwo-content">

        </div>
    </div>
</div>
<!-- Configuration section end -->

<!-- Help section start -->
<div class="modal fade zModalTwo" id="helpModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content zModalTwo-content">

        </div>
    </div>
</div>
<!-- Help section end -->

<!-- Test Email section start -->
<div class="modal fade zModalTwo" id="sendTestMail" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="POST" action="{{ route('super_admin.setting.mail.test') }}">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Test Mail') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <img src="{{ asset('assets/images/icon/delete.svg') }}" alt="" />
                            </button>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="to" class="form-label">{{ __('Recipient') }}<span
                                            class="required">*</span></label>
                                    <input type="email" name="to" class="form-control" id="to"
                                        placeholder="{{ __('Recipient Mail') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="subject" class="form-label">{{ __('Subject') }}<span
                                            class="required">*</span></label>
                                    <input type="text" name="subject" class="form-control" id="subject"
                                        placeholder="{{ __('Subject') }}" value="Test Mail" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message-text" class="form-label">{{ __('Your Message') }}<span
                                            class="required">*</span></label>
                                    <textarea name="message" class="form-control" id="message-text"
                                        rows="4">{{ __('Hi, This is a test mail') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Send') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- TEST EMail section end -->

<!-- TEST SMS section start -->
<div class="modal fade zModalTwo" id="sendTestSMS" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="{{ route('super_admin.setting.sms.test') }}" method="post"
                enctype="multipart/form-data" data-handler="commonResponseForModal">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Test SMS') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <img src="{{ asset('assets/images/icon/delete.svg') }}" alt="" />
                            </button>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="to-sms" class="form-label">{{ __('Recipient Number') }}<span
                                            class="required">*</span></label>
                                    <input type="text" name="to" class="form-control" id="to-sms"
                                        placeholder="{{ __('Recipient Number') }}" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="message-text-sms" class="form-label">{{ __('Your Message') }}<span
                                            class="required">*</span></label>
                                    <textarea name="message" class="form-control" id="message-text-sms"
                                        rows="4">{{ __('Hi, This is a test sms') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Send') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- TEST SMS section end -->
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/configuration.css') }}">
@endpush

@push('script')
<script src="{{ asset('admin/js/configuration.js') }}"></script>
@endpush