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
                <form method="post" action="{{ route('admin.garments.merchandiser.handover') }}" class="merchandiser-handover-form">
                    @csrf
                    <div class="merchandiser-form-heading"><span class="merchandiser-form-icon"><i class="fa-solid fa-people-arrows"></i></span><div><h4>{{ __('Assign a new merchandiser') }}</h4><p>{{ __('Keep order ownership and notes in one place.') }}</p></div></div>
                    <label for="insightOrderSelect">{{ __('Order') }}</label>
                    <div class="merchandiser-field"><i class="fa-solid fa-box"></i><select name="order_id" id="insightOrderSelect" required><option value="">{{ __('Select Order') }}</option>@foreach($orders as $order)<option value="{{ $order->id }}">{{ $order->order_number }} - {{ $order->buyer?->company_name }}</option>@endforeach</select></div>
                    <label for="insightUserSelect">{{ __('New Merchandiser') }}</label>
                    <div class="merchandiser-field"><i class="fa-solid fa-user"></i><select name="to_user_id" id="insightUserSelect" required><option value="">{{ __('New Merchandiser') }}</option>@foreach($users as $user)<option value="{{ $user->id }}">{{ $user->name }}</option>@endforeach</select></div>
                    <label for="handoverNotes">{{ __('Handover Notes') }}</label>
                    <textarea name="notes" id="handoverNotes" placeholder="{{ __('Handover notes') }}" required></textarea>
                    <button class="primary-btn merchandiser-submit" type="submit"><i class="fa-solid fa-paper-plane"></i>{{ __('Hand Over Order') }}</button>
                </form>
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
@endsection
