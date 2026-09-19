@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('style')
<style>
/* Buyer Portal KPI cards - match garment dashboard style */
.buyer-portal-kpis .card-box {
    background: var(--garment-card-color);
    border: 0;
    border-top: 4px solid var(--garment-card-dark);
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(30, 41, 59, .06);
    min-height: 15.5rem;
    overflow: hidden;
    transition: box-shadow .2s ease, transform .2s ease;
}
.buyer-portal-kpis .card-box:hover {
    box-shadow: 0 12px 28px rgba(30, 41, 59, .12);
    transform: translateY(-3px);
}
.buyer-portal-kpis .card-icon {
    right: 1.25rem;
    top: 1.25rem;
}
.buyer-portal-kpis .card-icon i {
    align-items: center;
    background: rgba(255,255,255,.2) !important;
    border-radius: 12px !important;
    color: #fff !important;
    display: inline-flex;
    font-size: 1.35rem;
    height: 48px;
    justify-content: center;
    padding: 0 !important;
    width: 48px;
}
.buyer-portal-kpis .card-info h2 {
    color: #fff;
    font-size: 2.8rem;
    font-weight: 700;
    margin-bottom: .35rem;
}
.buyer-portal-kpis .card-info h3 {
    color: rgba(255,255,255,.88);
    font-size: .95rem;
    font-weight: 500;
}
.buyer-portal-kpis .card-status {
    color: #fff;
    font-size: .82rem;
    font-weight: 600;
}
.buyer-portal-kpis > div:nth-child(1),
.buyer-portal-kpis .col-xl-3:nth-child(1) {
    --garment-card-color: #4778c7;
    --garment-card-dark: #315b9d;
}
.buyer-portal-kpis > div:nth-child(2),
.buyer-portal-kpis .col-xl-3:nth-child(2) {
    --garment-card-color: #02BCFF;
    --garment-card-dark: #0291c5;
}
.buyer-portal-kpis > div:nth-child(3),
.buyer-portal-kpis .col-xl-3:nth-child(3) {
    --garment-card-color: #bd8517;
    --garment-card-dark: #8d620d;
}
.buyer-portal-kpis > div:nth-child(4),
.buyer-portal-kpis .col-xl-3:nth-child(4) {
    --garment-card-color: #2c9567;
    --garment-card-dark: #1d704b;
}

/* Profile info card */
.bp-profile-info {
    display: flex;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13.5px;
    gap: 12px;
}
.bp-profile-info:last-child { border-bottom: none; }
.bp-profile-label {
    min-width: 140px;
    color: #64748b;
    font-weight: 500;
    flex-shrink: 0;
}
.bp-profile-value {
    color: #1e293b;
    font-weight: 600;
    word-break: break-word;
}

/* Search/buyer select filter row */
.buyer-portal-filter .search-input-wrap {
    margin-bottom: 0;
}

@media (max-width: 575px) {
    .buyer-portal-kpis .card-box { min-height: 13rem; padding: 1rem; }
    .buyer-portal-kpis .card-info h2 { font-size: 2.2rem; }
}
</style>
@endpush

@push('script')
<script src="{{ asset('admin/js/garment-buyer-portal.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush

@section('content')
<input type="hidden" id="buyer-data-route" value="{{ route('admin.garments.buyer-portal.index') }}">

{{-- Page Header --}}
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="title">{{ __($title) }}</h2>
        <p class="text-muted mb-0" style="font-size:13.5px;">{{ __('Buyer profile, portfolio overview and real-time order tracking') }}</p>
    </div>
</div>

