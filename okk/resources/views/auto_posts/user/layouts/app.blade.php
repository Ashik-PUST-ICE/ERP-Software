<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('auto_posts.user.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
<input type="hidden" id="lang_code" value="{{session('local')}}">
<div class="overflow-x-hidden">
    @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
        <div class="preloader">
            <div id="preloader_status">
                <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}"/>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="main-wrap">
        <!-- Sidebar -->
        @include('auto_posts.user.layouts.sidebar')
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            @include('auto_posts.user.layouts.nav')
            <!-- Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </main>
</div>
@if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg">
        @include('cookie-consent::index')
    </div>
@endif
@include('auto_posts.user.layouts.script')
</body>

</html>
