@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ $title }}</h2>
</div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title">{{ __($title) }}</h3>
            </div>

            <!-- Instructions Section -->
            <div class="alert alert-info mb-4" role="alert">
                <h5 class="alert-heading mb-3">{{ __('Instructions') }}:</h5>
                <p class="mb-3">
                    {{ __("You need to follow some instruction after maintenance mode changes. Instruction list given below-") }}
                </p>
                <ul class="mb-0 ps-3">
                    <li class="mb-2">
                        {{ __("If you select maintenance mode") }} <strong>{{ __("Maintenance On") }}</strong>,
                        {{__("you need to input secret key for maintenance work. Otherwise you can't work this website. And your created secret key helps you to work under maintenance.")}}
                    </li>
                    <li class="mb-2">
                        {{ __("After created maintenance key, you can use this website secretly through this url") }}
                        <span class="iconify" data-icon="arcticons:url-forwarder"></span>
                        <span class="text-primary fw-bold">{{ url('/') }}/({{ __('Your created secret key') }})</span>
                    </li>
                    <li class="mb-2">
                        {{__("Only one time url is browsing with secret key, and you can browse your site in maintenance mode. When maintenance mode on, any user can see maintenance mode error message.")}}
                    </li>
                    <li class="mb-0">
                        {{ __("Unfortunately you forget your secret key and try to connect with your website.") }}
                        <br>
                        {{ __("Then you go to your project folder location") }}
                        <strong>{{ __("Main Files") }}</strong>{{ __("(where your file in cpanel or your hosting)") }}
                        <span class="iconify"
                            data-icon="arcticons:url-forwarder"></span><strong>{{ __("storage") }}</strong>
                        <span class="iconify"
                            data-icon="arcticons:url-forwarder"></span><strong>{{ __("framework") }}</strong>.
                        {{ __("You can see 2 files and need to delete 2 files. Files are:") }}
                        <br>
                        {{ __("1. down") }} <br>
                        {{ __("2. maintenance.php") }}
                    </li>
                </ul>
            </div>

            <div class="primary-form">
                <form id="maintenance-mode-form" class="ajax"
                    action="{{route('super_admin.setting.maintenance.change')}}" method="POST"
                    enctype="multipart/form-data" data-handler="commonResponseForModal">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="maintenance_mode" class="form-label">{{ __('Maintenance Mode') }}<span
                                        class="required">*</span></label>
                                <select name="maintenance_mode" id="maintenance_mode"
                                    class="select form-control wide maintenance_mode sf-select-without-search" required>
                                    <option value="">{{ __('Select Option') }}</option>
                                    <option value="1" @if(getOption('maintenance_mode')==1) selected @endif>
                                        {{ __('Maintenance On') }}</option>
                                    <option value="2" @if(getOption('maintenance_mode') !=1) selected @endif>
                                        {{ __('Live') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="maintenance_secret_key"
                                    class="form-label">{{ __('Maintenance Mode Secret Key') }}</label>
                                <input type="text" name="maintenance_secret_key" id="maintenance_secret_key"
                                    value="{{ getOption('maintenance_secret_key') }}" minlength="6"
                                    class="form-control maintenance_secret_key"
                                    placeholder="{{ __('Enter secret key (min 6 characters)') }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="maintenance_mode_url"
                                    class="form-label">{{ __('Maintenance Mode URL') }}</label>
                                <div class="input-group">
                                    <input type="text" id="maintenance_mode_url" value=""
                                        class="form-control maintenance_mode_url" readonly>
                                    <button type="button" class="btn btn-outline-secondary" id="copy-maintenance-url"
                                        title="{{ __('Copy URL') }}">
                                        <i class="fa-solid fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="maintenance-mode-form" class="primary-btn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script src="{{ asset('super_admin/js/maintenance-mode.js') }}"></script>
@endpush