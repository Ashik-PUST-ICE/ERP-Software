@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ __($pageTitle) }}
@endpush


@section('content')
<div class="section-title">
    <h2 class="title">{{ __($pageTitle) }}</h2>
</div>
<div class="settings-page-area refund-request-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">


            <div class="table-waraper">
                <input type="hidden" id="refund-request-route"
                    value="{{ route('super_admin.subscription-refund.list') }}">
                <table class="display primary-table dataTable dtr-inline" id="refundRequestDatatable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __("SL") }}</th>
                            <th>{{ __("Subscription Type") }}</th>
                            <th>{{ __("Name") }}</th>
                            <th>{{ __("Email") }}</th>
                            <th>{{ __("Package Name") }}</th>
                            <th>{{ __("Refund Reason") }}</th>
                            <th>{{ __("Request time") }}</th>
                            <th>{{ __("Status") }}</th>
                            <th class="keep-show">{{ __("Action") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="refund-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('admin/js/refund-request.js') }}"></script>
@endpush