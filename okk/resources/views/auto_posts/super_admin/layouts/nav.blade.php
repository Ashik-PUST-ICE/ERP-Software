<!-- @php
$adminNotifications = \App\Models\Notification::where(function ($query) {
$query->whereNull('user_id')->orWhere('user_id', auth()->id());
})->latest()->take(10)->get();
$unreadNotificationsCount = $adminNotifications->where('view_status', 0)->count();
@endphp -->


<header class="header-area">
    <div class="header-left">
        <button class="mobileMenu"><i class="fa-solid fa-bars"></i></button>
        <button class="search-btn">
            <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path
                    d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                    stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                <path
                    d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                    stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <div class="search-area">
            <div class="search-area-wrap">
                <label class="icon" for="searchDataHeader">
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
                <input type="text" class="search-input" id="searchDataHeader"
                    placeholder="{{ __('Search here...') }}" />
                <!-- <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search here...') }}" /> -->
            </div>
        </div>
    </div>
    <div class="header-right">

        {{-- Language Switcher --}}
        @php $appLangs = appLanguages(); @endphp
        @if(!empty(getOption('show_language_switcher')) && getOption('show_language_switcher') == STATUS_ACTIVE &&
        $appLangs->count() > 0)
        <div class="dropdown language-dropdown ">
            <button class="language-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="language-flag-text">
                    @if(selectedLanguage()?->flag)
                    <img class="flag" src="{{ asset(selectedLanguage()->flag) }}"
                        alt="{{ selectedLanguage()?->language }}">
                    @endif
                    <span class="selected-text">{{ selectedLanguage()?->language ?? __('Language') }}</span>
                </div>
                <i class="fa-solid fa-angle-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                @foreach($appLangs as $app_lang)
                <li>
                    <a class="dropdown-item {{ selectedLanguage()?->iso_code === $app_lang->iso_code ? 'active' : '' }}"
                        href="{{ url('/local/' . $app_lang->iso_code) }}">
                        @if($app_lang->flag)
                        <img src="{{ asset($app_lang->flag) }}" alt="{{ $app_lang->language }}">
                        @endif
                        {{ $app_lang->language }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif



        <!-- <div class="dropdown notifications-dropdown">
            <button class="notifications-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.5 18C15.5 19.933 13.933 21.5 12 21.5C10.067 21.5 8.5 19.933 8.5 18" stroke="#141B34"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M19.2311 18H4.76887C3.79195 18 3 17.208 3 16.2311C3 15.762 3.18636 15.3121 3.51809 14.9803L4.12132 14.3771C4.68393 13.8145 5 13.0514 5 12.2558V9.5C5 5.63401 8.13401 2.5 12 2.5C15.866 2.5 19 5.634 19 9.5V12.2558C19 13.0514 19.3161 13.8145 19.8787 14.3771L20.4819 14.9803C20.8136 15.3121 21 15.762 21 16.2311C21 17.208 20.208 18 19.2311 18Z"
                        stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                @if($unreadNotificationsCount > 0)
                <span class="notifications-badge">{{ $unreadNotificationsCount }}</span>
        @endif
        </button>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
            <ul class="notifications-list">
                @forelse($adminNotifications as $notification)
                <li>
                    <div class="notifications-text">
                        <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0.325" y="0.325" width="25.35" height="25.35" rx="12.675" fill="#FAF8F7" />
                            <rect x="0.325" y="0.325" width="25.35" height="25.35" rx="12.675" stroke="#F5EDEC"
                                stroke-width="0.65" />
                            <path
                                d="M15.2751 16.8999C15.2751 18.1564 14.2565 19.1749 13.0001 19.1749C11.7436 19.1749 10.7251 18.1564 10.7251 16.8999"
                                stroke="#141B34" stroke-width="0.975" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M17.7001 16.9002H8.29967C7.66467 16.9002 7.1499 16.3854 7.1499 15.7504C7.1499 15.4455 7.27104 15.1531 7.48666 14.9374L7.87876 14.5453C8.24446 14.1796 8.4499 13.6836 8.4499 13.1665V11.3752C8.4499 8.8623 10.487 6.8252 12.9999 6.8252C15.5128 6.8252 17.5499 8.8623 17.5499 11.3752V13.1665C17.5499 13.6836 17.7554 14.1796 18.1211 14.5453L18.5131 14.9374C18.7287 15.1531 18.8499 15.4455 18.8499 15.7504C18.8499 16.3854 18.3351 16.9002 17.7001 16.9002Z"
                                stroke="#141B34" stroke-width="0.975" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <p>
                            {{ $notification->title }}
                            @if($notification->link)
                            <a href="{{ $notification->link }}" class="see-more-btn">{{ __('See More') }}</a>
                            @endif
                        </p>
                    </div>
                    <span class="notifications-time">{{ $notification->created_at->diffForHumans() }}</span>
                </li>
                @empty
                <li class="text-center py-3">
                    <p class="text-muted mb-0">{{ __('No notifications') }}</p>
                </li>
                @endforelse
            </ul>
            <a href="#" class="primary-btn">{{ __('All Notifications') }}</a>
        </div>
    </div> -->


        {{-- Profile Dropdown --}}
        <div class="dropdown profile-dropdown">
            <button class="profile-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="profile-avatar">
                    <img src="{{ getFileUrl(auth()->user()->image) }}" alt="{{ auth()->user()->name }}"
                        onerror="this.src='{{ asset('assets/images/avatar-image.png') }}'">
                </div>
                <div class="profile-info">
                    <h4>{{ __('Welcome') }}</h4>
                    <h3>{{ auth()->user()->name }} <i class="fa-solid fa-angle-down"></i></h3>
                </div>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item"
                        href="{{ auth()->user()->role == USER_ROLE_SUPER_ADMIN ? route('super_admin.profile.index') : route('admin.profile.index') }}">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M14.1666 7.08341C14.1666 4.78223 12.3011 2.91675 9.99992 2.91675C7.69874 2.91675 5.83325 4.78223 5.83325 7.08341C5.83325 9.38458 7.69874 11.2501 9.99992 11.2501C12.3011 11.2501 14.1666 9.38458 14.1666 7.08341Z"
                                stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M15.8334 17.0833C15.8334 13.8617 13.2217 11.25 10.0001 11.25C6.77842 11.25 4.16675 13.8617 4.16675 17.0833"
                                stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        {{ __('Profile') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.66048 3.33414C3.33325 3.84672 3.33325 4.5098 3.33325 5.83595V14.1642C3.33325 15.4903 3.33325 16.1534 3.66048 16.666C3.71891 16.7576 3.78442 16.8444 3.85639 16.9257C4.25945 17.3811 4.89714 17.5632 6.1725 17.9276C7.45109 18.2928 8.09039 18.4754 8.55325 18.2015C8.63359 18.154 8.70825 18.0977 8.776 18.0336C9.16659 17.6638 9.16659 16.9991 9.16659 15.6696V4.3306C9.16659 3.0011 9.16659 2.33634 8.776 1.9666C8.70825 1.90248 8.63359 1.84614 8.55325 1.79864C8.09039 1.52471 7.45109 1.70733 6.1725 2.07257C4.89714 2.4369 4.25945 2.61906 3.85639 3.07445C3.78442 3.15578 3.71891 3.2426 3.66048 3.33414Z"
                                stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M9.16675 3.33325H10.8477C12.4324 3.33325 13.2247 3.33325 13.7171 3.82141C13.9916 4.09357 14.113 4.4582 14.1667 4.99992M9.16675 16.6666H10.8477C12.4324 16.6666 13.2247 16.6666 13.7171 16.1784C13.9916 15.9063 14.113 15.5417 14.1667 14.9999"
                                stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M17.5001 10.0001H11.6667M16.2501 7.91675C16.2501 7.91675 18.3334 9.45113 18.3334 10.0001C18.3334 10.5491 16.2501 12.0834 16.2501 12.0834"
                                stroke="#0D0D0D" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>

    </div>
</header>
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>