@extends('auto_posts.admin.layouts.admin')
@push('title')
{{ $pageTitle }}
@endpush



@section('content')
{{-- NOTE: admin.blade.php already provides <div class="content-wrapper min-height-fix"> --}}
@php
$platformIcons = [
'facebook' => '<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" />
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" stroke="#ECE3E1" />
    <path fill-rule="evenodd" clip-rule="evenodd"
        d="M12.1518 15.6112C11.337 15.6112 11.167 15.7711 11.167 16.5371V17.926C11.167 18.6921 11.337 18.8519 12.1518 18.8519H14.1215V24.4075C14.1215 25.1735 14.2916 25.3334 15.1064 25.3334H17.0761C17.8909 25.3334 18.0609 25.1735 18.0609 24.4075V18.8519H20.2726C20.8906 18.8519 21.0498 18.739 21.2196 18.1804L21.6417 16.7915C21.9324 15.8346 21.7532 15.6112 20.6947 15.6112H18.0609V13.2964C18.0609 12.785 18.5018 12.3704 19.0457 12.3704H21.8488C22.6636 12.3704 22.8337 12.2106 22.8337 11.4445V9.59267C22.8337 8.82662 22.6636 8.66675 21.8488 8.66675H19.0457C16.3262 8.66675 14.1215 10.7395 14.1215 13.2964V15.6112H12.1518Z"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linejoin="round" />
</svg>',
'twitter' => '<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" />
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" stroke="#ECE3E1" />
    <path
        d="M9.5 24.5L15.7903 18.2097M15.7903 18.2097L9.5 9.5H13.6667L18.2097 15.7903M15.7903 18.2097L20.3333 24.5H24.5L18.2097 15.7903M24.5 9.5L18.2097 15.7903"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
</svg>',
'instagram' => '<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" />
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" stroke="#ECE3E1" />
    <path
        d="M9.08301 16.9999C9.08301 13.268 9.08301 11.402 10.2424 10.2426C11.4017 9.08325 13.2677 9.08325 16.9997 9.08325C20.7316 9.08325 22.5976 9.08325 23.757 10.2426C24.9163 11.402 24.9163 13.268 24.9163 16.9999C24.9163 20.7318 24.9163 22.5978 23.757 23.7573C22.5976 24.9166 20.7316 24.9166 16.9997 24.9166C13.2677 24.9166 11.4017 24.9166 10.2424 23.7573C9.08301 22.5978 9.08301 20.7318 9.08301 16.9999Z"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linejoin="round" />
    <path
        d="M20.75 17C20.75 19.0711 19.0711 20.75 17 20.75C14.9289 20.75 13.25 19.0711 13.25 17C13.25 14.9289 14.9289 13.25 17 13.25C19.0711 13.25 20.75 14.9289 20.75 17Z"
        stroke="#0D0D0D" stroke-width="1.5" />
    <path d="M21.5895 12.4167H21.582" stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round"
        stroke-linejoin="round" />
</svg>',
'linkedin' => '<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" />
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" stroke="#ECE3E1" />
    <path d="M12.833 15.3333V21.1666" stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round"
        stroke-linejoin="round" />
    <path
        d="M16.167 17.8333V21.1666M16.167 17.8333C16.167 16.4525 17.2862 15.3333 18.667 15.3333C20.0477 15.3333 21.167 16.4525 21.167 17.8333V21.1666M16.167 17.8333V15.3333"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    <path d="M12.841 12.8333H12.832" stroke="#0D0D0D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
    <path
        d="M9.08301 16.9999C9.08301 13.268 9.08301 11.402 10.2424 10.2426C11.4017 9.08325 13.2677 9.08325 16.9997 9.08325C20.7316 9.08325 22.5976 9.08325 23.757 10.2426C24.9163 11.402 24.9163 13.268 24.9163 16.9999C24.9163 20.7318 24.9163 22.5978 23.757 23.7573C22.5976 24.9166 20.7316 24.9166 16.9997 24.9166C13.2677 24.9166 11.4017 24.9166 10.2424 23.7573C9.08301 22.5978 9.08301 20.7318 9.08301 16.9999Z"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linejoin="round" />
</svg>',
'youtube' => '<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" />
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" stroke="#ECE3E1" />
    <path
        d="M17.0003 24.0834C18.5084 24.0834 19.9546 23.9344 21.2948 23.6612C22.9689 23.32 23.806 23.1493 24.5698 22.1672C25.3337 21.1852 25.3337 20.0578 25.3337 17.803V16.1972C25.3337 13.9424 25.3337 12.815 24.5698 11.8329C23.806 10.8508 22.9689 10.6802 21.2948 10.3389C19.9546 10.0657 18.5084 9.91675 17.0003 9.91675C15.4922 9.91675 14.0461 10.0657 12.7058 10.3389C11.0317 10.6802 10.1947 10.8508 9.43083 11.8329C8.66699 12.815 8.66699 13.9424 8.66699 16.1972V17.803C8.66699 20.0578 8.66699 21.1852 9.43083 22.1672C10.1947 23.1493 11.0317 23.32 12.7058 23.6612C14.0461 23.9344 15.4922 24.0834 17.0003 24.0834Z"
        stroke="#0D0D0D" stroke-width="1.5" />
    <path
        d="M20.3018 17.2607C20.1781 17.7655 19.5201 18.1281 18.2041 18.8533C16.7727 19.642 16.057 20.0364 15.4773 19.8845C15.281 19.833 15.1002 19.7425 14.9483 19.6198C14.5 19.2573 14.5 18.5048 14.5 16.9999C14.5 15.495 14.5 14.7425 14.9483 14.3801C15.1002 14.2573 15.281 14.1668 15.4773 14.1154C16.057 13.9634 16.7727 14.3578 18.2041 15.1465C19.5201 15.8718 20.1781 16.2343 20.3018 16.7392C20.3438 16.911 20.3438 17.0888 20.3018 17.2607Z"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linejoin="round" />
</svg>',
'tiktok' => '<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" />
    <rect x="0.5" y="0.5" width="32" height="32" rx="9.5" stroke="#ECE3E1" />
    <path
        d="M9.08301 16.9999C9.08301 13.268 9.08301 11.402 10.2424 10.2426C11.4017 9.08325 13.2677 9.08325 16.9997 9.08325C20.7316 9.08325 22.5976 9.08325 23.757 10.2426C24.9163 11.402 24.9163 13.268 24.9163 16.9999C24.9163 20.7318 24.9163 22.5978 23.757 23.7573C22.5976 24.9166 20.7316 24.9166 16.9997 24.9166C13.2677 24.9166 11.4017 24.9166 10.2424 23.7573C9.08301 22.5978 9.08301 20.7318 9.08301 16.9999Z"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linejoin="round" />
    <path
        d="M15.7796 16.173C15.0962 16.0764 13.5386 16.2362 12.7748 17.6486C12.011 19.0608 12.7808 20.5307 13.2611 21.0892C13.7355 21.6116 15.2432 22.6009 16.8425 21.635C17.2389 21.3956 17.7328 21.217 18.293 19.3462L18.2278 11.9846C18.1198 12.7952 19.0152 14.6965 21.5647 14.9215"
        stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
</svg>',
'threads' => '<span class="platform-tab-icon platform-tab-icon-fa"><i class="fa-brands fa-threads"></i></span>',
];

