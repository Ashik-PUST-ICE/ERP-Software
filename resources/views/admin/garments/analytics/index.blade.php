@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title d-flex justify-content-between align-items-center">
    <h2 class="title">{{ __($title) }}</h2>
    <a class="primary-btn" href="{{ route('admin.garments.analytics.data') }}" target="_blank">
        <i class="fa-solid fa-code me-2"></i>{{ __('View Report Data') }}
    </a>
</div>

<div class="row g-3 mb-4">
    @foreach([['orders','Total Orders','fa-clipboard-list'],['planned_quantity','Planned Quantity','fa-bullseye'],['produced_quantity','Produced Quantity','fa-industry'],['achievement','Plan Achievement','fa-chart-line']] as $card)
        <div class="col-xl-3 col-md-6">
            <div class="section-wrap p-4">
                <p class="text-muted mb-2">{{ __($card[1]) }}</p>
                <h3 class="mb-1">{{ number_format($summary['kpis'][$card[0]], 1) }}{{ $card[0] === 'achievement' ? '%' : '' }}</h3>
                <i class="fa-solid {{ $card[2] }} text-primary"></i>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-8">
        <div class="section-wrap p-4">
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
        <div class="section-wrap p-4">
            <h4 class="mb-4">{{ __('Order Status') }}</h4>
            @forelse($summary['order_status'] as $item)
                <div class="d-flex justify-content-between border-bottom py-2"><span>{{ __('Status') }} {{ $item['status'] }}</span><strong>{{ $item['total'] }}</strong></div>
            @empty
                <p class="text-muted">{{ __('No order data available') }}</p>
            @endforelse
            <div class="mt-4"><p class="mb-1 text-muted">{{ __('Quality Rejection Rate') }}</p><h3>{{ $summary['kpis']['rejection_rate'] }}%</h3></div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
.analytics-row{display:flex;align-items:center;gap:12px;margin-bottom:12px}.analytics-row>span{width:58px;font-size:12px}.analytics-row>strong{width:70px;text-align:right;font-size:12px}.analytics-track{height:16px;background:#f2eeee;border-radius:10px;position:relative;flex:1;overflow:hidden}.analytics-target,.analytics-output{position:absolute;left:0;top:0;height:100%;border-radius:10px}.analytics-target{background:#f4d8ca}.analytics-output{background:#ff6b35}.legend{display:inline-block;width:10px;height:10px;border-radius:50%;margin-right:4px}.legend.target{background:#f4d8ca}.legend.output{background:#ff6b35}
</style>
@endpush
