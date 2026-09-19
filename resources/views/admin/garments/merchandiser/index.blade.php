@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('script')
<script src="{{ asset('admin/js/garment-merchandiser.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush

@section('content')
<input type="hidden" id="merchandiser-data-url" value="{{ route('admin.garments.merchandiser.index') }}">

<div class="section-title"><h2 class="title">{{ __($title) }}</h2></div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="section-wrap p-4">
            <h4 class="mb-3">{{ __('Order Follow-up') }}</h4>
            <div class="table-responsive">
                <table class="display primary-table w-100" id="merchandiserOrdersTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('Order') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Delivery') }}</th>
                            <th class="keep-show">{{ __('Qty') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}</td></tr>
                    </tbody>
                </table>
            </div>
            <div id="merchandiser-orders-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="section-wrap p-4">
            <h4 class="mb-3">{{ __('Overdue TNA Tasks') }}</h4>
            <div id="merchandiserOverdueTasks"><div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}</div></div>
        </div>
    </div>
    <div class="col-12">
        <div class="section-wrap p-4">
            <h4 class="mb-3">{{ __('Shipment Document Follow-up') }}</h4>
            <div id="merchandiserShipments"><div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> {{ __('Loading...') }}</div></div>
        </div>
    </div>
</div>
@endsection
