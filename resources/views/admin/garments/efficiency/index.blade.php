@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Add Efficiency Record') }}
    </button>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="efficiencySearchData"><i class="fa-solid fa-magnifying-glass"></i></label>
                    <input class="search-input" id="efficiencySearchData" placeholder="{{ __('Search operator, machine or order...') }}">
                </div>
                <input type="hidden" id="garment-efficiency-data-route" value="{{ route('admin.garments.efficiency.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="garmentEfficiencyDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('SL') }}</th>
                            <th>{{ __('Operator') }}</th>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Machine') }}</th>
                            <th>{{ __('Output / Target') }}</th>
                            <th>{{ __('Efficiency') }}</th>
                            <th>{{ __('Date') }}</th>
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
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            @include('admin.garments.efficiency.form', ['efficiency' => null, 'employees' => $employees, 'orders' => $orders])
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade zModalTwo" id="edit-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('admin/js/garment-efficiency.js') }}"></script>
@endpush
