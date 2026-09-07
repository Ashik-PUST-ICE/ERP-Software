@extends('auth.layouts.app')

@push('title')
    {{ __('Login') }}
@endpush

@section('content')
    <div class="auth-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="{{ route('index') }}" class="brand-logo">
                    <img src="{{ getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png') }}" alt="{{ getOption('app_name') }}">
                </a>
                <div class="auth-top">
                    <h2>{{ __('Sign In') }}</h2>
                    @if (getOption('disable_registration') != 1)
                        <h3>{{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="auth-link">{{ __('Sign Up') }}</a></h3>
                    @endif
                </div>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="auth-form">
                        <div class="form-group">
                            <label for="EmailAddress" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="EmailAddress" name="email"
                                value="{{ old('email') }}" placeholder="{{ __('example@gmail.com') }}" required>
                            @error('email')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="Password" class="form-label">
                                {{ __('Password') }}
                                <a href="{{ route('password.request') }}" class="auth-link">{{ __('Forgot Password?') }}</a>
                            </label>
                            <input type="password" class="form-control" id="Password" name="password"
                                placeholder="**********" required>
                            @error('password')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="Remember" name="remember">
                            <label class="form-check-label" for="Remember">{{ __('Remember Me') }}</label>
                        </div>
                        @if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1)
                            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                <div class="g-recaptcha" data-sitekey="{{ getOption('google_recaptcha_site_key') }}"></div>

                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif
                        <button type="submit" class="auth-form-btn">{{ __('Sign In') }}</button>
                    </div>
                </form>
                @if (getOption('google_login_status') == 1 || getOption('facebook_login_status') == 1)
                    <div class="auth-bottom">
                        <h3 class="or-platform-title"><span>{{ __('Or Sign in with') }}</span></h3>
                        <ul class="auth-social-media">
                            @if (getOption('facebook_login_status') == 1)
                                <li>
                                    <a href="{{ route('facebook-login') }}" class="auth-social-btn">
                                        <img src="{{ asset('assets/images/facebook.png') }}" alt="facebook">
                                    </a>
                                </li>
                            @endif
                            @if (getOption('google_login_status') == 1)
                                <li>
                                    <a href="{{ route('google-login') }}" class="auth-social-btn">
                                        <img src="{{ asset('assets/images/google.png') }}" alt="google">
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                @endif
                @if (env('LOGIN_HELP') == 'active')
                    <div class="mt-3">
                        <div id="superAdminCredentialShow" class="login-info text-center p-2 border rounded mb-2 cursor-pointer">
                            <b>Super Admin :</b> suadmin@gmail.com | 123456
                        </div>
                        <div id="adminCredentialShow" class="login-info text-center p-2 border rounded cursor-pointer">
                            <b>Admin :</b> admin@gmail.com | 123456
                        </div>
                    </div>
                @endif
                <!-- <div class="auth-bottom">
                    <h3 class="or-platform-title"><span>Or Sign Up with</span></h3>
                    <ul class="auth-social-media">
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/facebook.png" alt="facebook"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/instagram.png" alt="instagram"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/threads.png" alt="threads"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/twitter.png" alt="twitter"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/linkedin.png" alt="linkedin"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/tik-tok.png" alt="tik-tok"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/youtube.png" alt="youtube"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/google.png" alt="google"></a></li>
                    </ul>
                </div> -->
            </div>
        </div>
        <div class="auth-right">
            <img class="img-fluid" src="{{ getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg') }}" alt="auth-image">
        </div>
    </div>
@endsection

@push('script')
    <script>
        "use strict"
        @if (env('LOGIN_HELP') == 'active')
        $('#superAdminCredentialShow').on('click', function() {
            $('#EmailAddress').val('suadmin@gmail.com');
            $('#Password').val('123456');
        });
        $('#adminCredentialShow').on('click', function() {
            $('#EmailAddress').val('admin@gmail.com');
            $('#Password').val('123456');
        });
        @endif
    </script>
@endpush
