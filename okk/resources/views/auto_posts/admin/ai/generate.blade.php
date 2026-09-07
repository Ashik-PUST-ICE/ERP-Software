@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Generate AI Content') }}
@endpush

@section('content')
<div id="ai-generate-wrap" class="content-wrapper"
    data-submit-url="{{ route('admin.ai.generate-content.submit') }}"
    data-csrf-token="{{ csrf_token() }}"
    data-toggle-save-url-pattern="{{ route('admin.ai.generated-content.toggle-save', ['id' => ':id']) }}"
    data-msg-generating-video="{{ __('Generating video (this may take a few minutes)...') }}"
    data-msg-generating="{{ __('Generating...') }}"
    data-msg-done="{{ __('Done.') }}"
    data-msg-success="{{ __('Content generated successfully.') }}"
    data-msg-generation-failed="{{ __('Generation failed.') }}"
    data-msg-request-failed="{{ __('Request failed. Try again.') }}">
    <div class="section-title">
        <h2 class="title">{{ __('Generate AI Content') }}</h2>
        <a href="{{ route('admin.ai.generated-content.list') }}" class="primary-btn">{{ __('Generated List') }}</a>
    </div>
    <div class="section-wrap">
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="content_type" class="form-label">{{ __('Content Type') }} <span
                                class="required">*</span></label>
                        <select id="content_type" name="content_type" class="select form-control wide" required>
                            <option value="text">{{ __('Text') }}</option>
                            <option value="image">{{ __('Image') }}</option>
                            <option value="video">{{ __('Video') }}</option>
                        </select>
                        <small class="text-muted">{{ __('Choose what you want to generate.') }}</small>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="tone" class="form-label">{{ __('Tone') }}</label>
                        <select id="tone" name="tone" class="select form-control wide">
                            <option value="">{{ __('Default') }}</option>
                            @foreach(config('ai.tones', []) as $key => $label)
                            <option value="{{ $key }}">{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="language" class="form-label">{{ __('Language') }}</label>
                        <select id="language" name="language" class="select form-control wide">
                            <option value="">{{ __('Default') }}</option>
                            @foreach(languageIsoCode() as $code => $label)
                            <option value="{{ $code }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="max_tokens" class="form-label">{{ __('Max Tokens') }}</label>
                        <input type="number" id="max_tokens" name="max_tokens" class="form-control" min="100" max="4096"
                            placeholder="{{ getOption('openai_max_tokens', 1000) }}" value="">
                        <small class="text-muted">{{ __('Leave empty to use default from settings') }}</small>
                    </div>
                </div>
                <div class="col-12" id="wrap-manual-image" style="display: none;">
                    <div class="form-group">
                        <small class="text-muted d-block mb-1">{{ __('Describe the image you want in the prompt below. AI will generate an image from your text.') }}</small>
                    </div>
                </div>
                <div class="col-12" id="wrap-manual-video" style="display: none;">
                    <div class="form-group">
                        <small class="text-muted d-block mb-1">{{ __('Describe the video you want in the prompt below. AI will generate a short video (may take a few minutes).') }}</small>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="prompt" class="form-label">{{ __('Your prompt') }} <span
                                class="required">*</span></label>
                        <textarea id="prompt" name="prompt" class="summernote" rows="5"
                            placeholder="{{ __('e.g. Write a short social media post about...') }}" required></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <button type="button" id="btn-generate" class="primary-btn">{{ __('Generate') }}</button>
                    <span id="generate-status" class="ms-3 text-muted"></span>
                </div>
            </div>
        </div>
        <div class="mt-4" id="result-wrap" style="display: none;" data-id="">
            <div class="section-inner-title d-flex align-items-center justify-content-between">
                <h4 class="title">{{ __('Generated content') }}</h4>
                <button type="button" id="btn-save-content" class="primary-btn btn-sm">
                    <i class="fa-regular fa-bookmark me-1"></i> {{ __('Save to List') }}
                </button>
            </div>
            <div class="primary-form">
                <div class="form-group" id="result-text-wrap">
                    <textarea id="generated-text" class="form-control" rows="10"
                        placeholder="{{ __('Generated content will appear here. You can edit and copy.') }}"></textarea>
                    <small class="text-muted">{{ __('You can edit and copy this content for your posts.') }}</small>
                </div>
                <div class="form-group" id="result-media-wrap" style="display: none;">
                    <div id="result-image-wrap" style="display: none;">
                        <img id="result-generated-image" src="" alt="" style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 8px;">
                        <p class="mt-2 mb-0"><a id="result-image-link" href="" target="_blank" rel="noopener">{{ __('Open image') }}</a></p>
                    </div>
                    <div id="result-video-wrap" style="display: none;">
                        <video id="result-generated-video" controls style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 8px;" src=""></video>
                        <p class="mt-2 mb-0"><a id="result-video-link" href="" target="_blank" rel="noopener">{{ __('Download video') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/ai-generate-content.js') }}"></script>
@endpush