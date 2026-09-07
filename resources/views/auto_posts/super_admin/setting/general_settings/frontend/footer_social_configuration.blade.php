<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Footer Social Media Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

@php
$socialCount = 1;
for ($i = 2; $i <= 4; $i++) { 
    if (trim((string) getOption('social_media_' . $i . '_url', '' )) !=='' ) {
        $socialCount = $i; 
    } 
}
@endphp

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}" method="POST"
        data-handler="commonResponseForModal">
        @csrf
        <input type="hidden" name="social_media_count" id="social_media_count" value="{{ $socialCount }}">
        <div class="row gy-4">
            <!-- Social Media Section -->
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">{{ __('Social Media Links') }}</h5>
                    <button type="button" class="primary-btn" id="social-media-add-more-btn"
                        onclick="socialMediaAddMore()">
                        {{ __('Add More') }}
                    </button>
                </div>
                <p class="text-muted mb-3">{{ __('Maximum 4 social media links allowed') }}</p>
                
                <div id="social-media-cards-wrapper">
                    @for($n = 1; $n <= 4; $n++)
                    <div class="social-media-card-block border rounded p-3 mb-3" data-card-num="{{ $n }}" style="{{ $n > $socialCount ? 'display:none' : '' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ __('Social Media') }} #{{ $n }}</h6>
                            <div class="d-flex align-items-center gap-2">
                                <label class="switch switch-sm">
                                    <input type="hidden" name="social_media_{{ $n }}_status" value="0">
                                    <!-- <input type="checkbox" name="social_media_{{ $n }}_status" value="1" 
                                        {{ getOption('social_media_' . $n . '_status', 1) == 1 ? 'checked' : '' }}> -->
                                    <span class="slider round"></span>
                                </label>
                                @if($n > 1)
                                <button type="button" class="btn btn-sm btn-danger social-media-remove-btn"
                                    onclick="socialMediaRemoveCard({{ $n }})"
                                    style="{{ $n !== $socialCount ? 'display:none' : '' }}">&times;</button>
                                @endif
                            </div>
                        </div>
                        <div class="row gy-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Name') }}</label>
                                    <input type="text" name="social_media_{{ $n }}_name" class="form-control"
                                        value="{{ getOption('social_media_' . $n . '_name') }}"
                                        placeholder="{{ __('e.g., Instagram') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('URL') }}</label>
                                    <input type="url" name="social_media_{{ $n }}_url" class="form-control"
                                        value="{{ getOption('social_media_' . $n . '_url') }}"
                                        placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Save') }}</button>
        </div>
    </form>
</div>

<script src="{{ asset('super_admin/js/landing/footer-config.js') }}"></script>
