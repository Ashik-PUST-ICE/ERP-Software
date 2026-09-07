@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Manage Campaigns') }}
@endpush

@section('content')
<div class="content-wrapper">
    <div class="section-title">
        <h2 class="title">{{ __('Campaigns') }}</h2>
        <a href="{{ route('admin.campaign.create') }}" class="primary-btn">+ {{ __('Campaign Create') }}</a>
    </div>
    <div class="section-wrap">
        <div class="table-waraper">
            <div class="search-input-wrap">
                <label class="icon" for="searchData">
                    <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                            stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                            stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </label>
                <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search By Title...') }}" />
            </div>
            <table class="display search-datatable primary-table" id="campaignTable"
                data-url="{{ route('admin.campaign.datatable') }}">
                <thead>
                    <tr>
                        <th class="keep-show">{{ __('Campaign Name') }}</th>
                        <th class="keep-show">{{ __('Status') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th>{{ __('End Date') }}</th>
                        <th>{{ __('Platform') }}</th>
                        <th class="keep-show">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('admin/js/campaign.js') }}"></script>
@endpush