$platformColors = [
'facebook' => '#FF4F02',
'twitter' => '#FFC402',
'instagram' => '#02BCFF',
'linkedin' => '#0FA958',
'youtube' => '#0D0D0D',
'tiktok' => '#6BD096',
'threads' => '#6B02FF',
];

$tabPlatforms = $platforms ?? ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube', 'tiktok', 'threads'];
@endphp

<div class="section-title">
    <h2 class="title">{{ __($pageTitle) }}</h2>
</div>

{{-- Platform Tabs --}}
<nav class="primry-tabs mb-20">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-AllPlatforms-tab" data-bs-toggle="tab"
            data-bs-target="#nav-AllPlatforms" type="button" role="tab" aria-controls="nav-AllPlatforms"
            aria-selected="true">{{ __('All Platforms') }}</button>

        @foreach($tabPlatforms as $pl)
        <button class="nav-link" id="nav-{{ ucfirst($pl) }}-tab" data-bs-toggle="tab"
            data-bs-target="#nav-{{ ucfirst($pl) }}" type="button" role="tab" aria-controls="nav-{{ ucfirst($pl) }}"
            aria-selected="false">
            {!! $platformIcons[$pl] ?? '' !!}
            {{ ucfirst($pl) }}
        </button>
        @endforeach
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    {{-- All Platforms Tab --}}
    <div class="tab-pane fade show active" id="nav-AllPlatforms" role="tabpanel" aria-labelledby="nav-AllPlatforms-tab"
        tabindex="0">
        @include('auto_posts.admin.partials.dashboard-stat-cards', [
        'postCount' => $totalPosts,
        'platform' => null,
        ])
    </div>

    {{-- Per-Platform Tabs --}}
    @foreach($tabPlatforms as $pl)
    <div class="tab-pane fade" id="nav-{{ ucfirst($pl) }}" role="tabpanel" aria-labelledby="nav-{{ ucfirst($pl) }}-tab"
        tabindex="0">
        @include('auto_posts.admin.partials.dashboard-stat-cards', [
        'postCount' => $postCounts[$pl] ?? 0,
        'platform' => $pl,
        ])
    </div>
    @endforeach
