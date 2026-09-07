@extends('auth.layouts.app')

@push('title')
    {{ __('Registration') }}
@endpush

@section('content')
    <div class="auth-area registration-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="{{ route('index') }}" class="brand-logo">
                    <img src="{{ getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png') }}" alt="{{ getOption('app_name') }}">
                </a>
                <div class="auth-top">
                    <h2>{{ __('Sign Up') }}</h2>
                    <h3>{{ __('Already have an account?') }} <a href="{{ route('login') }}" class="auth-link">{{ __('Sign In') }}</a></h3>
                </div>
                <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="auth-form">
                        <div class="form-group">
                            <label for="FullName" class="form-label">{{ __('Full Name') }}</label>
                            <input type="text" class="form-control" id="FullName" name="name" 
                                value="{{ old('name') }}" placeholder="{{ __('John Doe') }}" required>
                            @error('name')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="EmailAddress" class="form-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control" id="EmailAddress" name="email" 
                                value="{{ old('email') }}" placeholder="{{ __('example@gmail.com') }}" required>
                            @error('email')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="Password" class="form-label">{{ __('Password') }}</label>
                            <input type="password" class="form-control" id="Password" name="password" 
                                placeholder="**********" required>
                            @error('password')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="ConfirmPassword" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="ConfirmPassword" name="password_confirmation" 
                                placeholder="**********" required>
                            @error('password_confirmation')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        @if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1)
                            <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                {!! RecaptchaV3::field('register') !!}
                                @if ($errors->has('g-recaptcha-response'))
                                    <span class="help-block text-danger">
                                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                    </span>
                                @endif
                            </div>
                        @endif
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="agree_policy" id="termsPrivacy" required>
                            <label class="form-check-label" for="termsPrivacy">
                                {{ __('By clicking Create account, I agree that I have read and accepted the Terms of Use and Privacy Policy.') }}
                            </label>
                        </div>
                        <button type="submit" class="auth-form-btn">{{ __('Sign Up') }}</button>
                    </div>
                </form>
                @if (getOption('google_login_status') == 1 || getOption('facebook_login_status') == 1)
                    <div class="auth-bottom">
                        <h3 class="or-platform-title"><span>{{ __('Or Sign Up with') }}</span></h3>
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