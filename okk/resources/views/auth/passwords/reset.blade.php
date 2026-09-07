@extends('auth.layouts.app')

@push('title')
    {{ __('Reset Password') }}
@endpush

@section('content')
    <div class="auth-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="{{ route('login') }}" class="brand-logo">
                    <img src="{{ getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png') }}" alt="{{ getOption('app_name') }}">
                </a>
                <div class="auth-top">
                    <h2>{{ __('Set your new password') }}</h2>
                </div>
                <form method="POST" action="{{ route('password.update', $token) }}">
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
                            <label for="password" class="form-label">{{ __('New Password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                placeholder="**********" required>
                            @error('password')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" 
                                placeholder="**********" required>
                            @error('password_confirmation')
                                <span class="text-danger fs-12">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="auth-form-btn">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="auth-right">
            <img class="img-fluid" src="{{ getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg') }}" alt="auth-image">
        </div>
    </div>
@endsection
