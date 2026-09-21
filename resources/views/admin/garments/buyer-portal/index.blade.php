@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('script')
<script src="{{ asset('admin/js/garment-buyer-portal.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush

@section('content')
<input type="hidden" id="buyer-data-route" value="{{ route('admin.garments.buyer-portal.index') }}">

<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h2 class="title">{{ __($title) }}</h2>
    <select class="form-control w-auto" id="buyerPortalBuyerSelect">
        @foreach($buyers as $item)
            <option value="{{ $item->id }}" @selected($buyer?->id === $item->id)>{{ $item->company_name }}</option>
        @endforeach
    </select>
</div>

<div class="row gy-4 mb-20 garment-dashboard-kpis">
    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-user-tie" style="color:white;background:#4778c7;border-radius:50%;padding:11px;"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalName" style="color:#1b1c17;">{{ $buyer?->company_name ?? '--' }}</h2>
                <h3 id="buyerPortalEmail" style="color:#837775;">{{ $buyer?->email ?? '' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-clipboard-list" style="color:white;background:#0FA958;border-radius:50%;padding:11px;"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalTotalOrders">--</h2>
                <h3>{{ __('Total Orders') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-6">
        <div class="card-box">
            <span class="card-icon"><i class="fa-solid fa-boxes-stacked" style="color:white;background:#02BCFF;border-radius:50%;padding:11px;"></i></span>
            <div class="card-info">
                <h2 id="buyerPortalTotalQuantity">--</h2>
                <h3>{{ __('Total Quantity') }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="section-wrap">
    <div class="section-small-title">
        <h3 class="title">{{ __('Order Status Overview') }}</h3>
    </div>
    <div class="table-responsive">
        <table class="display primary-table w-100" id="buyerPortalOrdersTable">
            <thead>
                <tr>
                    <th>{{ __('Order') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Quantity') }}</th>
                    <th>{{ __('Delivery Date') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="5" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}</td></tr>
            </tbody>
        </table>
    </div>
    <div id="buyer-portal-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
</div>
@endsection
