@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Campaign Create') }}
@endpush

@section('content')
<div class="content-wrapper">
    <div class="section-title">
        <h2 class="title">{{ __('Campaign Create') }}</h2>
    </div>
    <div class="section-wrap">
        <div class="form-wrapper">
            <div class="steps">
                <div class="step active">
                    <span>1</span>
                    <p>{{ __('Campaign Details') }}</p>
                </div>
                <div class="line"></div>
                <div class="step">
                    <span>2</span>
                    <p>{{ __('Account Selection') }}</p>
                </div>
                <div class="line"></div>
                <div class="step">
                    <span>3</span>
                    <p>{{ __('Review & Launch') }}</p>
                </div>
            </div>
            <form id="campaignForm" action="{{ route('admin.campaign.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="gallery_image_ids" id="selected_gallery_image_ids">
                <input type="hidden" name="gallery_video_ids" id="selected_gallery_video_ids">
                <input type="hidden" name="account_ids" id="selected_account_ids">
                <input type="hidden" name="platform" id="selected_platform">
                <input type="hidden" name="post_type" id="campaign_post_type" value="Feed">
                <input type="hidden" name="status" id="campaign_status" value="pending">
                <input type="hidden" name="scheduled_time" id="campaign_scheduled_time_field" value="">

                <!-- Step 1: Campaign Details -->
                <div class="form-step active">
                    <div class="campaign-details">
                        <div class="form-step-top text-center">
                            <h3>{{ __('Campaign Details') }}</h3>
                            <p>{{ __('Start by adding the basic details for your campaign.') }}</p>
                        </div>
                        <div class="primary-form">
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="CampaignName" class="form-label">{{ __('Campaign Name') }}<span
                                                class="required">*</span></label>
                                        <input type="text" class="form-control" id="CampaignName" name="name"
                                            placeholder="{{ __('Demo') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="StartDate" class="form-label">{{ __('Start Date') }}<span
                                                class="required">*</span></label>
                                        <input type="date" class="form-control" id="StartDate" name="start_date"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="EndDate" class="form-label">{{ __('End Date') }}<span
                                                class="required">*</span></label>
                                        <input type="date" class="form-control" id="EndDate" name="end_date" required>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="Description" class="form-label">{{ __('Description') }}<span
                                                class="required">*</span></label>
                                        <textarea id="Description" name="description" class="summernote" rows="5"
                                            placeholder="{{ __('Enter campaign description...') }}"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="actions">
                            <button type="button" class="primary-btn btn-next">{{ __('Next') }}</button>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Account & Content Setup -->
                <div class="form-step">
                    <div class="form-step-top text-center">
                        <h3>{{ __('Account & Content Setup') }}</h3>
                        <p>{{ __('Pick your platforms and craft engaging content for your campaign.') }}</p>
                    </div>
                    @include('auto_posts.admin.campaign.partials.post_editor')
                    <div class="actions">
                        <button type="button" class="btn-secondary primary-btn btn-back">{{ __('Back') }}</button>
                        <button type="button" class="primary-btn btn-next">{{ __('Next') }}</button>
                    </div>
                </div>

                <!-- Step 3: Review & Launch -->
                <div class="form-step">
                    <div class="form-step-top text-center">
                        <h3>{{ __('Review & Launch') }}</h3>
                        <p>{{ __('Review your campaign details before launching') }}</p>
                    </div>
                    <div class="review-launch-area">
                        <div class="row gy-4">
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="single-review-campaing">
                                    <h3>{{ __('Campaign Details') }}</h3>
                                    <p id="review-name">-</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="single-review-campaing">
                                    <h3>{{ __('Campaign Duration') }}</h3>
                                    <p id="review-start-date">-</p>
                                    <p id="review-end-date">-</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="single-review-campaing">
                                    <h3>{{ __('Selected Accounts') }}</h3>
                                    <div class="account-area" id="review-accounts">
                                        <span>-</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="single-review-campaing">
                                    <h3>{{ __('Platform') }}</h3>
                                    <p id="review-platform">-</p>
                                </div>
                            </div>
                        </div>
                        <div class="ready-launch text-center mt-4">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M44.6829 17.0835C44.7918 19.2713 44.7918 21.8739 44.7918 25.0002C44.7918 34.33 44.7918 38.995 41.8935 41.8935C38.995 44.7918 34.33 44.7918 25.0002 44.7918C15.6703 44.7918 11.0053 44.7918 8.10691 41.8935C5.2085 38.995 5.2085 34.33 5.2085 25.0002C5.2085 15.6703 5.2085 11.0053 8.10691 8.10691C11.0053 5.2085 15.6703 5.2085 25.0002 5.2085C27.2333 5.2085 29.1993 5.2085 30.9377 5.24825"
                                    stroke="#16A34A" stroke-width="3" stroke-linecap="round" />
                                <path
                                    d="M16.6665 23.9585C16.6665 23.9585 19.7915 23.9585 23.9582 31.2502C23.9582 31.2502 34.4973 12.1529 44.7915 8.3335"
                                    stroke="#16A34A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <h3>{{ __('Ready to Launch!') }}</h3>
                            <p>{{ __('Your campaign is configured and ready to go. Click "Launch Campaign" to start.') }}
                            </p>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="button" class="btn-secondary primary-btn btn-back">{{ __('Back') }}</button>
                        <button type="submit" name="publish_now" value="1" class="primary-btn"
                            id="campaignPostBtn">{{ __('Post') }}</button>
                        <!-- <button type="submit" name="publish_now" value="0" class="primary-btn btn-secondary" id="campaignScheduleBtn">{{ __('Schedule') }}</button> -->
                        {{-- Schedule date picker --}}
                        <div class="schedule-wrapper" style="position: relative; display: inline-block;">
                            <a href="javascript:void(0)" class="datepick" id="campaignDatePickerTrigger"
                                style="display: inline-flex; align-items: center; padding: 10px 15px; border: 1px solid #dee2e6; border-radius: 4px; text-decoration: none; background: #fff; margin-left: 10px;">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.78598 6.45493L1.90338 6.34032C3.25284 2.77858 7.12725 0.749938 10.9047 1.75856C14.928 2.83283 17.3178 6.94581 16.2424 10.9451C15.167 14.9445 11.0337 17.3157 7.0104 16.2415C4.02314 15.4438 1.93644 12.971 1.5 10.1133"
                                        stroke="#0D0D0D" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M9 6V9L10.5 10.5" stroke="#0D0D0D" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <div id="campaign-custom-picker-box" class="picker-card shadow-sm"
                                style="display:none; position: absolute; top: 100%; right: 0; z-index: 1000; margin-top: 5px;">
                                <div id="campaign-inline-calendar"></div>
                                <div class="time-section">
                                    <label>Time</label>
                                    <div class="time-input-wrapper">
                                        <input type="time" id="campaign-custom-time" value="">
                                    </div>
                                </div>
                                <div class="picker-footer">
                                    <div class="scheduled-info">
                                        <p>Scheduled for: <strong id="campaign-display-date">Select a date</strong></p>
                                        <p class="small text-muted mb-0 mt-1">
                                            {{ __('Times are in :timezone', ['timezone' => getAppTimeZone()]) }}</p>
                                    </div>
                                    <div class="btn-group gap-2">
                                        <button class="primary-btn btn-secondary"
                                            id="campaign-closePicker">Cancel</button>
                                        <button class="primary-btn" id="campaign-confirm-schedule">Schedule</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

