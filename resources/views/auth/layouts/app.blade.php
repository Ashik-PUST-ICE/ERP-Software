<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ getOption('app_name') }} - @stack('title' ?? '')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @hasSection('meta')
    @stack('meta')
    @else
    @php
    $metaData = getMeta('home');
    @endphp
    <meta name="description" content="{{ __($metaData['meta_description']) ?? getOption('app_name') }}">
    <meta name="keywords" content="{{ __($metaData['meta_keyword']) }}">
    @endif

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <!-- fonts file -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- css file  -->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/scss/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <script src="{{ asset('assets/js/modernizr-3.11.2.min.js') }}"></script>
    @if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1 && getOption('google_recaptcha_site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
    @stack('style')

    <style>
        {{ getOption('custom_css') }}
    </style>

    @if(getOption('google_analytics_status', 0))
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ getOption('google_analytics_tracking_id') }}">
    </script>
    <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', "{{ getOption('google_analytics_tracking_id') }}");
    </script>
    @endif
</head>

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
    <input type="hidden" id="lang_code" value="{{session('local')}}">
    @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
    <div class="preloader">
        <div id="preloader_status">
            <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}" />
        </div>
    </div>
    @endif

    @yield('content')

    <!-- js file  -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('common/js/common.js') }}?ver={{ env('VERSION' ,0) }}"></script>

    @stack('script')

    <script>
    var currencySymbol = "{{ getCurrencySymbol() }}";
    var currencyPlacement = "{{ getCurrencyPlacement() }}";

    @if(Session::has('success'))
    toastr.success("{{ session('success') }}");
    @endif
    @if(Session::has('error'))
    toastr.error("{{ session('error') }}");
    @endif
    @if(Session::has('info'))
    toastr.info("{{ session('info') }}");
    @endif
    @if(Session::has('warning'))
    toastr.warning("{{ session('warning') }}");
    @endif

    @if(@$errors->any())
    @foreach($errors->all() as $error)
    toastr.error("{{ $error }}");
    @endforeach
    @endif
    </script>
</body>

</html>