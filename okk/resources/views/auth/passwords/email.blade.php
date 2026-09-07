@extends('auth.layouts.app')

@push('title')
    {{ __('Forget Password') }}
@endpush

@section('content')
    <div class="auth-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="{{ route('index') }}" class="brand-logo">
                    <img src="{{ getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png') }}" alt="{{ getOption('app_name') }}">
                </a>
                <div class="auth-top">
                    <h2>{{ __('Forgot Password') }}</h2>
                    @if (getOption('disable_registration') != 1)
                        <h3>{{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="auth-link">{{ __('Sign Up') }}</a></h3>
                    @endif
                </div>
                <form method="POST" action="{{ route('password.email') }}">
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
                        <button type="submit" class="auth-form-btn">{{ __('Continue') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="auth-right">
            <img class="img-fluid" src="{{ getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg') }}" alt="auth-image">
        </div>
    </div>
@endsection
