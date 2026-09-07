<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

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

    <!-- Open Graph meta tags for social sharing -->
    <meta property="og:type" content="{{ __('Alumni') }}">
    <meta property="og:title" content="{{ __($metaData['meta_title']) ?? getOption('app_name') }}">
    <meta property="og:description" content="{{ __($metaData['meta_description']) ?? getOption('app_name') }}">
    <meta property="og:image" content="{{ __($metaData['og_image']) ?? getSettingImage('app_logo') }}">

    <meta property="og:url" content="{{ url()->current() }}">

    <meta property="og:site_name" content="{{ __(getOption('app_name')) }}">

    <!-- Twitter Card meta tags for Twitter sharing -->
    <meta name="twitter:card" content="{{ __('Alumni') }}">
    <meta name="twitter:title" content="{{ __($metaData['meta_title']) ?? getOption('app_name') }}">
    <meta name="twitter:description" content="{{ __($metaData['meta_description']) ?? getOption('app_name') }}">
    <meta name="twitter:image" content="{{ __($metaData['og_image']) ?? getSettingImage('app_logo') }}">

    @endif

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}">
    <!-- fonts file -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- css file  -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}" />
    {{--    <link rel="stylesheet" href="{{ asset('assets_old/css/plugins.css') }}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/scss/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}" />
    {{--    <script src="{{ asset('assets/js/modernizr-3.11.2.min.js') }}"></script>--}}
    {{--    <link rel="stylesheet" href="{{ asset('assets_old/css/dataTables.css') }}" />--}}
    {{--    <link rel="stylesheet" href="{{ asset('assets_old/css/dataTables.responsive.min.css') }}" />--}}

    @include('auto_posts.admin.layouts.dynamic-color')
    @stack('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        {!! getOption('custom_css') !!}
    </style>
<link rel="stylesheet" href="{{ asset('admin/css/header-extra.css') }}">

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