</div>

{{-- Charts & Latest Posts Row --}}
<div class="row gy-4 mt-2">
    <div class="col-xl-6 col-lg-6">
        <div class="section-wrap social-accounts-chart h-100">
            <div class="section-small-title mb-0">
                <h3 class="title">{{ __('Social Accounts') }}</h3>
            </div>
            <div class="socia-chart-wrap">
                <div id="dashboardSocialAccountsChart"></div>
            </div>
            <div class="stats">
                <div class="stat-box">
                    <strong>{{ $totalAccounts }}</strong>
                    {{ __('Total') }}
                </div>
                <div class="stat-box">
                    <strong>{{ $activeAccounts }}</strong>
                    {{ __('Active') }}
                </div>
                <div class="stat-box">
                    <strong>{{ $totalAccounts - $activeAccounts }}</strong>
                    {{ __('Inactive') }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-lg-6">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title">{{ __('Latest Post') }}</h3>
            </div>
            <table id="latestPostsTable" data-url="{{ route('admin.dashboard.latest-posts') }}"
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

<input type="hidden" id="transaction-route" value="{{ route('admin.dashboard') }}">
@endsection

@push('script')
<!-- <script src="{{ asset('common/js/apexcharts.min.js') }}"></script> -->
<script src="{{ asset('admin/js/admin-dashboard.js') }}?ver={{ env('VERSION', 0) }}"></script>
<script>
(function() {
    var labels = @json($chartLabels);
    var series = @json($chartSeries);
    var totalAccounts = {{ (int) $totalAccounts }};
    @php
    $orderedColors = [];
    foreach($chartLabels as $label) {
        $orderedColors[] = $chartColors[strtolower($label)] ?? '#999';
    }
    @endphp
    var colors = @json($orderedColors);

    if (!labels.length) {
        labels = ['No Data'];
        series = [1];
        colors = ['#e0e0e0'];
    }

    var options = {
        series: series,
        chart: {
            type: 'donut',
            width: '480',
            animations: {
                enabled: true
            },
            events: {
                dataPointSelection: function() {
                    return false;
                }
            }
        },
        labels: labels,
        colors: colors,
        stroke: {
            width: 10,
            colors: ['#ffffff'],
            lineCap: 'round'
        },
        states: {
            hover: {
                filter: {
                    type: 'none'
                }
            },
            active: {
                filter: {
                    type: 'none'
                }
            }
        },
        plotOptions: {
            pie: {
                expandOnClick: false,
                donut: {
                    size: '84%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            offsetY: -8,
                            color: '#FF4F02',
                            fontSize: '12px'
                        },
                        value: {
                            show: true,
                            fontSize: '33px',
                            fontWeight: 500,
                            color: '#FF4F02',
                            offsetY: 10,
                            formatter: function(val) {
                                return Math.round(val);
                            }
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 500,
                            color: '#FF4F02',
                            formatter: function() {
                                return totalAccounts;
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function(val) {
                return Math.round(val) + '%';
            },
            style: {
                fontSize: '12px',
                fontWeight: 500,
                colors: ['#fff']
            },
            background: {
                enabled: true,
                borderRadius: 50,
                foreColor: '#000',
                padding: 6,
                opacity: 1
            },
            dropShadow: {
                enabled: false
            }
        },
        legend: {
            show: true,
            position: 'left',
            fontSize: '12px',
            fontWeight: 500,
            markers: {
                width: 12,
                height: 12,
                radius: 50
            },
            onItemHover: {
                highlightDataSeries: false
            }
        },
        tooltip: {
            enabled: false
        },
        responsive: [{
                breakpoint: 1367,
                options: {
                    chart: {
                        width: '100%'
                    }
                }
            },
            {
                breakpoint: 1024,
                options: {
                    chart: {
                        width: '100%'
                    },
                    legend: {
                        position: 'top'
                    }
                }
            },
            {
                breakpoint: 768,
                options: {
                    chart: {
                        width: '100%'
                    },
                    legend: {
                        position: 'top'
                    }
                }
            },
            {
                breakpoint: 480,
                options: {
                    chart: {
                        width: '100%'
                    },
                    dataLabels: {
                        style: {
                            fontSize: '10px'
                        }
                    },
                    plotOptions: {
                        pie: {
                            expandOnClick: false,
                            donut: {
                                size: '65%'
                            }
                        }
                    },
                    legend: {
                        position: 'top',
                        fontSize: '9px'
                    }
                }
            }
        ]
    };

    new ApexCharts(document.querySelector('#dashboardSocialAccountsChart'), options).render();
}());
</script>
@endpush