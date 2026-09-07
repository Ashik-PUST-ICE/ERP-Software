@extends('auto_posts.admin.layouts.admin')
@push('title')
{{ __('Calendar') }}
@endpush
@section('content')

<div class="section-title d-flex flex-wrap align-items-center justify-content-between gap-2">
    <h2 class="title mb-0">{{ __('Calendar') }}</h2>
    <span class="badge bg-light text-dark border px-3 py-2" title="{{ __('All times are displayed in this timezone') }}">
        <i class="fa-solid fa-clock me-1"></i>{{ __('Times in :timezone', ['timezone' => $calendarConfig['appTimeZone'] ?? 'UTC']) }}
    </span>
</div>
<div class="create-post-wrap calendar-area">
    <div class="create-post-left">
        <div class="post-left-top">
            <ul class="post-latform-lsit">
                @foreach(SOCIAL_MEDIA_PLATFORMS as $platform)
                @php $platformAllowed = isset($allowedProviders) ? in_array(strtolower($platform), $allowedProviders) :
                true; @endphp
                <li>
                    <label class="platform-name{{ !$platformAllowed ? ' disabled' : '' }}">
                        <input type="checkbox" class="platform-filter" value="{{ strtolower($platform) }}"
                            data-platform="{{ strtolower($platform) }}"
                            data-provider-allowed="{{ $platformAllowed ? '1' : '0' }}"
                            {{ !$platformAllowed ? ' disabled' : '' }}>
                        <span class="post-paltform"><span class="icon">
                                @switch($platform)
                                @case('Facebook')
                                <i class="fa-brands fa-facebook-f"></i>
                                @break
                                @case('LinkedIn')
                                <i class="fa-brands fa-linkedin-in"></i>
                                @break
                                @case('YouTube')
                                <i class="fa-brands fa-youtube"></i>
                                @break
                                @case('TikTok')
                                <i class="fa-brands fa-tiktok"></i>
                                @break
                                @case('Twitter')
                                <i class="fa-brands fa-x-twitter"></i>
                                @break
                                @case('Instagram')
                                <i class="fa-brands fa-instagram"></i>
                                @break
                                @case('Threads')
                                <i class="fa-brands fa-threads"></i>
                                @break
                                @default
                                <i class="fa-solid fa-globe"></i>
                                @endswitch
                            </span>
                            {{ $platform }}</span>
                    </label>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="post-left-bottom">
            <div class="search-input-wrap">
                <label class="icon" for="search">
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                            stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                            stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </label>
                <input type="text" class="search-input" id="search" placeholder="Search Accounts..." />
            </div>
            <ul class="accounts-list">
                @foreach($accounts as $account)
                @php $accountPlatformAllowed = isset($allowedProviders) ? in_array(strtolower($account->platform),
                $allowedProviders) : true; @endphp
                <li>
                    <label class="accounts-info{{ !$accountPlatformAllowed ? ' disabled' : '' }}">
                        <input class="form-check-input account-filter" type="checkbox" value="{{ $account->id }}"
                            data-platform="{{ strtolower($account->platform) }}"
                            data-provider-allowed="{{ $accountPlatformAllowed ? '1' : '0' }}"
                            {{ !$accountPlatformAllowed ? ' disabled' : '' }}>
                        {{ $account->username ?: $account->account_id }}
                    </label>
                </li>
                @endforeach
            </ul>

        </div>
    </div>
    <div class="create-post-center">
        <div id="calendar-config" data-events-url="{{ $calendarConfig['eventsUrl'] }}"
            data-update-date-url="{{ $calendarConfig['updateDateUrl'] }}"
            data-publish-now-url="{{ $calendarConfig['publishNowUrl'] }}"
            data-publish-campaign-url="{{ $calendarConfig['publishCampaignUrl'] }}"
            data-edit-post-url="{{ $calendarConfig['editPostUrl'] }}"
            data-edit-campaign-url="{{ $calendarConfig['editCampaignUrl'] }}"
            data-delete-post-url="{{ $calendarConfig['deletePostUrl'] }}"
            data-delete-campaign-url="{{ $calendarConfig['deleteCampaignUrl'] }}"
            data-csrf-token="{{ $calendarConfig['csrfToken'] }}"
            data-default-avatar="{{ $calendarConfig['defaultAvatar'] }}"
            data-base-url="{{ $calendarConfig['baseUrl'] }}"
            data-app-timezone="{{ $calendarConfig['appTimeZone'] ?? 'UTC' }}" style="display:none;"></div>
        <div class="section-wrap">
            <div class="calendar-top">
                <div class="left-range">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.6663 1.3335V4.00016M5.33301 1.3335V4.00016" stroke="#0D0D0D" stroke-width="1.4"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M8.66667 2.6665H7.33333C4.81917 2.6665 3.5621 2.6665 2.78105 3.44755C2 4.2286 2 5.48568 2 7.99984V9.33317C2 11.8473 2 13.1044 2.78105 13.8854C3.5621 14.6665 4.81917 14.6665 7.33333 14.6665H8.66667C11.1808 14.6665 12.4379 14.6665 13.2189 13.8854C14 13.1044 14 11.8473 14 9.33317V7.99984C14 5.48568 14 4.2286 13.2189 3.44755C12.4379 2.6665 11.1808 2.6665 8.66667 2.6665Z"
                            stroke="#0D0D0D" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M2 6.6665H14" stroke="#0D0D0D" stroke-width="1.4" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span id="rangeText">Jan 1 - Jan 31</span>
                </div>
                <div class="nav-btns">
                    <span id="prevBtn" class="calendar-btn">
                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.24779 6.50275L10.1111 1.63927C10.245 1.50567 10.3186 1.32706 10.3186 1.1366C10.3186 0.946041 10.245 0.767528 10.1111 0.633724L9.68491 0.207789C9.55121 0.0737724 9.37249 0 9.18203 0C8.99158 0 8.81307 0.0737724 8.67926 0.207789L2.88877 5.99818C2.75443 6.13241 2.68087 6.31176 2.6814 6.50243C2.68087 6.69394 2.75433 6.87309 2.88877 7.00742L8.67387 12.7922C8.80768 12.9262 8.98619 13 9.17675 13C9.3672 13 9.54572 12.9262 9.67963 12.7922L10.1057 12.3663C10.3829 12.089 10.3829 11.6377 10.1057 11.3606L5.24779 6.50275Z"
                                fill="black" />
                        </svg>
                    </span>
                    <div class="month-title" id="monthTitle">January 2026</div>
                    <span id="nextBtn" class="calendar-btn">
                        <svg width="13" height="13" viewBox="0 0 13 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_166_2003)">
                                <path
                                    d="M7.75221 6.49725L2.88894 11.3607C2.75503 11.4943 2.68137 11.6729 2.68137 11.8634C2.68137 12.054 2.75503 12.2325 2.88894 12.3663L3.31509 12.7922C3.44879 12.9262 3.62751 13 3.81797 13C4.00842 13 4.18693 12.9262 4.32074 12.7922L10.1112 7.00182C10.2456 6.86759 10.3191 6.68824 10.3186 6.49757C10.3191 6.30606 10.2457 6.12691 10.1112 5.99258L4.32613 0.207789C4.19233 0.073773 4.01381 5.85314e-07 3.82325 5.68655e-07C3.6328 5.52005e-07 3.45428 0.073773 3.32037 0.207789L2.89433 0.633724C2.61711 0.910951 2.61711 1.36225 2.89433 1.63937L7.75221 6.49725Z"
                                    fill="black" />
                            </g>
                            <defs>
                                <clipPath id="clip0_166_2003">
                                    <rect width="13" height="13" fill="white"
                                        transform="translate(13 13) rotate(-180)" />
                                </clipPath>
                            </defs>
                        </svg>
                    </span>
                </div>
            </div>
            <div id="calendar"></div>
        </div>
    </div>
</div>


@endsection

@push('script')
<script src="{{ asset('admin/js/calendar.js') }}"></script>
@endpush