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
                <h3 class="title">{{ __('Logo Settings') }}</h3>
            </div>
            <form id="logo-settings-form" class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}"
                    method="POST"
                    enctype="multipart/form-data" data-handler="commonResponseForModal">
                @csrf
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title">{{ __('App Preloader') }}</h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_preloader').click()">
                                    @if(getOption('app_preloader'))
                                        <img class="logo" src="{{ getSettingImage('app_preloader') }}" alt="preloader"/>
                                    @else
                                        <img class="logo" src="{{ asset('assets/images/logo-placehoder.png') }}" alt="logo placeholder"/>
                                    @endif
                                    <input type="file" name="app_preloader" id="app_preloader" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended">{{ __('Recommended Size : 140 X 40') }}</h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title">{{ __('App Logo (White)') }}</h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_logo').click()">
                                    @if(getOption('app_logo'))
                                        <img class="logo" src="{{ getSettingImage('app_logo') }}" alt="logo white"/>
                                    @else
                                        <img class="logo" src="{{ asset('assets/images/logo-placehoder.png') }}" alt="logo placeholder"/>
                                    @endif
                                    <input type="file" name="app_logo" id="app_logo" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended">{{ __('Recommended Size : 140 X 40') }}</h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title">{{ __('App Logo (Black)') }}</h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_black_logo').click()">
                                    @if(getOption('app_black_logo'))
                                        <img class="logo" src="{{ getSettingImage('app_black_logo') }}" alt="logo black"/>
                                    @else
                                        <img class="logo" src="{{ asset('assets/images/logo-placehoder.png') }}" alt="logo placeholder"/>
                                    @endif
                                    <input type="file" name="app_black_logo" id="app_black_logo" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended">{{ __('Recommended Size : 140 X 40') }}</h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title">{{ __('App Fav Icon') }}</h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('app_fav_icon').click()">
                                    @if(getOption('app_fav_icon'))
                                        <img class="logo" src="{{ getSettingImage('app_fav_icon') }}" alt="favicon"/>
                                    @else
                                        <img class="logo" src="{{ asset('assets/images/logo-placehoder.png') }}" alt="logo placeholder"/>
                                    @endif
                                    <input type="file" name="app_fav_icon" id="app_fav_icon" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended">{{ __('Recommended Size : 16 X 16') }}</h5>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-logo">
                            <h4 class="logo-title">{{ __('Login Left Image') }}</h4>
                            <div class="logo-upload">
                                <div class="upload-img-box" onclick="document.getElementById('login_left_image').click()">
                                    @if(getOption('login_left_image'))
                                        <img class="logo" src="{{ getSettingImage('login_left_image') }}" alt="login left image"/>
                                    @else
                                        <img class="logo" src="{{ asset('assets/images/logo-placehoder.png') }}" alt="logo placeholder"/>
                                    @endif
                                    <input type="file" name="login_left_image" id="login_left_image" accept="image/*" style="display:none" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <h5 class="size-recommended">{{ __('Recommended Size : 140 X 40') }}</h5>
                        </div>
                    </div>
                </div>
            </form>
            <div class="btn-list">
                <button type="submit" form="logo-settings-form" class="primary-btn">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
