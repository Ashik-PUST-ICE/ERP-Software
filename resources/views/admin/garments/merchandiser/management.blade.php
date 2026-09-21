@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<input type="hidden" id="merchandiser-management-data-route" value="{{ route('admin.garments.merchandiser.management.data') }}">

<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-4 col-md-6">
        <div class="section-wrap p-4 h-100">
            <p class="text-muted mb-2">{{ __('Order Assignments') }}</p>
            <h3 id="managementOrderCount">0</h3>
            <a href="{{ route('admin.garments.merchandiser.orders') }}" class="primary-btn mt-3">
                <i class="fa fa-arrow-right me-2"></i>{{ __('View Orders') }}
            </a>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="section-wrap p-4 h-100">
            <p class="text-muted mb-2">{{ __('Merchandiser Tasks') }}</p>
            <h3 id="managementTaskCount">0</h3>
            <a href="{{ route('admin.garments.merchandiser.tasks') }}" class="primary-btn mt-3">
                <i class="fa fa-arrow-right me-2"></i>{{ __('View Tasks') }}
            </a>
        </div>
    </div>
    <div class="col-xl-4 col-md-6">
        <div class="section-wrap p-4 h-100">
            <p class="text-muted mb-2">{{ __('Buyer Communications') }}</p>
            <h3 id="managementCommunicationCount">0</h3>
            <a href="{{ route('admin.garments.merchandiser.communications') }}" class="primary-btn mt-3">
                <i class="fa fa-arrow-right me-2"></i>{{ __('View Communications') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
(function ($) {
    "use strict";
    $(document).ready(function () {
        var baseUrl = $('#merchandiser-management-data-route').val() || '';
        if (!baseUrl) return;
        $.get(baseUrl, { section: 'cards' }, function (response) {
            var cards = (response && response.cards) || {};
            $('#managementOrderCount').text(Number(cards.orders || 0).toLocaleString());
            $('#managementTaskCount').text(Number(cards.tasks || 0).toLocaleString());
            $('#managementCommunicationCount').text(Number(cards.communications || 0).toLocaleString());
        });
    });
})(jQuery);
</script>
@endpush
