@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-order-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Add Order') }}
    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="orderSearchData"><i class="fa-solid fa-magnifying-glass"></i></label>
                    <input type="text" class="search-input" id="orderSearchData" placeholder="{{ __('Search styles, orders or buyers...') }}" />
                </div>
                <input type="hidden" id="garment-order-data-route" value="{{ route('admin.garments.orders.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="garmentOrderDataTable">
                    <thead><tr>
                        <th class="keep-show">{{ __('SL') }}</th>
                        <th>{{ __('Style') }}</th>
                        <th>{{ __('Order No.') }}</th>
                        <th>{{ __('Buyer') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Delivery Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="keep-show">{{ __('Action') }}</th>
                    </tr></thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo order-modal" id="add-order-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content">
            @include('admin.garments.orders.form', ['order' => null, 'buyers' => $buyers, 'styles' => $styles])
        </div>
    </div>
</div>
<div class="modal fade zModalTwo order-modal" id="edit-order-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content"></div>
    </div>
</div>
@endsection

@push('style')
<style>
    #add-order-modal .modal-dialog, #edit-order-modal .modal-dialog { max-width: 960px; height: calc(100% - 2rem); min-height: 0; }
    #add-order-modal .modal-content, #edit-order-modal .modal-content { height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
    #add-order-modal form, #edit-order-modal form { display: flex; flex-direction: column; height: 100%; min-height: 0; }
    #add-order-modal .modal-body, #edit-order-modal .modal-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; padding: 28px 30px 24px; }
    .order-form-section { border: 1px solid #eee8e5; border-radius: 8px; padding: 18px; background: #fff; }
    .order-form-section + .order-form-section { margin-top: 16px; }
    .order-form-section-title { color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 15px; }
    .order-form-section-title i { color: #ff4f02; margin-right: 8px; }
    .order-modal .form-group { margin-bottom: 0; }
    .order-modal .form-control { min-height: 44px; }
    .order-modal textarea.form-control { min-height: 88px; resize: vertical; }
    .order-modal-footer { display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee8e5; margin-top: 22px; padding-top: 18px; }
</style>
@endpush

@push('script')
<script src="{{ asset('admin/js/garment-orders.js') }}"></script>
@endpush
