<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ selectedLanguage()->rtl == 1 ? 'rtl' : 'ltr' }}">

@include('auto_posts.super_admin.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
    <input type="hidden" id="lang_code" value="{{session('local')}}">
    <!-- Preloader Start -->
    @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
        <div class="preloader">
            <div id="preloader_status">
                <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}"/>
            </div>
        </div>
    @endif
    <!-- Preloader End -->


    <!-- Main Content -->
    <main class="main-wrap">
        <!-- Sidebar -->
        @include('auto_posts.super_admin.layouts.sidebar')
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <!-- Sticky Header Area Start -->
            
            @include('auto_posts.super_admin.layouts.nav')

            <!-- Sticky Header Area End -->
            <!-- Content -->
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- <div class="overflow-x-hidden">
    </div> -->
    @if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
        <div class="cookie-consent-wrap shadow-lg">
            @include('cookie-consent::index')
        </div>
    @endif
    @include('auto_posts.super_admin.layouts.script')
</body>

</html>
