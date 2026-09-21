@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('script')
<script src="{{ asset('admin/js/garment-merchandiser-insights.js') }}?ver={{ config('app.version', 0) }}"></script>
@endpush

@section('content')
<div class="garment-page" data-card-data-url="{{ route('admin.garments.merchandiser.insights.data') }}">
    <input type="hidden" id="garment-merchandiser-insights-data-url" value="{{ route('admin.garments.merchandiser.insights.data') }}">
    <div class="section-title">
        <h2 class="title">{{ __($title) }}</h2>
        <span class="text-muted" style="font-size:1.3rem;">{{ now()->format('l, d F Y') }}</span>
    </div>

    <div class="row gy-4 mb-20 garment-dashboard-kpis merchandiser-insights-kpis">
        @foreach([
            ['insightOrders', $orderCount, 'Assigned Orders', 'fa-list-check'],
            ['insightOverdueTasks', $overdueTasks, 'Overdue Tasks', 'fa-calendar-xmark'],
            ['insightDeliveryRisk', $deliveryRisk, 'Delivery Risk (7 Days)', 'fa-triangle-exclamation'],
            ['insightBuyers', $buyerCount, 'Active Buyers', 'fa-users'],
        ] as $card)
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card-box">
                    <span class="card-icon"><i class="fa-solid {{ $card[3] }}"></i></span>
                    <div class="card-info"><h2 id="{{ $card[0] }}">{{ number_format($card[1]) }}</h2><h3>{{ __($card[2]) }}</h3></div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row gy-4">
        <div class="col-xl-6">
            <div class="section-wrap p-4 h-100 merchandiser-panel">
                <div class="section-small-title"><h3 class="title">{{ __('Order Handover') }}</h3><i class="fa-solid fa-right-left text-primary"></i></div>
                <div class="merchandiser-handover-cta">
                    <div class="merchandiser-cta-graphic"><i class="fa-solid fa-people-arrows"></i><span><i class="fa-solid fa-arrow-right"></i></span><i class="fa-solid fa-user-tie"></i></div>
                    <h4>{{ __('Assign a new merchandiser') }}</h4>
                    <p>{{ __('Move an order to another merchandiser with notes and a complete handover history.') }}</p>
                    <button type="button" class="primary-btn merchandiser-open-modal" data-bs-toggle="modal" data-bs-target="#orderHandoverModal"><i class="fa-solid fa-right-left me-1"></i>{{ __('Hand Over Order') }}</button>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="section-wrap p-4 h-100 merchandiser-panel">
                <div class="section-small-title"><h3 class="title">{{ __('Handover History') }}</h3><i class="fa-solid fa-clock-rotate-left text-primary"></i></div>
                <div id="merchandiserHandoverHistory">
                    @forelse($handovers as $handover)
                        <div class="merchandiser-handover-item"><strong>{{ $handover->order?->order_number }}</strong><p>{{ $handover->fromUser?->name }} <span>→</span> {{ $handover->toUser?->name }}</p><small>{{ $handover->handed_over_at?->format('d M Y, h:i A') }}</small></div>
                    @empty
                        <div class="merchandiser-empty"><i class="fa-solid fa-clock"></i><span>{{ __('No handover records') }}</span></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="mt-2" data-card-retry hidden>{{ __('Retry loading metrics') }}</button>
</div>

<div class="modal fade zModalTwo" id="orderHandoverModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" action="{{ route('admin.garments.merchandiser.handover') }}" class="merchandiser-handover-form">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="merchandiser-form-heading mb-0 pb-0 border-0"><span class="merchandiser-form-icon"><i class="fa-solid fa-people-arrows"></i></span><div><h4>{{ __('Assign a new merchandiser') }}</h4><p>{{ __('Keep order ownership and notes in one place.') }}</p></div></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="insightOrderSelect">{{ __('Order') }} <span class="required">*</span></label>
                                    <select class="form-control" name="order_id" id="insightOrderSelect" required><option value="">{{ __('Select Order') }}</option>@foreach($orders as $order)<option value="{{ $order->id }}">{{ $order->order_number }} - {{ $order->buyer?->company_name }}</option>@endforeach</select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="insightUserSelect">{{ __('New Merchandiser') }} <span class="required">*</span></label>
                                    <select class="form-control" name="to_user_id" id="insightUserSelect" required><option value="">{{ __('New Merchandiser') }}</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label" for="handoverNotes">{{ __('Handover Notes') }} <span class="required">*</span></label>
                                    <textarea class="form-control" name="notes" id="handoverNotes" rows="3" placeholder="{{ __('Handover notes') }}" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top"><button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button><button class="primary-btn merchandiser-submit" type="submit"><i class="fa-solid fa-paper-plane"></i>{{ __('Hand Over Order') }}</button></div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
