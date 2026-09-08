@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Add Buyer') }}
    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData"><i class="fa-solid fa-magnifying-glass"></i></label>
                    <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search Buyers...') }}" />
                </div>
                <input type="hidden" id="buyer-data-route" value="{{ route('admin.garments.buyers.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="buyerDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('SL') }}</th>
                            <th>{{ __('Buyer Code') }}</th>
                            <th>{{ __('Company') }}</th>
                            <th>{{ __('Contact Person') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo buyer-modal" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content">
            @include('admin.garments.buyers.form', ['buyer' => null])
        </div>
    </div>
</div>

<div class="modal fade zModalTwo buyer-modal" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content"></div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/garment-buyers.js') }}"></script>
@endpush

@push('style')
<style>
    #add-modal.buyer-modal .modal-dialog,
    #edit-modal.buyer-modal .modal-dialog { max-width: 960px; height: calc(100% - 2rem); min-height: 0; }
    #add-modal.buyer-modal .modal-content,
    #edit-modal.buyer-modal .modal-content { height: 100%; max-height: 100%; min-height: 0; overflow: hidden; }
    #add-modal.buyer-modal form,
    #edit-modal.buyer-modal form { display: flex; flex-direction: column; height: 100%; min-height: 0; }
    #add-modal.buyer-modal .modal-body,
    #edit-modal.buyer-modal .modal-body { display: block; flex: 1 1 auto; min-height: 0; max-height: none; overflow-y: auto; overflow-x: hidden; }
    .buyer-modal .zModalTwo-body { padding: 28px 30px 24px; }
    .buyer-modal-header { border-bottom: 1px solid #eee8e5; padding-bottom: 18px; }
    .buyer-modal-icon { width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; background: #fff1eb; color: #ff4f02; font-size: 18px; }
    .buyer-modal-subtitle { color: #837775; font-size: 13px; margin: 2px 0 0; }
    .buyer-form-section { border: 1px solid #eee8e5; border-radius: 8px; padding: 18px; background: #fff; }
    .buyer-form-section + .buyer-form-section { margin-top: 16px; }
    .buyer-form-section-title { display: flex; align-items: center; gap: 8px; color: #1b1c17; font-size: 15px; font-weight: 600; margin: 0 0 15px; }
    .buyer-form-section-title i { color: #ff4f02; font-size: 14px; }
    .buyer-modal .form-group { margin-bottom: 0; }
    .buyer-modal .form-control { min-height: 44px; }
    .buyer-modal textarea.form-control { min-height: 88px; resize: vertical; }
    .buyer-modal-footer { display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid #eee8e5; margin-top: 22px; padding-top: 18px; }
    @media (max-width: 575px) { .buyer-modal .zModalTwo-body { padding: 22px 18px 18px; } .buyer-form-section { padding: 14px; } }
</style>
@endpush