@include('auto_posts.admin.template.modals')

<div id="campaign-config" data-ai-generate-url="{{ route('admin.ai.generate-content.submit') }}"
    data-ai-enabled="{{ ($ai_enabled ?? false) ? '1' : '0' }}" data-ai-upgrade-message="{{ $ai_upgrade_message ?? '' }}"
    data-allowed-providers='@json($allowedProviders ?? [])'
    data-post-limit-allowed="{{ ($postLimitInfo['allowed'] ?? true) ? '1' : '0' }}"
    data-post-limit-message="{{ $postLimitInfo['message'] ?? '' }}" style="display:none;"></div>

<!-- GalleryModal -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="GalleryModal" aria-labelledby="GalleryModalLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="GalleryModalLabel">{{ __('Gallery') }}</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="search-input-wrap mb-4">
            <label class="icon" for="gallerySearch">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </label>
            <input type="text" class="search-input" id="gallerySearch" placeholder="{{ __('Search By Title...') }}">
        </div>

        <ul class="nav nav-tabs post-tabs mb-4 mt-4" id="GalleryTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="All-tab" data-bs-toggle="tab" data-bs-target="#All-tab-pane"
                    type="button" role="tab">All</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="Images-tab" data-bs-toggle="tab" data-bs-target="#Images-tab-pane"
                    type="button" role="tab">Images</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="Videos-tab" data-bs-toggle="tab" data-bs-target="#Videos-tab-pane"
                    type="button" role="tab">Videos</button>
            </li>
        </ul>

        <div class="tab-content" id="GalleryTabContent">
            <div class="tab-pane fade show active" id="All-tab-pane" role="tabpanel" tabindex="0">
                <div class="row gy-4">
                    @forelse($galleries as $gallery)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image gallery-item-select" data-id="{{ $gallery->id }}"
                            data-url="{{ asset('storage/' . $gallery->file_path) }}" data-type="image"
                            style="cursor: pointer; position: relative;" onclick="toggleMediaSelection(this)">
                            <div class="selection-checkbox"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="select-icon" style="display: none;">
                                    <circle cx="12" cy="12" r="11" fill="#4CAF50" stroke="white" stroke-width="2" />
                                    <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="image-wrapper">
                                <img class="logo" src="{{ asset('storage/' . $gallery->file_path) }}"
                                    alt="{{ $gallery->title ?? $gallery->file_name }}" style="pointer-events: none;">
                            </div>
                            <div class="image-info">
                                <h5 class="image-format">{{ $gallery->file_name }}</h5>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                    @forelse($videos as $video)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image gallery-item-select" data-id="{{ $video->id }}"
                            data-url="{{ asset('storage/' . $video->file_path) }}" data-type="video"
                            style="cursor: pointer; position: relative;" onclick="toggleMediaSelection(this)">
                            <div class="selection-checkbox"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="select-icon" style="display: none;">
                                    <circle cx="12" cy="12" r="11" fill="#4CAF50" stroke="white" stroke-width="2" />
                                    <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="image-wrapper"
                                style="background: #000; display: flex; align-items: center; justify-content: center; min-height: 120px;"
                                onmouseover="this.querySelector('video').play()"
                                onmouseout="this.querySelector('video').pause();this.querySelector('video').currentTime=0;">
                                <video preload="none" class="logo"
                                    style="width: 100%; height: 120px; object-fit: cover; pointer-events: none;">
                                    <source src="{{ asset('storage/' . $video->file_path) }}"
                                        type="{{ $video->file_type }}">
                                </video>
                            </div>
                            <div class="image-info">
                                <h5 class="image-format">{{ $video->file_name }}</h5>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="tab-pane fade" id="Images-tab-pane" role="tabpanel" tabindex="0">
                <div class="row gy-4">
                    @forelse($galleries as $gallery)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image gallery-item-select" data-id="{{ $gallery->id }}"
                            data-url="{{ asset('storage/' . $gallery->file_path) }}" data-type="image"
                            style="cursor: pointer; position: relative;" onclick="toggleMediaSelection(this)">
                            <div class="selection-checkbox"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="select-icon" style="display: none;">
                                    <circle cx="12" cy="12" r="11" fill="#4CAF50" stroke="white" stroke-width="2" />
                                    <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="image-wrapper">
                                <img class="logo" src="{{ asset('storage/' . $gallery->file_path) }}"
                                    alt="{{ $gallery->title ?? $gallery->file_name }}" style="pointer-events: none;">
                            </div>
                            <div class="image-info">
                                <h5 class="image-format">{{ $gallery->file_name }}</h5>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p>{{ __('No images found') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
            <div class="tab-pane fade" id="Videos-tab-pane" role="tabpanel" tabindex="0">
                <div class="row gy-4">
                    @forelse($videos as $video)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image gallery-item-select" data-id="{{ $video->id }}"
                            data-url="{{ asset('storage/' . $video->file_path) }}" data-type="video"
                            style="cursor: pointer; position: relative;" onclick="toggleMediaSelection(this)">
                            <div class="selection-checkbox"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="select-icon" style="display: none;">
                                    <circle cx="12" cy="12" r="11" fill="#4CAF50" stroke="white" stroke-width="2" />
                                    <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="image-wrapper"
                                style="background: #000; display: flex; align-items: center; justify-content: center; min-height: 120px;"
                                onmouseover="this.querySelector('video').play()"
                                onmouseout="this.querySelector('video').pause();this.querySelector('video').currentTime=0;">
                                <video preload="none" class="logo"
                                    style="width: 100%; height: 120px; object-fit: cover; pointer-events: none;">
                                    <source src="{{ asset('storage/' . $video->file_path) }}"
                                        type="{{ $video->file_type }}">
                                </video>
                            </div>
                            <div class="image-info">
                                <h5 class="image-format">{{ $video->file_name }}</h5>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p>{{ __('No videos found') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas-footer" style="padding: 15px; border-top: 1px solid #dee2e6; background: #fff;">
        <button type="button" class="primary-btn w-100" id="applyGallerySelection">
            {{ __('Apply') }} <span class="badge bg-light text-dark ms-2" id="selectionCount"
                style="display: none;">0</span>
        </button>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('assets/js/hashtag.js') }}"></script>
<script src="{{ asset('admin/js/template.js') }}?v={{ time() }}"></script>
<script src="{{ asset('admin/js/campaign.js') }}?v={{ time() }}"></script>
<script type="module" src="{{ asset('admin/js/emoji-picker.js') }}?v={{ time() }}"></script>
<script src="{{ asset('admin/js/date-picker.js') }}?v={{ time() }}"></script>
@endpush