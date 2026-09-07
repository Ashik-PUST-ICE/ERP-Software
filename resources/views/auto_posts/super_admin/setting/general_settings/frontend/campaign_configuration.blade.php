<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Campaign Calendar Section Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

@php
$cardCount = 1;
for ($i = 2; $i <= 12; $i++) {
    if (trim((string) getOption('landing_campaign_card' . $i . '_title', '')) !== '' || trim((string) getOption('landing_campaign_card' . $i . '_description', '')) !== '' || getOption('landing_campaign_card' . $i . '_image')) {
        $cardCount = $i;
    }
}
@endphp

<div class="primary-form">
    <form class="ajax" action="{{ route('super_admin.setting.frontend.landing-page.update') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal" id="campaign-config-form">
        @csrf
        <input type="hidden" name="landing_campaign_card_count" id="landing_campaign_card_count" value="{{ $cardCount }}">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label">{{ __('Section Title') }}</label>
                    <input type="text" name="landing_campaign_title" class="form-control"
                        value="{{ getOption('landing_campaign_title') }}"
                        placeholder="{{ __('Enter campaign section title') }}">
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">{{ __('Campaign Cards (Title, Description & Image)') }}</h5>
                    <button type="button" class="primary-btn" id="campaign-add-more-btn" onclick="campaignAddMore()">
                        {{ __('Add More') }}
                    </button>
                </div>
            </div>

            <div class="col-12" id="campaign-cards-wrapper">
                @for($n = 1; $n <= 12; $n++)
                <div class="campaign-card-block border rounded p-3 mb-3" data-card-num="{{ $n }}"
                    style="{{ $n > $cardCount ? 'display:none' : '' }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">{{ __('Card') }} #{{ $n }}</h6>
                        @if($n > 1)
                        <button type="button" class="btn btn-sm btn-danger campaign-remove-btn"
                            onclick="campaignRemoveCard({{ $n }})"
                            style="{{ $n !== $cardCount ? 'display:none' : '' }}">&times;</button>
                        @endif
                    </div>
                    <div class="row gy-3">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Name') }} ({{ __('Card') }} {{ $n }})</label>
                                <input type="text" name="landing_campaign_card{{ $n }}_title" class="form-control"
                                    value="{{ getOption('landing_campaign_card' . $n . '_title') }}"
                                    placeholder="{{ __('Enter name') }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Description') }} ({{ __('Card') }} {{ $n }})</label>
                                <textarea name="landing_campaign_card{{ $n }}_description" class="form-control" rows="2"
                                    placeholder="{{ __('Enter card description') }}">{{ getOption('landing_campaign_card' . $n . '_description') }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label d-block">{{ __('Image') }} ({{ __('Card') }} {{ $n }})</label>
                                <div class="zImage-upload-details mw-100">
                                    <div class="upload-img-box upload-image-box-new">
                                        <img src="{{ getOption('landing_campaign_card' . $n . '_image') ? getSettingImage('landing_campaign_card' . $n . '_image') : '' }}" />
                                        <input type="file" name="landing_campaign_card{{ $n }}_image" accept="image/*"
                                            onchange="previewFile(this)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="primary-btn">{{ __('Save') }}</button>
        </div>
    </form>
</div>

<script src="{{ asset('super_admin/js/landing/campaign-config.js') }}"></script>
