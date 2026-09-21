@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Assign Merchandiser') }}
    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search Orders...') }}" />
                </div>
                <input type="hidden" id="merchandiser-orders-data-route" value="{{ route('admin.garments.merchandiser.management.data') }}?section=orders">
                <table class="display primary-table dataTable dtr-inline" id="merchandiserOrderDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('SL') }}</th>
                            <th>{{ __('Order #') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Style') }}</th>
                            <th>{{ __('Primary Merchandiser') }}</th>
                            <th>{{ __('Assigned Team') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content">
            <form class="ajax reset" action="{{ route('admin.garments.merchandiser.assign') }}" method="post"
                data-handler="commonResponseForModal">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Assign Merchandiser') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Select Garment Order') }} <span class="required">*</span></label>
                                    <select name="order_id" class="form-control" required>
                                        <option value="">{{ __('Select order...') }}</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Lead / Primary Merchandiser') }}</label>
                                    <select name="primary_user_id" class="form-control">
                                        <option value="">{{ __('Select lead (optional)...') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group merchandiser-team-wrap">
                                    <label class="form-label">{{ __('Assign Merchandisers (Team)') }} <span class="required">*</span></label>
                                    <select class="form-control multiple-basic-single" multiple="multiple" name="user_ids[]" required>
                                        <option value=""></option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>
<!-- PART2 -->
@endsection

@push('style')
<style>
#add-modal .select2-container.select2-container--open,
#edit-modal .select2-container.select2-container--open {
    z-index: 9999;
}
#add-modal .modal-body,
#edit-modal .modal-body {
    overflow: visible;
}
#add-modal .modal-content,
#edit-modal .modal-content {
    overflow: visible;
}
</style>
@endpush

@push('script')
<script src="{{ asset('admin/js/garment-merchandiser-orders.js') }}"></script>
@endpush
