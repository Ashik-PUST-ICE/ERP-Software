@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@section('content')
<div class="garment-page" data-card-data-url="{{ route('admin.garments.analytics.api.cards') }}">
    <div class="section-title d-flex justify-content-between align-items-center">
        <h2 class="title">{{ __($title) }}</h2>
        <a class="primary-btn" href="{{ route('admin.garments.analytics.index') }}">
            <i class="fa-solid fa-arrow-left me-2"></i>{{ __('Back to Analytics') }}
        </a>
    </div>

    <div class="row gy-4 mb-20 garment-dashboard-kpis">
    @foreach([
        ['kpiAnalyticsOrders', 'Total Orders', 'fa-clipboard-list', '#4778c7', 'orders'],
        ['kpiAnalyticsPlanned', 'Planned Quantity', 'fa-bullseye', '#02BCFF', 'planned_quantity'],
        ['kpiAnalyticsProduced', 'Produced Quantity', 'fa-industry', '#0FA958', 'produced_quantity'],
        ['kpiAnalyticsAchievement', 'Plan Achievement', 'fa-chart-line', '#FFC402', 'achievement'],
    ] as $card)
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon"><i class="fa-solid {{ $card[2] }}" style="color:white;background:{{ $card[3] }};border-radius:50%;padding:11px;"></i></span>
                <div class="card-info">
                    <h2 id="{{ $card[0] }}">{{ number_format($summary['kpis'][$card[4]], 1) }}{{ $card[4] === 'achievement' ? '%' : '' }}</h2>
                    <h3>{{ __($card[1]) }}</h3>
                </div>
            </div>
        </div>
    @endforeach
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="section-wrap h-100 garment-analytics-panel">
                <h4 class="mb-4">{{ __('Last 14 Days: Target vs Output') }}</h4>
                @forelse($summary['daily_output'] as $day)
                    <div class="analytics-row">
                        <span>{{ $day['date'] }}</span>
                        <div class="analytics-track">
                            <div class="analytics-target" style="width: {{ $day['target_width'] }}%"></div>
                            <div class="analytics-output" style="width: {{ $day['output_width'] }}%"></div>
                        </div>
                        <strong>{{ number_format($day['output']) }}</strong>
                    </div>
                @empty
                    <p class="text-muted">{{ __('No production data available') }}</p>
                @endforelse
                <div class="small mt-3"><span class="legend target"></span>{{ __('Target') }} <span class="legend output ms-3"></span>{{ __('Output') }}</div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="section-wrap h-100 garment-analytics-panel">
                <h4 class="mb-4">{{ __('Order Status') }}</h4>
                @forelse($summary['order_status'] as $item)
                    <div class="d-flex justify-content-between border-bottom py-2"><span>{{ __('Status') }} {{ $item['status'] }}</span><strong>{{ $item['total'] }}</strong></div>
                @empty
                    <p class="text-muted">{{ __('No order data available') }}</p>
                @endforelse
                <div class="mt-4 analytics-rejection"><p class="mb-1 text-muted">{{ __('Quality Rejection Rate') }}</p><h3>{{ $summary['kpis']['rejection_rate'] }}%</h3></div>
            </div>
        </div>
    </div>
    <button type="button" class="mt-2" data-card-retry hidden>{{ __('Retry loading metrics') }}</button>
@endsection

@push('style')
<style>
.analytics-row{display:flex;align-items:center;gap:12px;margin-bottom:12px}.analytics-row>span{width:58px;font-size:12px}.analytics-row>strong{width:70px;text-align:right;font-size:12px}.analytics-track{height:16px;background:#eef2f6;border-radius:10px;position:relative;flex:1;overflow:hidden}.analytics-target,.analytics-output{position:absolute;left:0;top:0;height:100%;border-radius:10px}.analytics-target{background:#b8c9e8}.analytics-output{background:#4778c7}.legend{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px}.legend.target{background:#b8c9e8}.legend.output{background:#4778c7}
</style>
@endpush
