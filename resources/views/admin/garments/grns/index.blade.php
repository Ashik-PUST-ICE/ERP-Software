@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-grn-modal"><i class="fa fa-plus me-2"></i>{{ __('Add GRN') }}</button></div>
<div class="settings-page-area"><div class="settings-page-right"><div class="section-wrap"><div class="table-waraper">
    <div class="search-input-wrap mb-3"><label class="icon" for="grnSearchData"><i class="fa-solid fa-magnifying-glass"></i></label><input type="text" class="search-input" id="grnSearchData" placeholder="{{ __('Search GRN, supplier or material...') }}" /></div>
    <input type="hidden" id="garment-grn-data-route" value="{{ route('admin.garments.grns.index') }}">
    <table class="display primary-table dataTable dtr-inline" id="garmentGrnDataTable"><thead><tr>
        <th class="keep-show">{{ __('SL') }}</th><th>{{ __('GRN No.') }}</th><th>{{ __('Material') }}</th><th>{{ __('Supplier') }}</th><th>{{ __('Accepted Qty') }}</th><th>{{ __('Received Date') }}</th><th>{{ __('Status') }}</th><th class="keep-show">{{ __('Action') }}</th>
    </tr></thead><tbody></tbody></table>
</div></div></div></div>

<div class="modal fade zModalTwo grn-modal" id="add-grn-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content">@include('admin.garments.grns.form', ['grn' => null, 'materials' => $materials, 'purchaseOrders' => $purchaseOrders])</div></div></div>
<div class="modal fade zModalTwo grn-modal" id="edit-grn-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content"></div></div></div>
@endsection

@push('style')
<style>
    #add-grn-modal .modal-dialog, #edit-grn-modal .modal-dialog { max-width: 960px; height: calc(100% - 2rem); min-height: 0; }
    #add-grn-modal .modal-content, #edit-grn-modal .modal-content { height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
    #add-grn-modal form, #edit-grn-modal form { display: flex; flex-direction: column; height: 100%; min-height: 0; }
    #add-grn-modal .modal-body, #edit-grn-modal .modal-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; padding: 28px 30px 24px; }
    .grn-form-section { border: 1px solid #eee8e5; border-radius: 8px; padding: 18px; background: #fff; }
    .grn-form-section + .grn-form-section { margin-top: 16px; }
    .grn-form-section-title { color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 15px; }
    .grn-form-section-title i { color: #ff4f02; margin-right: 8px; }
    .grn-modal .form-group { margin-bottom: 0; }
    .grn-modal .form-control { min-height: 44px; }
    .grn-modal textarea.form-control { min-height: 82px; resize: vertical; }
    .grn-summary { display: flex; gap: 12px; align-items: center; margin-top: 14px; color: #837775; font-size: 13px; }
    .grn-summary strong { color: #1b1c17; }
    .grn-modal-footer { display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee8e5; margin-top: 22px; padding-top: 18px; }
</style>
@endpush

@push('script')<script src="{{ asset('admin/js/garment-grns.js') }}"></script>@endpush
