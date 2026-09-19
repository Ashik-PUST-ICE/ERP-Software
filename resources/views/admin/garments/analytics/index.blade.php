@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('script')
<script src="{{ asset('admin/js/garment-analytics.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush

@section('content')
<input type="hidden" id="garment-analytics-data-url" value="{{ route('admin.garments.analytics.api.cards') }}">

<div class="section-title d-flex justify-content-between align-items-center">
    <h2 class="title">{{ __($title) }}</h2>
    <a class="primary-btn" href="{{ route('admin.garments.analytics.data') }}" target="_blank">
        <i class="fa-solid fa-code me-2"></i>{{ __('View Report Data') }}
    </a>
</div>

<div class="row gy-4 mb-20 garment-dashboard-kpis">
    @foreach([
        ['kpiAnalyticsOrders', 'Total Orders', 'fa-clipboard-list', '#4778c7'],
        ['kpiAnalyticsPlanned', 'Planned Quantity', 'fa-bullseye', '#02BCFF'],
        ['kpiAnalyticsProduced', 'Produced Quantity', 'fa-industry', '#0FA958'],
        ['kpiAnalyticsAchievement', 'Plan Achievement', 'fa-chart-line', '#FFC402'],
    ] as $card)
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon"><i class="fa-solid {{ $card[2] }}" style="color:white;background:{{ $card[3] }};border-radius:50%;padding:11px;"></i></span>
                <div class="card-info">
                    <h2 id="{{ $card[0] }}">--</h2>
                    <h3>{{ __($card[1]) }}</h3>
                </div>
                <span class="card-status up">{{ __('Live data') }} <span class="arrow"><i class="fa-solid fa-arrows-rotate"></i></span></span>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="section-wrap h-100 garment-analytics-panel">
            <h4 class="mb-4">{{ __('Last 14 Days: Target vs Output') }}</h4>
            <div id="garmentAnalyticsDailyOutput"><div class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading from server...') }}</div></div>
            <div class="small mt-3"><span class="legend target"></span>{{ __('Target') }} <span class="legend output ms-3"></span>{{ __('Output') }}</div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="section-wrap h-100 garment-analytics-panel">
            <h4 class="mb-4">{{ __('Order Status') }}</h4>
            <div id="garmentAnalyticsOrderStatus"><div class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading from server...') }}</div></div>
            <div class="mt-4 analytics-rejection"><p class="mb-1 text-muted">{{ __('Quality Rejection Rate') }}</p><h3 id="analyticsKpiRejectionRate">--</h3></div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.analytics-row{display:flex;align-items:center;gap:12px;margin-bottom:12px}.analytics-row>span{width:58px;font-size:12px}.analytics-row>strong{width:70px;text-align:right;font-size:12px}.analytics-track{height:16px;background:#eef2f6;border-radius:10px;position:relative;flex:1;overflow:hidden}.analytics-target,.analytics-output{position:absolute;left:0;top:0;height:100%;border-radius:10px}.analytics-target{background:#b8c9e8}.analytics-output{background:#4778c7}.legend{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px}.legend.target{background:#b8c9e8}.legend.output{background:#4778c7}
</style>
@endpush
