@extends('auth.layouts.app')

@push('title')
{{ __('Verify') }}
@endpush

@section('content')
<div class="auth-area">
    <div class="auth-left">
        <div class="auth-form-wrap">
            <a href="{{ route('login') }}" class="brand-logo">
                <img src="{{ getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png') }}"
                    alt="{{ getOption('app_name') }}">
            </a>
            <div class="auth-top">
                <h2>{{ __('Confirm Email') }}</h2>
                <h3>{{ __('Please check your email') }} <span>{{ $user->email }}</span>
                    {{ __('and enter the otp below to verify') }}</h3>
            </div>
            <form action="{{ route('email.verified', $token) }}" method="POST" class="otp-form" name="otp-form">
                @csrf
                <div class="auth-form">
                    <div class="form-group">
                        <label class="form-label text-center w-100 mb-3">{{ __('Enter OTP Code') }}</label>
                        <div class="otp-input-fields d-flex justify-content-center gap-2" id="otp-block">
                            <input type="text" name="otp__field__1" id="otp__field__1" maxlength="1" required
                                class="otp__digit otp__field__1 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                            <input type="text" name="otp__field__2" id="otp__field__2" maxlength="1" required
                                class="otp__digit otp__field__2 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                            <input type="text" name="otp__field__3" id="otp__field__3" maxlength="1" required
                                class="otp__digit otp__field__3 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                            <input type="text" name="otp__field__4" id="otp__field__4" maxlength="1" required
                                class="otp__digit otp__field__4 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                        </div>
                    </div>
                    <p class="text-center fs-12 text-muted pt-2 pb-3">{{ __('Send the code again after') }} <span
                            id="send-after-timer"></span></p>
                    <div class="d-none" id="resent-div">
                        <button type="button"
                            onclick="event.preventDefault(); document.getElementById('resent-form').submit();"
                            class="auth-form-btn w-100 mb-3"
                            title="{{ __('Click here to request another') }}">{{ __('Click here to request another') }}</button>
                    </div>
                    <button id="verify-btn" type="submit" class="auth-form-btn">{{ __('Verify') }}</button>
                </div>
            </form>
            <form method="POST" action="{{ route('email.verify.resend', $token) }}" class="d-none" id="resent-form">
                @csrf
            </form>
        </div>
    </div>
    <div class="auth-right">
        <img class="img-fluid" src="{{ getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg') }}"
            alt="auth-image">
    </div>
</div>
@endsection

@push('script'
<script>
// Set the date we're counting down to
var countDownDate = new Date('{{ $user->otp_expiry }}').getTime();
var currentTime = new Date('{{ now() }}');
var oldTime = 0;
</script>

<script src="{{ asset('user/js/verify_timer.js') }}"></script>
@endpush