@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title"><h2 class="title">{{ __($title) }}</h2><button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-material-modal"><i class="fa fa-plus me-2"></i>{{ __('Add Material') }}</button></div>
<div class="settings-page-area"><div class="settings-page-right"><div class="section-wrap"><div class="table-waraper">
    <div class="search-input-wrap mb-3"><label class="icon" for="materialSearchData"><i class="fa-solid fa-magnifying-glass"></i></label><input type="text" class="search-input" id="materialSearchData" placeholder="{{ __('Search materials...') }}" /></div>
    <input type="hidden" id="garment-material-data-route" value="{{ route('admin.garments.materials.index') }}">
    <table class="display primary-table dataTable dtr-inline" id="garmentMaterialDataTable"><thead><tr>
        <th class="keep-show">{{ __('SL') }}</th><th>{{ __('Item Code') }}</th><th>{{ __('Item Name') }}</th><th>{{ __('Category') }}</th><th>{{ __('Stock') }}</th><th>{{ __('Warehouse') }}</th><th>{{ __('Stock Status') }}</th><th>{{ __('Status') }}</th><th class="keep-show">{{ __('Action') }}</th>
    </tr></thead><tbody></tbody></table>
</div></div></div></div>

<div class="modal fade zModalTwo material-modal" id="add-material-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content">@include('admin.garments.materials.form', ['material' => null])</div></div></div>
<div class="modal fade zModalTwo material-modal" id="edit-material-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content"></div></div></div>
@endsection

@push('style')
<style>
    #add-material-modal .modal-dialog, #edit-material-modal .modal-dialog { max-width: 960px; height: calc(100% - 2rem); min-height: 0; }
    #add-material-modal .modal-content, #edit-material-modal .modal-content { height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
    #add-material-modal form, #edit-material-modal form { display: flex; flex-direction: column; height: 100%; min-height: 0; }
    #add-material-modal .modal-body, #edit-material-modal .modal-body { flex: 1 1 auto; min-height: 0; overflow-y: auto; overflow-x: hidden; padding: 28px 30px 24px; }
    .material-form-section { border: 1px solid #eee8e5; border-radius: 8px; padding: 18px; background: #fff; }
    .material-form-section + .material-form-section { margin-top: 16px; }
    .material-form-section-title { color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 15px; }
    .material-form-section-title i { color: #ff4f02; margin-right: 8px; }
    .material-modal .form-group { margin-bottom: 0; }
    .material-modal .form-control { min-height: 44px; }
    .material-modal textarea.form-control { min-height: 82px; resize: vertical; }
    .material-modal-footer { display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee8e5; margin-top: 22px; padding-top: 18px; }
</style>
@endpush

@push('script')<script src="{{ asset('admin/js/garment-materials.js') }}"></script>@endpush
