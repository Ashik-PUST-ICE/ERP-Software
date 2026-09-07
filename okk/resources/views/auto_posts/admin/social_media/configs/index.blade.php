@extends('auto_posts.admin.layouts.admin')
@push('title')
{{ $title ?? 'Social Media Configurations' }}
@endpush
@section('content')

{{-- Platform Overview - Stats (same design as platform.html) --}}
<div class="section-title">
    <h2 class="title">{{ __('Platform Overview') }}</h2>
</div>
<div class="platform-card-wrap">
    <div class="platform-card">
        <span class="card-icon">
            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="19" cy="19" r="19" fill="#02BCFF" />
                <path
                    d="M22.3332 16.6666C22.3332 14.8256 20.8408 13.3333 18.9998 13.3333C17.1589 13.3333 15.6665 14.8256 15.6665 16.6666C15.6665 18.5075 17.1589 19.9999 18.9998 19.9999C20.8408 19.9999 22.3332 18.5075 22.3332 16.6666Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M23.6668 24.6667C23.6668 22.0893 21.5775 20 19.0002 20C16.4228 20 14.3335 22.0893 14.3335 24.6667"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <div class="card-info">
            <h2>{{ $stats['total_configs'] }}</h2>
            <h3>{{ __('Total Configs') }}</h3>
        </div>
    </div>
    <div class="platform-card">
        <span class="card-icon">
            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="19" cy="19" r="19" fill="#0D0D0D" />
                <path
                    d="M21.3332 18.3333C21.3332 17.0447 20.2885 16 18.9998 16C17.7112 16 16.6665 17.0447 16.6665 18.3333C16.6665 19.622 17.7112 20.6667 18.9998 20.6667C20.2885 20.6667 21.3332 19.622 21.3332 18.3333Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M21.3218 18.5666C21.5365 18.6317 21.7642 18.6667 22 18.6667C23.2887 18.6667 24.3334 17.622 24.3334 16.3333C24.3334 15.0447 23.2887 14 22 14C20.7901 14 19.7952 14.9209 19.6782 16.1001"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M18.3216 16.1001C18.2046 14.9209 17.2098 14 15.9998 14C14.7112 14 13.6665 15.0447 13.6665 16.3333C13.6665 17.622 14.7112 18.6667 15.9998 18.6667C16.2357 18.6667 16.4634 18.6317 16.678 18.5666"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M25.6667 22.0001C25.6667 20.1591 24.0251 18.6667 22 18.6667" stroke="white" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M22.6668 24.0001C22.6668 22.1591 21.0252 20.6667 19.0002 20.6667C16.9751 20.6667 15.3335 22.1591 15.3335 24.0001"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.0002 18.6667C13.9751 18.6667 12.3335 20.1591 12.3335 22.0001" stroke="white"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <div class="card-info">
            <h2>{{ $stats['active_configs'] }}</h2>
            <h3>{{ __('Active Configs') }}</h3>
        </div>
    </div>
    <div class="platform-card">
        <span class="card-icon">
            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="19" cy="19" r="19" fill="#0FA958" />
                <path
                    d="M24.6668 20.3333V17.6666C24.6668 15.1524 24.6668 13.8954 23.8858 13.1143C23.1047 12.3333 21.8476 12.3333 19.3334 12.3333H18.6668C16.1527 12.3333 14.8956 12.3333 14.1146 13.1143C13.3335 13.8953 13.3335 15.1524 13.3335 17.6665L13.3335 20.3332C13.3335 22.8474 13.3335 24.1045 14.1145 24.8855C14.8956 25.6666 16.1526 25.6666 18.6668 25.6666H19.3334C21.8476 25.6666 23.1047 25.6666 23.8858 24.8855C24.6668 24.1045 24.6668 22.8474 24.6668 20.3333Z"
                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M16.3335 15.6667H21.6668M16.3335 19.0001H21.6668M16.3335 22.3334H19.0002" stroke="white"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <div class="card-info">
            <h2>{{ $stats['total_accounts'] }}</h2>
            <h3>{{ __('Total Accounts') }}</h3>
        </div>
    </div>
    <div class="platform-card">
        <span class="card-icon">
            <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="19" cy="19" r="19" fill="#FF4F02" />
                <path d="M13.1279 24.182L24.1825 13.1274" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                <path
                    d="M16.0766 13.6333C16.7511 14.3079 16.7511 15.4015 16.0766 16.0761C15.402 16.7506 14.3084 16.7506 13.6338 16.0761C12.9593 15.4015 12.9593 14.3079 13.6338 13.6333C14.3084 12.9588 15.402 12.9588 16.0766 13.6333Z"
                    stroke="white" stroke-width="1.5" />
                <path
                    d="M23.6767 21.2332C24.3512 21.9077 24.3512 23.0014 23.6767 23.676C23.0022 24.3505 21.9085 24.3505 21.2339 23.676C20.5594 23.0014 20.5594 21.9077 21.2339 21.2332C21.9085 20.5587 23.0022 20.5587 23.6767 21.2332Z"
                    stroke="white" stroke-width="1.5" />
            </svg>
        </span>
        <div class="card-info">
            <h2>{{ $stats['active_accounts'] }}</h2>
            <h3>{{ __('Active Accounts') }}</h3>
        </div>
    </div>
