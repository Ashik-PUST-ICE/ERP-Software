@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-style-modal"><i class="fa fa-plus me-2"></i>{{ __('Add Style') }}</button>
</div>

<div class="settings-page-area"><div class="settings-page-right"><div class="section-wrap"><div class="table-waraper">
    <div class="search-input-wrap mb-3"><label class="icon" for="styleSearchData"><i class="fa-solid fa-magnifying-glass"></i></label><input type="text" class="search-input" id="styleSearchData" placeholder="{{ __('Search styles...') }}" /></div>
    <input type="hidden" id="garment-style-data-route" value="{{ route('admin.garments.styles.index') }}">
    <table class="display primary-table dataTable dtr-inline" id="garmentStyleDataTable"><thead><tr>
        <th class="keep-show">{{ __('SL') }}</th><th>{{ __('Style Code') }}</th><th>{{ __('Style Name') }}</th><th>{{ __('Product Type') }}</th><th>{{ __('Season') }}</th><th>{{ __('Orders') }}</th><th>{{ __('Status') }}</th><th class="keep-show">{{ __('Action') }}</th>
    </tr></thead><tbody></tbody></table>
</div></div></div></div>

<div class="modal fade zModalTwo style-modal" id="add-style-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content">@include('admin.garments.styles.form', ['style' => null])</div></div></div>
<div class="modal fade zModalTwo style-modal" id="edit-style-modal" aria-hidden="true" tabindex="-1"><div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content zModalTwo-content"></div></div></div>
@endsection

@push('style')
<style>
    #add-style-modal .modal-dialog, #edit-style-modal .modal-dialog { max-width: 760px; }
    .style-modal .zModalTwo-body { padding: 28px 30px 24px; }
    .style-form-section { border: 1px solid #eee8e5; border-radius: 8px; padding: 18px; }
    .style-form-section-title { color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 15px; }
    .style-form-section-title i { color: #ff4f02; margin-right: 8px; }
    .style-modal .form-group { margin-bottom: 0; }
    .style-modal .form-control { min-height: 44px; }
    .style-modal textarea.form-control { min-height: 88px; resize: vertical; }
    .style-modal-footer { display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee8e5; margin-top: 22px; padding-top: 18px; }
</style>
@endpush

@push('script')<script src="{{ asset('admin/js/garment-styles.js') }}"></script>@endpush
