@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('script')
<script src="{{ asset('admin/js/garment-analytics.js') }}?ver={{ config('app.version', 0) }}"></script>
@endpush

@section('content')
<div class="garment-page" data-card-data-url="{{ route('admin.garments.analytics.api.cards') }}">
    <input type="hidden" id="garment-analytics-data-url" value="{{ route('admin.garments.analytics.api.cards') }}">

    <div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h2 class="title">{{ __($title) }}</h2>
            <span class="text-muted" style="font-size:1.3rem;">{{ now()->format('l, d F Y') }}</span>
        </div>
        <a class="primary-btn" href="{{ route('admin.garments.analytics.data') }}" target="_blank">
            <i class="fa-solid fa-chart-column me-2"></i>{{ __('View Report Data') }}
        </a>
    </div>

    <div class="row gy-4 mb-20 garment-dashboard-kpis garment-analytics-kpis">
        @foreach([
            ['orders', 'kpiAnalyticsOrders', 'Total Orders', 'fa-clipboard-list'],
            ['planned_quantity', 'kpiAnalyticsPlanned', 'Planned Quantity', 'fa-bullseye'],
            ['produced_quantity', 'kpiAnalyticsProduced', 'Produced Quantity', 'fa-industry'],
            ['achievement', 'kpiAnalyticsAchievement', 'Plan Achievement', 'fa-chart-line'],
        ] as $card)
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid {{ $card[3] }}"></i></span>
                    <div class="card-info">
                        <h2 id="{{ $card[1] }}">{{ number_format($summary['kpis'][$card[0]], $card[0] === 'achievement' ? 1 : 0) }}{{ $card[0] === 'achievement' ? '%' : '' }}</h2>
                        <h3>{{ __($card[2]) }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row gy-4">
        <div class="col-xl-8">
            <div class="section-wrap p-4 h-100 garment-analytics-panel">
                <div class="section-small-title"><h3 class="title">{{ __('Last 14 Days: Target vs Output') }}</h3><i class="fa-solid fa-chart-line text-primary"></i></div>
                <div id="garmentAnalyticsDailyOutput">
                    @forelse($summary['daily_output'] as $day)
                        <div class="analytics-row"><span>{{ $day['date'] }}</span><div class="analytics-track"><div class="analytics-target" style="width: {{ $day['target_width'] }}%"></div><div class="analytics-output" style="width: {{ $day['output_width'] }}%"></div></div><strong>{{ number_format($day['output']) }}</strong></div>
                    @empty
                        <div class="analytics-empty"><i class="fa-solid fa-chart-line"></i><span>{{ __('No production data available') }}</span></div>
                    @endforelse
                </div>
                <div class="small mt-3"><span class="legend target"></span>{{ __('Target') }} <span class="legend output ms-3"></span>{{ __('Output') }}</div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="section-wrap p-4 h-100 garment-analytics-panel">
                <div class="section-small-title"><h3 class="title">{{ __('Order Status') }}</h3><i class="fa-solid fa-list-check text-primary"></i></div>
                <div id="garmentAnalyticsOrderStatus">
                    @forelse($summary['order_status'] as $item)
                        <div class="analytics-status-row"><span>{{ __('Status') }} {{ $item['status'] }}</span><strong>{{ $item['total'] }}</strong></div>
                    @empty
                        <div class="analytics-empty"><i class="fa-solid fa-box-open"></i><span>{{ __('No order data available') }}</span></div>
                    @endforelse
                </div>
                <div class="analytics-rejection mt-4"><p class="mb-1 text-muted">{{ __('Quality Rejection Rate') }}</p><h3 id="analyticsKpiRejectionRate">{{ $summary['kpis']['rejection_rate'] }}%</h3></div>
            </div>
        </div>
    </div>
    <button type="button" class="mt-2" data-card-retry hidden>{{ __('Retry loading metrics') }}</button>
</div>
@endsection
