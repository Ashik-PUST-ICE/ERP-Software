@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('script')
<script src="{{ asset('admin/js/garment-dashboard.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush

@section('content')
<input type="hidden" id="garment-dashboard-data-url" value="{{ route('admin.garments.dashboard.data') }}">

<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <span class="text-muted" style="font-size:1.3rem;">{{ now()->format('l, d F Y') }}</span>
</div>

<div class="row gy-4 mb-20 garment-dashboard-kpis">
    @foreach([
        ['kpiOverdueOrders', 'Overdue Orders', 'fa-calendar-xmark', '#FF4F02', route('admin.garments.orders.index')],
        ['kpiLowStock', 'Low Stock Items', 'fa-boxes-stacked', '#FFC402', route('admin.garments.materials.index')],
        ['kpiPendingFinishing', 'Pending Finishing', 'fa-shirt', '#02BCFF', route('admin.garments.finishing.index')],
        ['kpiReadyShipments', 'Ready Shipments', 'fa-ship', '#0FA958', route('admin.garments.shipment-documents.index')],
    ] as $card)
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon"><i class="fa-solid {{ $card[2] }}" style="color:white;background:{{ $card[3] }};border-radius:50%;padding:11px;"></i></span>
                <div class="card-info">
                    <h2 id="{{ $card[0] }}">--</h2>
                    <h3>{{ __($card[1]) }}</h3>
                </div>
                <span class="card-status up"><a href="{{ $card[4] }}" style="color:inherit;text-decoration:none;">{{ __('View') }}</a><span class="arrow"><i class="fa-solid fa-arrow-up"></i></span></span>
            </div>
        </div>
    @endforeach
</div>

<div class="row gy-4">
    <div class="col-xl-8 col-lg-7">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title">{{ __('Recent Orders') }}</h3>
                <a href="{{ route('admin.garments.orders.index') }}" class="text-primary" style="font-size:1.2rem;">{{ __('View All') }}</a>
            </div>
            <table class="display primary-table w-100" id="garmentRecentOrdersTable" data-url="{{ route('admin.garments.orders.index') }}">
                <thead><tr><th>{{ __('Order') }}</th><th>{{ __('Buyer') }}</th><th>{{ __('Quantity') }}</th><th>{{ __('Delivery') }}</th></tr></thead>
                <tbody><tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}</td></tr></tbody>
            </table>
        </div>
    </div>
    <div class="col-xl-4 col-lg-5">
        <div class="section-wrap h-100">
            <div class="section-small-title"><h3 class="title">{{ __('Latest Notifications') }}</h3><i class="fa-regular fa-bell text-primary"></i></div>
            <div id="garmentNotifications"><div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}</div></div>
        </div>
    </div>
</div>
@endsection