</div>

{{-- Connected Platforms (same design as platform.html) --}}
<div class="mt-20">
    <div class="section-medium-title">
        <h2 class="title">{{ __('Connected Platforms') }}</h2>
        <a href="{{ route('admin.social.account.index') }}" class="primary-btn">+{{ __('Connect Account') }}</a>
    </div>
    <div class="row gy-4">
        @foreach($platforms as $platformKey => $platform)
        @php
        $config = $configs->where('platform', $platformKey)->first();
        $isConfigured = $config !== null;
        $isActive = $isConfigured && $config->is_active;
        $accountCount = $isConfigured ? $config->socialMediaAccounts()->count() : 0;
        @endphp
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="single-platform">
                <div class="platform-top">
                    <div class="platform-area">
                        @include('auto_posts.admin.social_media.configs.partials.platform_icon_svg', ['platformKey' =>
                        $platformKey])
                        <div class="platform-info">
                            <h3>{{ $platform['name'] }}</h3>
                            <h4>{{ $isConfigured ? __('Last Updated') . ' : ' . $config->updated_at->diffForHumans() : __('Not Configured') }}
                            </h4>
                        </div>
                    </div>
                    <div class="dropdown options-area">
                        <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-ellipsis"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item open-settings-btn" href="#"
                                    data-platform="{{ $platformKey }}">{{ __('Settings') }}</a></li>
                        </ul>
                    </div>
                </div>
                <div class="platform-body">
                    <div class="single-info">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.3332 7.33333C10.3332 6.04467 9.2885 5 7.99984 5C6.71117 5 5.6665 6.04467 5.6665 7.33333C5.6665 8.622 6.71117 9.66667 7.99984 9.66667C9.2885 9.66667 10.3332 8.622 10.3332 7.33333Z"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M10.3218 7.5666C10.5365 7.63167 10.7642 7.66667 11 7.66667C12.2887 7.66667 13.3334 6.622 13.3334 5.33333C13.3334 4.04467 12.2887 3 11 3C9.79009 3 8.79522 3.92093 8.67822 5.10009"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M7.32164 5.10009C7.20464 3.92093 6.20978 3 4.99984 3C3.71117 3 2.6665 4.04467 2.6665 5.33333C2.6665 6.622 3.71117 7.66667 4.99984 7.66667C5.23571 7.66667 5.4634 7.63167 5.67802 7.5666"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M14.6667 11.0001C14.6667 9.15915 13.0251 7.66675 11 7.66675"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M11.6668 13.0001C11.6668 11.1591 10.0252 9.66675 8.00016 9.66675C5.97512 9.66675 4.3335 11.1591 4.3335 13.0001"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M5.00016 7.66675C2.97512 7.66675 1.3335 9.15915 1.3335 11.0001"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            {{ $accountCount }}
                        </h3>
                        <h4>{{ __('Accounts') }}</h4>
                    </div>
                    <div class="single-info">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M14.3628 7.36325C14.5655 7.64745 14.6668 7.78959 14.6668 7.99992C14.6668 8.21025 14.5655 8.35238 14.3628 8.63658C13.4521 9.91365 11.1263 12.6666 8.00016 12.6666C4.87402 12.6666 2.54823 9.91365 1.63752 8.63658C1.43484 8.35238 1.3335 8.21025 1.3335 7.99992C1.3335 7.78959 1.43484 7.64745 1.63752 7.36325C2.54823 6.08621 4.87402 3.33325 8.00016 3.33325C11.1263 3.33325 13.4521 6.08621 14.3628 7.36325Z"
                                    stroke="currentColor" stroke-width="1.3" />
                                <path
                                    d="M10 8C10 6.8954 9.1046 6 8 6C6.8954 6 6 6.8954 6 8C6 9.1046 6.8954 10 8 10C9.1046 10 10 9.1046 10 8Z"
                                    stroke="currentColor" stroke-width="1.3" />
                            </svg>
                            @if($isConfigured)
                            {{ $isActive ? __('Active') : __('Inactive') }}
                            @else
                            —
                            @endif
                        </h3>
                        <h4>{{ __('Status') }}</h4>
                    </div>
                    <div class="single-info">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M13.6668 9.33325V6.66658C13.6668 4.15243 13.6668 2.89535 12.8858 2.1143C12.1047 1.33325 10.8476 1.33325 8.33343 1.33325H7.66683C5.15272 1.33325 3.89565 1.33325 3.1146 2.11429C2.33355 2.89533 2.33354 4.1524 2.33352 6.66654L2.3335 9.33318C2.33348 11.8474 2.33346 13.1045 3.11452 13.8855C3.89556 14.6666 5.15265 14.6666 7.66683 14.6666H8.33343C10.8476 14.6666 12.1047 14.6666 12.8858 13.8855C13.6668 13.1045 13.6668 11.8474 13.6668 9.33325Z"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M5.3335 4.66675H10.6668M5.3335 8.00008H10.6668M5.3335 11.3334H8.00016"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            @if($isConfigured)
                            {{ $config->updated_at->diffForHumans() }}
                            @else
                            —
                            @endif
                        </h3>
                        <h4>{{ __('Last Updated') }}</h4>
                    </div>
                    <div class="single-info">
                        <h3>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.2964 5.36046H7.21896M1.3335 12L4.99858 8.3908C5.13959 8.25193 5.35988 8.23627 5.51887 8.35387L8.34943 10.4475C8.51616 10.5709 8.74876 10.5469 8.88723 10.3922L14.1901 4.46841M12.0724 4H13.9539C14.3192 4 14.6174 4.29385 14.6249 4.66119L14.6668 6.70993"
                                    stroke="currentColor" stroke-width="1.3" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            @if($isConfigured)
                            <a href="{{ route('admin.analytics.platform', $platformKey) }}"
                                class="text-decoration-none">{{ __('View') }}</a>
                            @else
                            <a href="#" class="open-create-btn text-decoration-none"
                                data-platform="{{ $platformKey }}">{{ __('Configure') }}</a>
                            @endif
                        </h3>
                        <h4>{{ __('Action') }}</h4>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- API Settings Modal (same design as platform.html APISettingsModal) --}}
