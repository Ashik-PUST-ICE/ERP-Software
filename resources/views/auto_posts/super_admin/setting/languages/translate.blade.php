@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{$title}}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h3 class="title">{{ __('Translate Language') }}</h3>
                    <div class="d-flex flex-wrap gap-2">
                        <a class="primary-btn" href="{{route('super_admin.setting.languages.download', $language->id)}}"
                            title="{{ __('Download File') }}">
                            <i class="fa fa-download me-2"></i>{{ __('Download File') }}
                        </a>
                        <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#importFile"
                            title="{{ __('Import File') }}">
                            <i class="fa fa-upload me-2"></i>{{ __('Import File') }}
                        </button>
                        <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#importModal"
                            title="{{ __('Import Keywords') }}">
                            <i class="fa fa-file-import me-2"></i>{{__('Import Keywords')}}
                        </button>
                        <button type="button" class="primary-btn addmore">
                            <i class="fa fa-plus me-2"></i>{{__('Add More')}}
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <form id="search-form">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control"
                                placeholder="{{__('Search Key or Value')}}">
                            <button class="primary-btn" type="submit">{{__('Search')}}</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="translations-container">
                @include('auto_posts.super_admin.setting.languages.partials.translations_table')
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="language-route" value="{{ route('super_admin.setting.languages.index') }}">
<input type="hidden" id="updateLangItemRoute"
    value="{{ route('super_admin.setting.languages.update.translate', [$language->id]) }}">
<input type="hidden" id="language-translate-route"
    value="{{ route('super_admin.setting.languages.translate', [$language->id]) }}">
<input type="hidden" id="update-text" value="{{ __('Update') }}">

<!-- Import Keywords Modal -->
<div class="modal fade zModalTwo" id="importModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax" action="{{ route('super_admin.setting.languages.import') }}" method="POST"
                data-handler="languageHandler">
                @csrf
                <input type="hidden" name="current" value="{{ $language->iso_code }}">
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Import Language') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="alert alert-warning mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa-solid fa-exclamation-triangle fa-lg mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong>{{ __('Warning:') }}</strong>
                                {{ __('If you import keywords, your current keywords will be deleted and replaced by the imported keywords.') }}
                            </div>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="sf-select-modal-import" class="form-label">{{ __('Language') }}<span
                                            class="required">*</span></label>
                                    <select name="import" class="select form-control wide sf-select-without-search"
                                        id="sf-select-modal-import" required>
                                        <option value="">{{ __('Select Language') }}</option>
                                        @foreach ($languages as $lang)
                                        <option value="{{ $lang->iso_code }}">{{ __($lang->language) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Import') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import File Modal -->
<div class="modal fade zModalTwo" id="importFile" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax" action="{{ route('super_admin.setting.languages.upload', $language->id) }}" method="POST"
                enctype="multipart/form-data" data-handler="languageHandler">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Upload Translated File') }}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="alert alert-info mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="fa-solid fa-info-circle fa-lg mt-1"></i>
                            </div>
                            <div class="flex-grow-1">
                                {{ __('Upload a valid JSON translation file. Existing translations will be merged with the uploaded file. Keys in the uploaded file will overwrite existing keys.') }}
                            </div>
                        </div>
                    </div>

                    <div class="primary-form">
                        <div class="form-group">
                            <label for="file" class="form-label">{{ __('Select JSON File') }}<span
                                    class="required">*</span></label>
                            <input type="file" name="file" class="form-control" accept=".json" required>
                            <small class="form-text text-muted">{{ __('Only JSON files are allowed') }}</small>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Upload') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/languages.css') }}">
@endpush

@push('script')
<script src="{{asset('admin/js/languages.js')}}"></script>
@endpush