@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-2">
    <h2 class="title">{{ __($title) }}</h2>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="#6E5858" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search materials...') }}" />
                    <input type="hidden" id="scanner-data-route" value="{{ route('admin.garments.materials.scanner') }}">
                </div>

                <div id="material-scanner-result" class="mb-3"></div>

                <table class="display primary-table dataTable dtr-inline" id="scannerDataTable">
                    <thead>
                        <tr>
                            <th>{{ __('Item Code') }}</th>
                            <th>{{ __('Item Name') }}</th>
                            <th>{{ __('Barcode') }}</th>
                            <th>{{ __('Unit') }}</th>
                            <th>{{ __('Current Stock') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/garment-scanner.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush

