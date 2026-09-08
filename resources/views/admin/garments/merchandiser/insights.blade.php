@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@push('script')
<script src="{{ asset('admin/js/garment-merchandiser-insights.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
@section('content')
<input type="hidden" id="garment-merchandiser-insights-data-url" value="{{ route('admin.garments.merchandiser.insights.data') }}">
<div class="section-title d-flex justify-content-between align-items-center">
    <h2 class="title">{{ __($title) }}</h2>
    <span class="merchandiser-live-status"><i class="fa-solid fa-circle"></i> {{ __('Live dashboard') }}</span>
</div>

<div class="row gy-4 mb-20 hrm-dashboard-kpis merchandiser-insights-kpis">
    @foreach([
        ['insightOrders', 'Assigned Orders', 'fa-list-check'],
        ['insightOverdueTasks', 'Overdue Tasks', 'fa-calendar-xmark'],
        ['insightDeliveryRisk', 'Delivery Risk (7 Days)', 'fa-triangle-exclamation'],
        ['insightBuyers', 'Active Buyers', 'fa-users'],
    ] as $card)
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
            <div class="card-box">
                <span class="card-icon"><i class="fa-solid {{ $card[2] }}"></i></span>
                <div class="card-info"><h2 id="{{ $card[0] }}">--</h2><h3>{{ __($card[1]) }}</h3></div>
                <span class="card-status up">{{ __('Live data') }} <span class="arrow"><i class="fa-solid fa-arrows-rotate"></i></span></span>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="section-wrap p-4 h-100 merchandiser-panel">
            <div class="merchandiser-form-heading">
                <div class="merchandiser-form-icon"><i class="fa-solid fa-right-left"></i></div>
                <div><h3>{{ __('Order Handover') }}</h3><p>{{ __('Transfer ownership to another merchandiser') }}</p></div>
            </div>
            <div class="merchandiser-handover-cta">
                <div class="merchandiser-cta-graphic"><i class="fa-solid fa-user-group"></i><span><i class="fa-solid fa-arrow-right"></i></span><i class="fa-solid fa-user"></i></div>
                <h4>{{ __('Ready to hand over an order?') }}</h4>
                <p>{{ __('Choose an order and assign it to another merchandiser from the secure handover form.') }}</p>
                <button type="button" class="primary-btn merchandiser-open-modal" data-bs-toggle="modal" data-bs-target="#orderHandoverModal"><i class="fa-solid fa-plus me-2"></i>{{ __('Start Handover') }}</button>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="section-wrap p-4 h-100 merchandiser-panel">
            <div class="section-small-title"><h3 class="title">{{ __('Handover History') }}</h3><i class="fa-solid fa-clock-rotate-left text-primary"></i></div>
            <div id="merchandiserHandoverHistory"><div class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading from server...') }}</div></div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo" id="orderHandoverModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" action="{{ route('admin.garments.merchandiser.handover') }}" id="merchandiserHandoverForm">
                @csrf
                <div class="modal-body zModalTwo-body merchandiser-modal-body">
                    <div class="merchandiser-modal-heading">
                        <div class="merchandiser-form-icon"><i class="fa-solid fa-right-left"></i></div>
                        <div><h4>{{ __('Order Handover') }}</h4><p>{{ __('Transfer order ownership') }}</p></div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="merchandiser-handover-form">
                        <label for="insightOrderSelect">{{ __('Order to hand over') }}</label>
                        <div class="merchandiser-field"><i class="fa-solid fa-box"></i><select name="order_id" id="insightOrderSelect" required><option value="">{{ __('Loading orders...') }}</option></select></div>
                        <small class="merchandiser-form-hint"><i class="fa-solid fa-circle-info"></i> {{ __('No active order is available. Create an order first from') }} <a href="{{ route('admin.garments.orders.index') }}">{{ __('Orders') }}</a>.</small>
                        <label for="insightUserSelect">{{ __('Assign to merchandiser') }}</label>
                        <div class="merchandiser-field"><i class="fa-solid fa-user"></i><select name="to_user_id" id="insightUserSelect" required><option value="">{{ __('Loading merchandisers...') }}</option></select></div>
                        <label for="insightHandoverNotes">{{ __('Handover notes') }}</label>
                        <textarea name="notes" id="insightHandoverNotes" rows="4" placeholder="{{ __('Add context or next steps for the new merchandiser...') }}" required></textarea>
                        <button class="primary-btn merchandiser-submit" type="submit"><i class="fa-solid fa-share me-2"></i>{{ __('Hand Over Order') }}<i class="fa-solid fa-arrow-right ms-auto"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
