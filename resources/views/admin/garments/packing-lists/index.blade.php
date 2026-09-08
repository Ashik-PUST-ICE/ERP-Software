@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-packing-list-modal"><i class="fa fa-plus me-2"></i>{{ __('Add Packing Row') }}</button></div>
<div class="settings-page-area"><div class="settings-page-right"><div class="section-wrap"><div class="table-waraper"><div class="search-input-wrap mb-3"><label class="icon" for="packingListSearch"><i class="fa-solid fa-magnifying-glass"></i></label><input class="search-input" id="packingListSearch" placeholder="{{ __('Search order or carton...') }}"></div><input type="hidden" id="packing-list-route" value="{{ route('admin.garments.packing-lists.index') }}"><table class="display primary-table dataTable dtr-inline" id="garmentPackingListDataTable"><thead><tr><th class="keep-show">{{ __('SL') }}</th><th>{{ __('Order') }}</th><th>{{ __('Carton') }}</th><th>{{ __('Color / Size') }}</th><th>{{ __('Qty') }}</th><th>{{ __('Net / Gross') }}</th><th>{{ __('Date') }}</th><th>{{ __('Status') }}</th><th class="keep-show">{{ __('Action') }}</th></tr></thead><tbody></tbody></table></div></div></div></div>
<div class="modal fade zModalTwo" id="add-packing-list-modal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content">@include('admin.garments.packing-lists.form',['packing'=>null,'orders'=>$orders])</div></div></div><div class="modal fade zModalTwo" id="edit-packing-list-modal" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content zModalTwo-content"></div></div></div>
@endsection
@push('style')
<style>
    #add-packing-list-modal .modal-dialog, #edit-packing-list-modal .modal-dialog { max-width: 980px; height: calc(100% - 2rem); min-height: 0; }
    #add-packing-list-modal .modal-content, #edit-packing-list-modal .modal-content { height: 100%; max-height: 100%; overflow: hidden; }
    #add-packing-list-modal form, #edit-packing-list-modal form { display: flex; flex-direction: column; height: 100%; }
    #add-packing-list-modal .modal-body, #edit-packing-list-modal .modal-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; padding: 30px; }
    .packing-form-section { border: 1px solid #eee8e5; border-radius: 10px; background: #fff; padding: 20px; }
    .packing-form-section + .packing-form-section { margin-top: 16px; }
    .packing-form-section-heading { display: flex; align-items: center; gap: 12px; border-bottom: 1px solid #f0ecea; padding-bottom: 14px; margin-bottom: 18px; }
    .packing-form-section-heading h5 { color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 3px; }
    .packing-form-section-heading p { color: #808080; font-size: 12px; margin: 0; }
    .packing-form-icon { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; color: #ff4f02; background: #fff2ec; }
    .packing-form .form-group { margin-bottom: 0; }
    .packing-form .form-control { min-height: 44px; }
</style>
@endpush
@push('script')<script src="{{ asset('admin/js/garment-packing-lists.js') }}"></script>@endpush
