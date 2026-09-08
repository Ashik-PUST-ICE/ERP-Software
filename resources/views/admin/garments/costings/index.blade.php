@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-costing-modal"><i class="fa fa-plus me-2"></i>{{ __('Add Costing') }}</button></div>
<div class="settings-page-area"><div class="settings-page-right"><div class="section-wrap"><div class="table-waraper">
    <div class="search-input-wrap mb-3"><label class="icon" for="costingSearchData"><i class="fa-solid fa-magnifying-glass"></i></label><input type="text" class="search-input" id="costingSearchData" placeholder="{{ __('Search costing sheets...') }}" /></div>
    <input type="hidden" id="garment-costing-data-route" value="{{ route('admin.garments.costings.index') }}">
    <table class="display primary-table dataTable dtr-inline" id="garmentCostingDataTable"><thead><tr>
        <th class="keep-show">{{ __('SL') }}</th><th>{{ __('Order No.') }}</th><th>{{ __('Style') }}</th><th>{{ __('Buyer') }}</th><th>{{ __('Total Cost') }}</th><th>{{ __('FOB') }}</th><th>{{ __('Status') }}</th><th class="keep-show">{{ __('Action') }}</th>
    </tr></thead><tbody></tbody></table>
</div></div></div></div>

<div class="modal fade zModalTwo costing-modal" id="add-costing-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content">@include('admin.garments.costings.form', ['costing' => null, 'orders' => $orders])</div></div></div>
<div class="modal fade zModalTwo costing-modal" id="edit-costing-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content"></div></div></div>
@endsection

@push('style')
<style>
    #add-costing-modal .modal-dialog, #edit-costing-modal .modal-dialog { max-width: 1000px; height: calc(100% - 2rem); min-height: 0; }
    #add-costing-modal .modal-content, #edit-costing-modal .modal-content { height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
    #add-costing-modal form, #edit-costing-modal form { display: flex; flex-direction: column; height: 100%; min-height: 0; }
    #add-costing-modal .modal-body, #edit-costing-modal .modal-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; padding: 28px 30px 24px; }
    .costing-form-section { border: 1px solid #eee8e5; border-radius: 8px; padding: 18px; background: #fff; }
    .costing-form-section + .costing-form-section { margin-top: 16px; }
    .costing-form-section-title { color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 15px; }
    .costing-form-section-title i { color: #ff4f02; margin-right: 8px; }
    .costing-modal .form-group { margin-bottom: 0; }
    .costing-modal .form-control { min-height: 44px; }
    .costing-modal textarea.form-control { min-height: 82px; resize: vertical; }
    .costing-summary { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 16px; }
    .costing-summary-item { border: 1px solid #eee8e5; border-radius: 8px; padding: 13px; background: #fffaf8; }
    .costing-summary-label { display: block; color: #837775; font-size: 12px; margin-bottom: 4px; }
    .costing-summary-value { color: #1b1c17; font-size: 17px; font-weight: 600; }
    .costing-modal-footer { display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee8e5; margin-top: 22px; padding-top: 18px; }
    @media (max-width: 767px) { .costing-summary { grid-template-columns: repeat(2, 1fr); } .costing-modal .modal-body { padding: 22px 18px 18px; } }
</style>
@endpush

@push('script')<script src="{{ asset('admin/js/garment-costings.js') }}"></script>@endpush
