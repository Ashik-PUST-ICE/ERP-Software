@extends('auto_posts.super_admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    <div class="section-title">
        <h2 class="title">{{ __($pageTitle) }}</h2>
    </div>

    {{-- Top SaaS Metric Cards (admin-style) --}}
    <div class="row gy-4 mb-4">
        {{-- Total Customer --}}
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="19" cy="19" r="19" fill="#02BCFF"/>
                        <path d="M14.5 17.25C15.7426 17.25 16.75 16.2426 16.75 15C16.75 13.7574 15.7426 12.75 14.5 12.75C13.2574 12.75 12.25 13.7574 12.25 15C12.25 16.2426 13.2574 17.25 14.5 17.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M11 22.75V22.25C11 20.8693 12.1193 19.75 13.5 19.75H15.5C16.8807 19.75 18 20.8693 18 22.25V22.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M23.5 17.25C24.7426 17.25 25.75 16.2426 25.75 15C25.75 13.7574 24.7426 12.75 23.5 12.75C22.2574 12.75 21.25 13.7574 21.25 15C21.25 16.2426 22.2574 17.25 23.5 17.25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 22.75V22.25C20 20.8693 21.1193 19.75 22.5 19.75H24.5C25.8807 19.75 27 20.8693 27 22.25V22.75" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="card-info">
                    <h2>{{ number_format($totalCustomers) }}</h2>
                    <h3>{{ __('Total Customer') }}</h3>
                </div>
                <span class="card-status up">
                    {{ number_format($totalCustomers) }}
                    <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
                </span>
            </div>
        </div>

        {{-- Active Package --}}
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="19" cy="19" r="19" fill="#0D0D0D"/>
                        <path d="M13 15.5H25L23.5 23H14.5L13 15.5Z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
                        <path d="M15 15.5L16.5 12.5H21.5L23 15.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="card-info">
                    <h2>{{ number_format($activePackages) }}</h2>
                    <h3>{{ __('Active Package') }}</h3>
                </div>
                <span class="card-status up">
                    {{ number_format($activePackages) }}
                    <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
                </span>
            </div>
        </div>

        {{-- Current Subscriptions --}}
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                        <path d="M15 14L16.5 12.5L19 15L21.5 12.5L23 14L20.5 16.5L23 19L21.5 20.5L19 18L16.5 20.5L15 19L17.5 16.5L15 14Z" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="card-info">
                    <h2>{{ number_format($currentSubscriptions) }}</h2>
                    <h3>{{ __('Current Subscriptions') }}</h3>
                </div>
                <span class="card-status {{ $currentSubscriptions > 0 ? 'up' : 'down' }}">
                    {{ number_format($currentSubscriptions) }}
                    <span class="arrow"><i class="fa-solid fa-arrow-{{ $currentSubscriptions > 0 ? 'up' : 'down' }}"></i></span>
                </span>
            </div>
        </div>

        {{-- Total Earn --}}
        <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon">
                    <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                        <path d="M19 12V26" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M15 15.5C15.7917 14.7083 17.1667 14 19 14C20.8333 14 22.2083 14.7083 23 15.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                        <path d="M15 22.5C15.7917 23.2917 17.1667 24 19 24C20.8333 24 22.2083 23.2917 23 22.5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </span>
                <div class="card-info">
                    <h2>{{ showPrice($totalEarn) }}</h2>
                    <h3>{{ __('Total Earn') }}</h3>
                </div>
                <span class="card-status up">
                    {{ showPrice($totalEarn) }}
                    <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
                </span>
            </div>
        </div>
    </div>

    {{-- Monthly Charts --}}
    <div class="row gy-4">
        <div class="col-xl-6">
            <div class="section-wrap h-100">
                <div class="section-small-title mb-0">
                    <h3 class="title">{{ __('Monthly Subscription Summary') }}</h3>
                </div>
                <div id="subscriptionSummaryChart"></div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="section-wrap h-100">
                <div class="section-small-title mb-0">
                    <h3 class="title">{{ __('Monthly Sell Summary') }}</h3>
                </div>
                <div id="salesSummaryChart"></div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('common/js/apexcharts.min.js') }}"></script>
    <script>
        (function () {
            const subscriptionData = @json($subscriptionChart);
            const salesData = @json($salesChart);

            function renderLineChart(elId, labels, data, color, valuePrefix = '') {
                const el = document.getElementById(elId);
                if (!el) return;

                const options = {
                    chart: {
                        type: 'area',
                        height: 320,
                        toolbar: { show: false },
                        zoom: { enabled: false }
                    },
                    colors: [color],
                    dataLabels: { enabled: false },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    series: [{
                        name: '',
                        data: data
                    }],
                    xaxis: {
                        categories: labels,
                        labels: {
                            style: { colors: '#6c757d' }
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (val) {
                                if (valuePrefix) {
                                    return valuePrefix + ' ' + Math.round(val);
                                }
                                return Math.round(val);
                            },
                            style: { colors: '#6c757d' }
                        }
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0,
                            stops: [0, 90, 100]
                        }
                    },
                    grid: {
                        borderColor: '#f1f3f5'
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                if (valuePrefix) {
                                    return valuePrefix + ' ' + val.toFixed(2);
                                }
                                return val.toFixed(0);
                            }
                        }
                    }
                };

                const chart = new ApexCharts(el, options);
                chart.render();
            }

            renderLineChart(
                'subscriptionSummaryChart',
                subscriptionData.labels || [],
                subscriptionData.data || [],
                '#FF4F02' // same primary orange as admin
            );

            renderLineChart(
                'salesSummaryChart',
                salesData.labels || [],
                salesData.data || [],
                '#0D0D0D', // same dark tone as admin's second card
                '{{ getCurrencySymbol() }}'
            );
        })();
    </script>
@endpush