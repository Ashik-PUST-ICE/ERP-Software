@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="garment-page" data-dashboard-url="{{ route('admin.garments.dashboard.data') }}">
<div class="section-title garment-dashboard-heading"><div><span class="garment-eyebrow">{{ __('Operations overview') }}</span><h2 class="title">{{ __($title) }}</h2><p>{{ __('Live production and fulfilment signals') }}</p></div><button type="button" class="garment-refresh-btn" data-dashboard-refresh><i class="fa-solid fa-rotate-right"></i> {{ __('Refresh') }}</button></div>
<div class="row g-3 mb-4 garment-kpi-row">@foreach([['overdueOrders','Overdue Orders','fa-calendar-xmark','red'],['lowStock','Low Stock Items','fa-boxes-stacked','amber'],['pendingFinishing','Pending Finishing','fa-shirt','blue'],['readyShipments','Ready Shipments','fa-ship','green']] as $card)<div class="col-xl-3 col-md-6"><div class="garment-kpi-card {{ $card[3] }}" data-card-key="{{ $card[0] }}"><div class="garment-kpi-icon"><i class="fa-solid {{ $card[2] }}"></i></div><div><span>{{ __($card[1]) }}</span><strong data-card-value>--</strong><small>{{ __('Live count') }}</small></div></div></div>@endforeach</div>
<div class="row g-4"><div class="col-xl-8"><div class="garment-panel"><div class="garment-panel-heading"><div><span class="garment-eyebrow">{{ __('Production flow') }}</span><h4>{{ __('Recent Orders') }}</h4></div><span class="garment-live-dot">{{ __('Live') }}</span></div><div class="table-responsive"><table class="table garment-orders-table"><thead><tr><th>{{ __('Order') }}</th><th>{{ __('Buyer') }}</th><th>{{ __('Quantity') }}</th><th>{{ __('Delivery') }}</th></tr></thead><tbody data-orders-body><tr><td colspan="4"><div class="garment-table-loader"></div></td></tr></tbody></table></div></div></div><div class="col-xl-4"><div class="garment-panel garment-notifications"><div class="garment-panel-heading"><div><span class="garment-eyebrow">{{ __('Attention center') }}</span><h4>{{ __('Latest Notifications') }}</h4></div><i class="fa-regular fa-bell"></i></div><div data-notifications-body><div class="garment-list-loader"></div></div></div></div></div>
<div class="garment-dashboard-error" data-dashboard-error hidden><i class="fa-solid fa-triangle-exclamation"></i><span>{{ __('Dashboard data could not be loaded.') }}</span><button type="button" data-dashboard-retry>{{ __('Try again') }}</button></div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/garment-dashboard.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
