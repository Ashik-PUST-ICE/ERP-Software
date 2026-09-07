<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Post Management Section Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

@php
$cardCount = 1;
for ($i = 2; $i <= 4; $i++) { if (trim((string) getOption('landing_post_card' . $i . '_title' , '' )) !=='' ) {
    $cardCount=$i; } } @endphp <div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.frontend.landing-page.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal" id="post-management-config-form">
        @csrf
        <input type="hidden" name="landing_post_card_count" id="landing_post_card_count" value="{{ $cardCount }}">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Section Title') }}</label>
                    <input type="text" name="landing_post_management_title" class="form-control"
                        value="{{ getOption('landing_post_management_title', 'Easy Post Creation With AI & Manage Posts.') }}"
                        placeholder="{{ __('Enter post management section title') }}">
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">{{ __('Post Management Cards') }}</h5>
                    <button type="button" class="primary-btn" id="post-management-add-more-btn"
                        onclick="postManagementAddMore()">
                        {{ __('Add More') }}
                    </button>
                </div>
            </div>

            <div class="col-12" id="post-management-cards-wrapper">
                @for($n = 1; $n <= 4; $n++) <div class="post-management-card-block border rounded p-3 mb-3"
                    data-card-num="{{ $n }}" style="{{ $n > $cardCount ? 'display:none' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">{{ __('Card') }} #{{ $n }}</h6>
                        @if($n > 1)
                        <button type="button" class="btn btn-sm btn-danger post-management-remove-btn"
                            onclick="postManagementRemoveCard({{ $n }})"
                            style="{{ $n !== $cardCount ? 'display:none' : '' }}">&times;</button>
                        @endif
                    </div>
                    <div class="row gy-2">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Title') }} ({{ __('Card') }} {{ $n }})</label>
                                <input type="text" name="landing_post_card{{ $n }}_title" class="form-control"
                                    value="{{ getOption('landing_post_card' . $n . '_title') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Description') }} ({{ __('Card') }} {{ $n }})</label>
                                <input type="text" name="landing_post_card{{ $n }}_description" class="form-control"
                                    value="{{ getOption('landing_post_card' . $n . '_description') }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label d-block">{{ __('Image') }} ({{ __('Card') }} {{ $n }})</label>
                                <div class="zImage-upload-details mw-100">
                                    <div class="upload-img-box upload-image-box-new">
                                        <img
                                            src="{{ getOption('landing_post_card' . $n . '_image') ? getSettingImage('landing_post_card' . $n . '_image') : '' }}" />
                                        <input type="file" name="landing_post_card{{ $n }}_image" accept="image/*"
                                            onchange="previewFile(this)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            @endfor
        </div>

        <div class="col-lg-12 mt-3 pt-3 border-top">
            <h6 class="mb-3">{{ __('Manage All Posts') }}</h6>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label">{{ __('Title') }}</label>
                <input type="text" name="landing_post_manage_all_title" class="form-control"
                    value="{{ getOption('landing_post_manage_all_title') }}"
                    placeholder="{{ __('Manage All Posts title') }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label class="form-label">{{ __('Description') }}</label>
                <input type="text" name="landing_post_manage_all_description" class="form-control"
                    value="{{ getOption('landing_post_manage_all_description') }}"
                    placeholder="{{ __('Manage All Posts description') }}">
            </div>
        </div>
        <div class="col-lg-12">
            <div class="form-group">
                <label class="form-label d-block">{{ __('Manage All Posts Image') }}</label>
                <div class="zImage-upload-details mw-100">
                    <div class="upload-img-box">
                        <img
                            src="{{ getOption('landing_post_manage_all_image') ? getSettingImage('landing_post_manage_all_image') : '' }}" />
                        <input type="file" name="landing_post_manage_all_image" accept="image/*"
                            onchange="previewFile(this)" />
                    </div>
                </div>
            </div>
        </div>
        </div>

        <!-- Footer Buttons -->
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="primary-btn">{{ __('Save') }}</button>
        </div>
    </form>
    </div>

    <script src="{{ asset('super_admin/js/landing/post-management-config.js') }}"></script>