<div class="modal fade primary-modal" id="APISettingsModal" tabindex="-1" aria-labelledby="APISettingsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="APISettingsModalLabel">{{ __('API Settings') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="APISettingsModalBody">
                @foreach($platforms as $platformKey => $platform)
                @php
                $config = $configs->where('platform', $platformKey)->first();
                $isNewConfig = false;
                // Create empty config object if no config exists to avoid errors
                if (!$config) {
                $config = new \App\Models\SocialMediaConfig([
                'platform' => $platformKey,
                'app_id' => '',
                'app_secret' => '',
                'redirect_uri' => '',
                'permissions' => [],
                'settings' => [],
                'is_active' => false
                ]);
                $isNewConfig = true;
                }
                @endphp
                <div class="settings-form-container" id="settings-form-{{ $platformKey }}" style="display: none;"
                    data-is-new="{{ $isNewConfig ? '1' : '0' }}">
                    @includeIf('auto_posts.admin.social_media.configs.partials.edit_form_' . $platformKey, ['config' =>
                    $config, 'platforms' => $platforms, 'isNewConfig' => $isNewConfig])
                </div>
                @endforeach
            </div>
            <div class="modal-footer" id="APISettingsModalFooter" style="display: none;">
                <button type="button" class="primary-btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="primary-btn" id="APISettingsModalUpdateBtn">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
</div>





@endsection

@push('script')
<div id="page-config" data-platforms="{{ json_encode($platforms) }}" style="display:none;"></div>
<script src="{{ asset('admin/js/social-media-config.js') }}?v={{ time() }}"></script>
@endpush