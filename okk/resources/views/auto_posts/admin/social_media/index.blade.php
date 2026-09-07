@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Social Media Accounts') }}
@endpush

@section('content')

<div class="section-title">
    <h2 class="title">{{ __('All Accounts') }}</h2>
    <a href="#" class="primary-btn d-none" id="connectAccountBtnMain">+
        {{ __('Connect Account') }}</a>
</div>

<nav class="primry-tabs mb-20">
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <button class="nav-link active" id="nav-AllPlatforms-tab" data-bs-toggle="tab"
            data-bs-target="#nav-AllPlatforms" type="button" role="tab" aria-controls="nav-AllPlatforms"
            aria-selected="true" data-platform="all">{{ __('All Platforms') }}</button>
        @foreach($platforms as $platform)
        <button class="nav-link platform-tab-btn" id="nav-{{ $platform }}-tab" data-bs-toggle="tab"
            data-bs-target="#nav-{{ $platform }}" type="button" role="tab" aria-controls="nav-{{ $platform }}"
            aria-selected="false" data-platform="{{ $platform }}">
            @include('auto_posts.admin.social_media.configs.partials.platform_icon_svg', ['platformKey' => $platform])
            {{ ucfirst($platform) }}
        </button>
        @endforeach
    </div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-AllPlatforms" role="tabpanel" aria-labelledby="nav-AllPlatforms-tab"
        tabindex="0">
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
                    <input type="text" class="search-input" id="searchData"
                        placeholder="{{ __('Search accounts...') }}" />
                </div>
                <table class="display social-accounts-table primary-table" id="socialMediaDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">#SL</th>
                            <th>{{ __('Account Name') }}</th>
                            <th>{{ __('Account Type') }}</th>
                            <th class="keep-show">{{ __('Platform') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Stats') }}</th>
                            <th>{{ __('Connected') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accounts as $index => $account)
                        <tr>
                            <td>
                                <label class="form-check-label sl-check-label">
                                    <input type="checkbox" class="form-check-input">
                                    {{ $accounts->firstItem() + $index }}
                                </label>
                            </td>
                            <td>{{ $account->username ?? $account->email ?? 'N/A' }}</td>
                            <td>
                                @if($account->isPageConnected())
                                <span class="status active">{{ __('Page') }}</span>
                                @elseif($account->isGroupConnected())
                                <span class="status active">{{ __('Group') }}</span>
                                @else
                                <span class="status active">{{ __('Profile') }}</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($account->platform) }}</td>
                            <td>
                                <span class="status {{ $account->is_active ? 'active' : 'inactive' }}">
                                    {{ $account->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.3332 7.33333C10.3332 6.04467 9.2885 5 7.99984 5C6.71117 5 5.6665 6.04467 5.6665 7.33333C5.6665 8.622 6.71117 9.66667 7.99984 9.66667C9.2885 9.66667 10.3332 8.622 10.3332 7.33333Z"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M10.3218 7.5666C10.5365 7.63167 10.7642 7.66667 11 7.66667C12.2887 7.66667 13.3334 6.622 13.3334 5.33333C13.3334 4.04467 12.2887 3 11 3C9.79009 3 8.79522 3.92093 8.67822 5.10009"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M7.32164 5.10009C7.20464 3.92093 6.20978 3 4.99984 3C3.71117 3 2.6665 4.04467 2.6665 5.33333C2.6665 6.622 3.71117 7.66667 4.99984 7.66667C5.23571 7.66667 5.4634 7.63167 5.67802 7.5666"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M14.6667 11.0001C14.6667 9.15915 13.0251 7.66675 11 7.66675"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11.6668 13.0001C11.6668 11.1591 10.0252 9.66675 8.00016 9.66675C5.97512 9.66675 4.3335 11.1591 4.3335 13.0001"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M5.00016 7.66675C2.97512 7.66675 1.3335 9.15915 1.3335 11.0001"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ $account->scheduled_posts_count ?? 0 }}
                                </div>
                                <span>{{ __('Post') }}:{{ $account->scheduled_posts_count ?? 0 }}</span>
                                @if(isset($account->engagement) && ($account->engagement['likes'] > 0 ||
                                $account->engagement['comments'] > 0 || $account->engagement['shares'] > 0))
                                <div class="mt-2">
                                    <span class="me-2" title="{{ __('Likes') }}">
                                        <i class="fa-solid fa-heart text-danger"></i>
                                        {{ $account->engagement['likes'] }}
                                    </span>
                                    <span class="me-2" title="{{ __('Comments') }}">
                                        <i class="fa-solid fa-comment text-primary"></i>
                                        {{ $account->engagement['comments'] }}
                                    </span>
                                    <span title="{{ __('Shares') }}">
                                        <i class="fa-solid fa-share text-success"></i>
                                        {{ $account->engagement['shares'] }}
                                    </span>
                                </div>
                                @endif
                            </td>
                            <td>{{ $account->created_at->format('m/d/Y') }}</td>
                            <td>
                                <div class="dropdown options-area">
                                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item edit-account-btn" href="#"
                                                data-account-id="{{ $account->id }}"
                                                data-platform="{{ $account->platform }}"
                                                data-edit-data-url="{{ route('admin.social.account.edit-data', $account->id) }}">
                                                {{ __('Edit') }}
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item delete-item" href="#"
                                                data-route="{{ route('admin.social.account.destroy', $account) }}">
                                                {{ __('Delete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted">{{ __('No social media accounts found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($platforms as $platform)
    <div class="tab-pane fade" id="nav-{{ $platform }}" role="tabpanel" aria-labelledby="nav-{{ $platform }}-tab"
        tabindex="0">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap">
                    <label class="icon" for="searchData{{ ucfirst($platform) }}">
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
                    <input type="text" class="search-input" id="searchData{{ ucfirst($platform) }}"
                        placeholder="{{ __('Search accounts...') }}" />
                </div>
                <table class="display social-accounts-table primary-table platform-accounts-table"
                    id="socialMediaDataTable-{{ $platform }}" data-platform="{{ $platform }}">
                    <thead>
                        <tr>
                            <th class="keep-show">#SL</th>
                            <th>{{ __('Account Name') }}</th>
                            <th>{{ __('Account Type') }}</th>
                            <th class="keep-show">{{ __('Platform') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Stats') }}</th>
                            <th>{{ __('Connected') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $platformAccounts = $accounts->where('platform', $platform);
                        @endphp
                        @forelse($platformAccounts as $index => $account)
                        <tr>
                            <td>
                                <label class="form-check-label sl-check-label">
                                    <input type="checkbox" class="form-check-input">
                                    {{ $index + 1 }}
                                </label>
                            </td>
                            <td>{{ $account->username ?? $account->email ?? 'N/A' }}</td>
                            <td>
                                @if($account->isPageConnected())
                                <span class="status active">{{ __('Page') }}</span>
                                @elseif($account->isGroupConnected())
                                <span class="status active">{{ __('Group') }}</span>
                                @else
                                <span class="status active">{{ __('Profile') }}</span>
                                @endif
                            </td>
                            <td>{{ ucfirst($account->platform) }}</td>
                            <td>
                                <span class="status {{ $account->is_active ? 'active' : 'inactive' }}">
                                    {{ $account->is_active ? __('Active') : __('Inactive') }}
                                </span>
                            </td>
                            <td>
                                <div>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.3332 7.33333C10.3332 6.04467 9.2885 5 7.99984 5C6.71117 5 5.6665 6.04467 5.6665 7.33333C5.6665 8.622 6.71117 9.66667 7.99984 9.66667C9.2885 9.66667 10.3332 8.622 10.3332 7.33333Z"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M10.3218 7.5666C10.5365 7.63167 10.7642 7.66667 11 7.66667C12.2887 7.66667 13.3334 6.622 13.3334 5.33333C13.3334 4.04467 12.2887 3 11 3C9.79009 3 8.79522 3.92093 8.67822 5.10009"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M7.32164 5.10009C7.20464 3.92093 6.20978 3 4.99984 3C3.71117 3 2.6665 4.04467 2.6665 5.33333C2.6665 6.622 3.71117 7.66667 4.99984 7.66667C5.23571 7.66667 5.4634 7.63167 5.67802 7.5666"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M14.6667 11.0001C14.6667 9.15915 13.0251 7.66675 11 7.66675"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M11.6668 13.0001C11.6668 11.1591 10.0252 9.66675 8.00016 9.66675C5.97512 9.66675 4.3335 11.1591 4.3335 13.0001"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M5.00016 7.66675C2.97512 7.66675 1.3335 9.15915 1.3335 11.0001"
                                            stroke="black" stroke-width="1.3" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ $account->scheduled_posts_count ?? 0 }}
                                </div>
                                <span>{{ __('Post') }}:{{ $account->scheduled_posts_count ?? 0 }}</span>
                                @if(isset($account->engagement) && ($account->engagement['likes'] > 0 ||
                                $account->engagement['comments'] > 0 || $account->engagement['shares'] > 0))
                                <div class="mt-2">
                                    <span class="me-2" title="{{ __('Likes') }}">
                                        <i class="fa-solid fa-heart text-danger"></i>
                                        {{ $account->engagement['likes'] }}
                                    </span>
                                    <span class="me-2" title="{{ __('Comments') }}">
                                        <i class="fa-solid fa-comment text-primary"></i>
                                        {{ $account->engagement['comments'] }}
                                    </span>
                                    <span title="{{ __('Shares') }}">
                                        <i class="fa-solid fa-share text-success"></i>
                                        {{ $account->engagement['shares'] }}
                                    </span>
                                </div>
                                @endif
                            </td>
                            <td>{{ $account->created_at->format('m/d/Y') }}</td>
                            <td>
                                <div class="dropdown options-area">
                                    <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">

                                        <li>
                                            <a class="dropdown-item edit-account-btn" href="#"
                                                data-account-id="{{ $account->id }}"
                                                data-platform="{{ $account->platform }}"
                                                data-edit-data-url="{{ route('admin.social.account.edit-data', $account->id) }}">
                                                {{ __('Edit') }}
                                            </a>
                                        </li>

                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>

                                        <li>
                                            <a class="dropdown-item delete-item" href="#"
                                                data-route="{{ route('admin.social.account.destroy', $account) }}">
                                                {{ __('Delete') }}
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted"></td>
                            <td class="text-center text-muted">{{ __('No accounts found for this platform.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endforeach
</div>



<!-- Delete Confirmation Modal -->
<div class="modal fade primary-modal" id="deleteModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Confirm Deletion') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ __('Are you sure you want to delete this social media account? This action cannot be undone.') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="primary-btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="primary-btn" id="confirmDeleteBtn">{{ __('Delete') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Connect Account Modal - Platform Selection -->
<div class="modal fade primary-modal" id="ConnectAccountModal" tabindex="-1" aria-labelledby="ConnectAccountModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="ConnectAccountModalLabel">{{ __('Connect Social Accounts') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="primary-form">
                    <form action="#">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Choose a platform to connect') }}</label>
                                    <div class="platform-list">
                                        @php
                                        $firstPlatform = $platforms[0] ?? null;
                                        @endphp
                                        @foreach($platforms as $platform)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input platform-select-radio" type="radio"
                                                name="platform" id="connect-platform-{{ $platform }}"
                                                value="{{ $platform }}"
                                                {{ $platform === $firstPlatform ? 'checked' : '' }}>
                                            <label class="form-check-label" for="connect-platform-{{ $platform }}">
                                                @include('auto_posts.admin.social_media.configs.partials.platform_icon_svg',
                                                ['platformKey' => $platform])
                                                {{ ucfirst($platform) }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tip-area">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.19028 10.5613H6.80781C5.57137 10.5613 4.95314 10.5613 4.68963 10.1537C4.42611 9.74608 4.6772 9.17817 5.17936 8.04236L6.68906 4.62769C7.14564 3.595 7.37393 3.07865 7.8168 2.78932C8.25968 2.5 8.82175 2.5 9.946 2.5H11.6872C13.0528 2.5 13.7357 2.5 13.9932 2.94613C14.2507 3.39225 13.912 3.98823 13.2344 5.18019L12.3412 6.75157C12.0043 7.34412 11.8359 7.64041 11.8382 7.88293C11.8413 8.19812 12.0089 8.4885 12.2797 8.6475C12.488 8.76992 12.8274 8.76992 13.5063 8.76992C14.3646 8.76992 14.7937 8.76992 15.0172 8.9185C15.3076 9.1115 15.4596 9.45683 15.4063 9.80267C15.3653 10.0688 15.0767 10.388 14.4993 11.0264L9.88675 16.1269C8.98075 17.1287 8.52775 17.6297 8.22355 17.4712C7.91935 17.3126 8.06544 16.6518 8.35758 15.3302L8.92991 12.7413C9.15233 11.735 9.26358 11.2318 8.99608 10.8966C8.72858 10.5613 8.2158 10.5613 7.19028 10.5613Z"
                            stroke="#FFC402" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                    <div class="tip-info">
                        <h4>{{ __('Pro Tip') }}</h4>
                        <p>{{ __('Add multiple accounts from the same platform for different brands or profiles.') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="primary-btn btn-secondary"
                    data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="primary-btn" id="connectAccountBtn">{{ __('Continue') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Connect (Add) Modal section start -->
@foreach($platforms as $platform)
@include('auto_posts.admin.social_media.modals.' . $platform)
@endforeach
<!-- Connect Modal section end -->

<!-- Edit Modal section start -->
@foreach($platforms as $platform)
@include('auto_posts.admin.social_media.modals._edit_modal', ['platform' => $platform])
@endforeach
<!-- Edit Modal section end -->
@endsection

@push('script')
<script src="{{ asset('admin/js/social-media.js') }}?v={{ time() }}"></script>
@endpush