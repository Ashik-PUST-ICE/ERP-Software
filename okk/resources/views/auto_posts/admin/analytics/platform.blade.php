@extends('auto_posts.admin.layouts.admin')
@push('title'){{ $pageTitle }}@endpush

@section('content')

<div class="page-content-wrapper">
    <div class="row ">
        <div class="col-12">
            
        </div>
    </div>
</div>

<div class="section-title mt-3">
    <h2 class="title">{{ $platformName }} {{ __('Analytics') }}</h2>
    <div class="board text-end">
                <a href="{{ route('admin.platform.index') }}" class="primary-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    {{ __('Back to Platforms') }}
                </a>
            </div>
</div>

{{-- Top 4 Stat Cards --}}
<div class="row gy-4">
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#02BCFF" />
                    <path
                        d="M19.6666 18.3332C19.6666 16.8604 18.4727 15.6665 16.9999 15.6665C15.5272 15.6665 14.3333 16.8604 14.3333 18.3332C14.3333 19.8059 15.5272 20.9998 16.9999 20.9998C18.4727 20.9998 19.6666 19.8059 19.6666 18.3332Z"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M18.359 16.0385C18.342 15.917 18.3333 15.7928 18.3333 15.6667C18.3333 14.1939 19.5272 13 20.9999 13C22.4727 13 23.6666 14.1939 23.6666 15.6667C23.6666 17.1394 22.4727 18.3333 20.9999 18.3333C20.5035 18.3333 20.0389 18.1977 19.6409 17.9615"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M21 25C21 22.7909 19.2091 21 17 21C14.7909 21 13 22.7909 13 25" stroke="white"
                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M25 22.3335C25 20.1244 23.2091 18.3335 21 18.3335" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($totalAccounts) }}</h2>
                <h3>{{ __('Total Account') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02" />
                    <path
                        d="M16.6666 21C16.6666 21 14.3333 22.7185 14.3333 23.3333C14.3332 23.9482 16.6666 25.6667 16.6666 25.6667"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M14.6667 23.3335H20.0001C22.1917 23.3335 23.2875 23.3335 24.0251 22.7282C24.1601 22.6174 24.2839 22.4936 24.3947 22.3586C25.0001 21.621 25.0001 20.5252 25.0001 18.3335"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M21.3333 17.0002C21.3333 17.0002 23.6666 15.2817 23.6666 14.6668C23.6666 14.0519 21.3333 12.3335 21.3333 12.3335"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M23.3333 14.6665H18C15.8083 14.6665 14.7125 14.6665 13.975 15.2718C13.8399 15.3826 13.7161 15.5064 13.6053 15.6415C13 16.379 13 17.4749 13 19.6665"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($totalPosts) }}</h2>
                <h3>{{ __('Total Post') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FFC402" />
                    <path d="M18.9999 23C17.2107 23 15.577 23.2517 14.3333 23.6667" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M22.9999 19.6667C24.4727 19.6667 25.6666 18.4727 25.6666 17C25.6666 15.5272 24.4727 14.3333 22.9999 14.3333C22.4511 14.3333 21.9409 14.4992 21.5169 14.7835C21.1524 13.7448 20.1631 13 18.9999 13C17.8367 13 16.8475 13.7448 16.483 14.7835C16.0589 14.4992 15.5488 14.3333 14.9999 14.3333C13.5272 14.3333 12.3333 15.5272 12.3333 17C12.3333 18.4727 13.5272 19.6667 14.9999 19.6667V23.3333"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M25 23.3332H21M25 23.3332C25 23.8 23.6705 24.6722 23.3333 24.9998M25 23.3332C25 22.8664 23.6705 21.9942 23.3333 21.6665"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($pendingPosts) }}</h2>
                <h3>{{ __('Pending Post') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#6B02FE" />
                    <path d="M21.4189 11.7456V14.5092M15.8916 11.7456V14.5092" stroke="white" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M19.3461 13.1274H17.9643C15.3587 13.1274 14.0559 13.1274 13.2465 13.9369C12.437 14.7463 12.437 16.0491 12.437 18.6547V20.0365C12.437 22.6421 12.437 23.9449 13.2465 24.7543C14.0559 25.5638 15.3587 25.5638 17.9643 25.5638H19.3461C21.9517 25.5638 23.2545 25.5638 24.0639 24.7543C24.8734 23.9449 24.8734 22.6421 24.8734 20.0365V18.6547C24.8734 16.0491 24.8734 14.7463 24.0639 13.9369C23.2545 13.1274 21.9517 13.1274 19.3461 13.1274Z"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12.437 17.2729H24.8734" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($scheduledPosts) }}</h2>
                <h3>{{ __('Schedule Post') }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row gy-4 mt-2">

    {{-- Left: Success/Failed + Social Post Area Chart --}}
    <div class="col-xl-6">
        <div class="row gy-4">
            <div class="col-6">
                <div class="card-box">
                    <span class="card-icon">
                        <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="19" cy="19" r="19" fill="#10A958" />
                            <path
                                d="M14.3333 20.6665C14.3333 20.6665 15.3333 20.6665 16.6666 22.9998C16.6666 22.9998 20.3725 16.8887 23.6666 15.6665"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div class="card-info">
                        <h2>{{ number_format($successPosts) }}</h2>
                        <h3>{{ __('Success Post') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card-box">
                    <span class="card-icon">
                        <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="19" cy="19" r="19" fill="#0D0D0D" />
                            <path
                                d="M23.3344 23.7479L25 25.4145M24.3333 21.0812C24.3333 19.0562 22.6917 17.4146 20.6667 17.4146C18.6416 17.4146 17 19.0562 17 21.0812C17 23.1063 18.6416 24.7479 20.6667 24.7479C22.6917 24.7479 24.3333 23.1063 24.3333 21.0812Z"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20.6666 19.7476V22.4142M21.9999 21.0809H19.3333" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M17.6667 13.4146H20.3333M13 18.0812V20.7479M15.3333 25.4145C14.0447 25.4145 13 24.3699 13 23.0812M22.6667 13.4146C23.9553 13.4146 25 14.4592 25 15.7479M13 15.7479C13 14.4592 14.0447 13.4146 15.3333 13.4146"
                                stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <div class="card-info">
                        <h2>{{ number_format($failedPosts) }}</h2>
                        <h3>{{ __('Failed Post') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-chart mt-20">
            <div class="chart-header">
                <div class="section-small-title mb-0">
                    <h3 class="title">{{ $platformName }} {{ __('Post') }}</h3>
                </div>
                <div class="stat-wrap">
                    <div class="stat">{{ __('Total Post') }} <b>{{ number_format($totalPosts) }}</b></div>
                    <div class="stat">{{ __('Pending') }} <b>{{ number_format($pendingPosts) }}</b></div>
                    <div class="stat">{{ __('Success') }} <b>{{ number_format($successPosts) }}</b></div>
                    <div class="stat">{{ __('Scheduled') }} <b>{{ number_format($scheduledPosts) }}</b></div>
                    <div class="stat">{{ __('Failed') }} <b>{{ number_format($failedPosts) }}</b></div>
                </div>
            </div>
            <div class="chart-wrapper">
                <div id="SocialPostChart"></div>
            </div>
        </div>
    </div>

    {{-- Right: Latest Post (same card as dashboard) --}}
    <div class="col-xl-6">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title">{{ __('Latest Post') }}</h3>
            </div>
            <table id="latestPostsTable" data-url="{{ route('admin.dashboard.latest-posts') }}" data-platform="{{ $platform ?? 'all' }}"
                class="display primary-table w-100">
                <thead>
                    <tr>
                        <th class="keep-show">#SL</th>
                        <th>{{ __('Platform Name') }}</th>
                        <th>{{ __('Account Name') }}</th>
                        <th>{{ __('Schedule Time') }}</th>
                        <th>{{ __('Post Type') }}</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('script')
<script src="{{ asset('common/js/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/js/admin-analytics.js') }}?ver={{ env('VERSION', 0) }}"></script>
<script>
(function() {
    var monthLabels = @json($monthLabels);
    var totalByMonth = @json($totalByMonth);
    var pendingByMonth = @json($pendingByMonth);
    var successByMonth = @json($successByMonth);
    var scheduledByMonth = @json($scheduledByMonth);
    var failedByMonth = @json($failedByMonth);
    initSocialPostChart(monthLabels, totalByMonth, pendingByMonth, successByMonth, scheduledByMonth, failedByMonth);
    if (typeof initAnalyticsLatestPostsTable === 'function') initAnalyticsLatestPostsTable('{{ $platform ?? 'all' }}');
}());
</script>
@endpush