{{-- Buyer Search / Filter --}}
<div class="section-wrap p-3 buyer-portal-filter" style="border-radius:12px 12px 0 0;margin-bottom:0!important;">
    <div class="d-flex align-items-center gap-3 flex-wrap">
        <div class="search-input-wrap" style="flex:1;max-width:420px;">
            <label class="icon" for="buyerPortalBuyerSelect">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </label>
            <select class="search-input" id="buyerPortalBuyerSelect" style="cursor:pointer;">
                @foreach($buyers as $item)
                    <option value="{{ $item->id }}" @selected($buyer?->id === $item->id)>
                        {{ $item->company_name }}  ({{ $item->buyer_code ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-muted small">
            <i class="fa-solid fa-circle-info me-1 text-primary"></i>
            {{ __('Select a buyer to view their profile and all associated orders') }}
        </div>
    </div>
</div>

{{-- KPI Cards Row (matches dashboard style) --}}
<div class="row gy-4 mb-20 buyer-portal-kpis">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-clipboard-list"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalTotalOrders">{{ number_format($stats['total_orders'] ?? 0) }}</h2>
                <h3>{{ __('Total Orders') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalTotalQuantity">{{ number_format($stats['total_quantity'] ?? 0) }}</h2>
                <h3>{{ __('Total Quantity (Pcs)') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-industry"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalInProduction">{{ number_format($stats['in_production_orders'] ?? 0) }}</h2>
                <h3>{{ __('In Production') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-circle-check"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalCompleted">{{ number_format($stats['completed_orders'] ?? 0) }}</h2>
                <h3>{{ __('Completed Orders') }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Buyer Profile + Orders Table --}}
<div class="row g-4">
    {{-- Profile Card --}}
    <div class="col-xl-4 col-lg-12">
        <div class="section-wrap p-4 h-100">
            <div class="d-flex align-items-center gap-3 pb-3 mb-3 border-bottom">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(71,120,199,.12);color:#4778c7;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <h4 id="buyerPortalName" class="fw-700 mb-1" style="color:#0f172a;font-size:1.05rem;">{{ $buyer?->company_name ?? '--' }}</h4>
                    <span id="buyerPortalCode" class="zBadge zBadge-primary">{{ $buyer?->buyer_code ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="bp-profile-info">
                <span class="bp-profile-label"><i class="fa-solid fa-user text-primary" style="width:16px;"></i> {{ __('Contact:') }}</span>
                <span id="buyerPortalContact" class="bp-profile-value">{{ $buyer?->contact_person ?? '--' }}</span>
            </div>
            <div class="bp-profile-info">
                <span class="bp-profile-label"><i class="fa-solid fa-envelope text-info" style="width:16px;"></i> {{ __('Email:') }}</span>
                <a id="buyerPortalEmail" href="{{ $buyer?->email ? 'mailto:'.$buyer->email : '#' }}" class="bp-profile-value text-primary">{{ $buyer?->email ?? '--' }}</a>
            </div>
            <div class="bp-profile-info">
                <span class="bp-profile-label"><i class="fa-solid fa-phone text-success" style="width:16px;"></i> {{ __('Phone:') }}</span>
                <span id="buyerPortalPhone" class="bp-profile-value">{{ $buyer?->phone ?? '--' }}</span>
            </div>
            <div class="bp-profile-info">
                <span class="bp-profile-label"><i class="fa-solid fa-globe text-secondary" style="width:16px;"></i> {{ __('Country:') }}</span>
                <span id="buyerPortalCountryCurrency" class="bp-profile-value">{{ $buyer?->country ?? '--' }} ({{ $buyer?->currency ?? 'USD' }})</span>
            </div>
            <div class="bp-profile-info">
                <span class="bp-profile-label"><i class="fa-solid fa-file-invoice-dollar text-warning" style="width:16px;"></i> {{ __('Payment:') }}</span>
                <span id="buyerPortalPaymentTerms" class="bp-profile-value">{{ $buyer?->payment_terms ?? '--' }}</span>
            </div>
            <div class="bp-profile-info">
                <span class="bp-profile-label"><i class="fa-solid fa-location-dot text-danger" style="width:16px;"></i> {{ __('Address:') }}</span>
                <span id="buyerPortalAddress" class="bp-profile-value text-muted small">{{ $buyer?->office_address ?: ($buyer?->shipping_address ?? '--') }}</span>
            </div>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="col-xl-8 col-lg-12">
        <div class="section-wrap p-4 h-100">
            <div class="section-small-title d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h3 class="title">{{ __('Order Status Overview') }}</h3>
                <a href="{{ route('admin.garments.orders.index') }}" class="text-primary" style="font-size:1.2rem;">{{ __('View All') }}</a>
            </div>
            <div class="table-responsive">
                <table class="display primary-table w-100" id="buyerPortalOrdersTable">
                    <thead>
                        <tr>
                            <th>{{ __('Order #') }}</th>
                            <th>{{ __('Style') }}</th>
                            <th>{{ __('Qty (Pcs)') }}</th>
                            <th>{{ __('Order Date') }}</th>
                            <th>{{ __('Delivery') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fa fa-spinner fa-spin me-2"></i>{{ __('Loading...') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="buyer-portal-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
        </div>
    </div>
</div>
@endsection
