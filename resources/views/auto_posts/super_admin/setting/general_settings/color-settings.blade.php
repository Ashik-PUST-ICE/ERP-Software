@extends('auto_posts.super_admin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@push('style')
    <link rel="stylesheet" href="{{ asset('super_admin/css/codemirror.css') }}"/>
    <link rel="stylesheet" href="{{ asset('super_admin/css/monokai.css') }}"/>
    <link rel="stylesheet" href="{{ asset('super_admin/css/color-settings.css') }}"/>
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ $title }}</h2>
</div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <!-- Color Settings Section -->
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title">{{ __('Color Settings') }}</h3>
            </div>
            <div class="primary-form">
                <form id="color-settings-form" class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}"
                      method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="app_color_design_type" class="form-label">{{ __('System Color') }}</label>
                                <select name="app_color_design_type" id="app_color_design_type"
                                        class="select form-control wide sf-select-without-search" required>
                                    <option value="{{ DEFAULT_COLOR }}"
                                        {{ getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR ? 'selected' : '' }}>
                                        {{ __('Default') }}</option>
                                    <option value="{{ CUSTOM_COLOR }}"
                                        {{ getOption('app_color_design_type', DEFAULT_COLOR) == CUSTOM_COLOR ? 'selected' : '' }}>
                                        {{ __('Custom') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row gy-4 {{getOption('app_color_design_type', DEFAULT_COLOR) == DEFAULT_COLOR ? 'd-none' : ''}}"
                         id="custom-color-block">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Primary Color') }}<span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: {{getOption('app_primary_color', '#FF4F02')}}" id="app_primary_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_primary_color"
                                               value="{{getOption('app_primary_color', '#FF4F02')}}"
                                               id="app_primary_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_primary_color_value"
                                               value="{{strtoupper(getOption('app_primary_color', '#FF4F02'))}}"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#FF4F02"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #FF4F02 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Hover Color') }}<span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: {{getOption('app_hover_color', '#d93900')}}" id="app_hover_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_hover_color"
                                               value="{{getOption('app_hover_color', '#d93900')}}"
                                               id="app_hover_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_hover_color_value"
                                               value="{{strtoupper(getOption('app_hover_color', '#d93900'))}}"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#d93900"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #d93900 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Text Color') }}<span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: {{getOption('app_text_color', '#1b1c17')}}" id="app_text_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_text_color"
                                               value="{{getOption('app_text_color', '#1b1c17')}}"
                                               id="app_text_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_text_color_value"
                                               value="{{strtoupper(getOption('app_text_color', '#1b1c17'))}}"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#1b1c17"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #1b1c17 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Text Secondary Color') }}<span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: {{getOption('app_text_secondary_color', '#707070')}}" id="app_text_secondary_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_text_secondary_color"
                                               value="{{getOption('app_text_secondary_color', '#707070')}}"
                                               id="app_text_secondary_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_text_secondary_color_value"
                                               value="{{strtoupper(getOption('app_text_secondary_color', '#707070'))}}"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#707070"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #707070 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Sidebar BG Color') }}<span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: {{getOption('app_sidebar_bg_color', '#1b1c17')}}" id="app_sidebar_bg_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_sidebar_bg_color"
                                               value="{{getOption('app_sidebar_bg_color', '#1b1c17')}}"
                                               id="app_sidebar_bg_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_sidebar_bg_color_value"
                                               value="{{strtoupper(getOption('app_sidebar_bg_color', '#1b1c17'))}}"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#1b1c17"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #1b1c17 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Sidebar Text Color') }}<span class="required">*</span></label>
                                <div class="color-picker-wrapper">
                                    <div class="color-preview" style="background-color: {{getOption('app_sidebar_text_color', '#f6f5f5')}}" id="app_sidebar_text_color_preview"></div>
                                    <div class="color-input-group">
                                        <input class="color5 form-control" type="color"
                                               name="app_sidebar_text_color"
                                               value="{{getOption('app_sidebar_text_color', '#f6f5f5')}}"
                                               id="app_sidebar_text_color">
                                        <input type="text" 
                                               class="color-value-display form-control" 
                                               id="app_sidebar_text_color_value"
                                               value="{{strtoupper(getOption('app_sidebar_text_color', '#f6f5f5'))}}"
                                               pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$"
                                               placeholder="#f6f5f5"
                                               maxlength="7"
                                               title="Enter hex color code (e.g., #f6f5f5 or #FFF)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="color-settings-form" class="primary-btn">{{ __('Save') }}</button>
            </div>
        </div>

        <!-- Custom CSS Section -->
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title">{{ __('Custom CSS') }}</h3>
            </div>
            <div class="primary-form">
                <form id="custom-css-form" class="ajax"
                      action="{{ route('super_admin.setting.application-settings.update') }}"
                      method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        {{ __('Custom CSS') }}
                                    </div>
                                    <div class="card-body">
                                        <textarea name="custom_css" id="custom-css-editor" class="form-control">{{getOption('custom_css', '/*css code here*/ ')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="custom-css-form" class="primary-btn">{{ __('Save') }}</button>
            </div>
        </div>

        <!-- Custom JS Section -->
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title">{{ __('Custom JS') }}</h3>
            </div>
            <div class="primary-form">
                <form id="custom-js-form" class="ajax"
                      action="{{ route('super_admin.setting.application-settings.update') }}"
                      method="POST"
                      enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        {{ __('Custom JS') }}
                                    </div>
                                    <div class="card-body">
                                        <textarea name="custom_js"
                                                  id="custom-js-editor" class="form-control">{{getOption('custom_js', '//js code here')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="custom-js-form" class="primary-btn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{ asset('super_admin/js/codemirror.js') }}"></script>
    <script src="{{ asset('super_admin/js/codemirror-mode.js') }}"></script>
    <script src="{{ asset('super_admin/js/codemirror-js-mode.js') }}"></script>
    <script src="{{ asset('super_admin/js/color-settings.js') }}"></script>
@endpush

