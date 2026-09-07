<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('auto_posts.admin.layouts.header')

<style>
    /* Loading Overlay Styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .loading-modal {
        background: #fff;
        padding: 30px 50px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }
    
    .loading-content-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .loading-spinner-wrap {
        margin-top: 20px;
    }
    
    .loading-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loading-title {
        margin: 0;
        font-size: 18px;
        color: #333;
        font-weight: 600;
    }
    
    .loading-subtitle {
        margin: 10px 0 0;
        color: #666;
        font-size: 14px;
    }
</style>

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
    <input type="hidden" id="lang_code" value="{{session('local')}}">
    <div class="overflow-x-hidden">
        @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
        <div id="preloader">
            <div id="preloader_status">
                <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}" />
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="zMain-wrap">
            <!-- Sidebar -->
            @include('auto_posts.admin.layouts.admin-sidebar')
            <!-- Main Content -->
            <div class="zMainContent">
                <!-- Header -->
                @include('auto_posts.admin.layouts.nav')
                <!-- Content -->
                @yield('content')
            </div>
        </div>
    </div>
    @if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg">
        @include('cookie-consent::index')
    </div>
    @endif

    <!-- Loading Overlay -->
    <div id="create-post-loading-overlay" class="loading-overlay" style="display:none;">
        <div class="loading-modal">
            <div class="loading-content-wrap">
                <h3 id="loading-title" class="loading-title">Creating post</h3>
                <p id="loading-subtitle" class="loading-subtitle">Please wait while we create your post.</p>
                <div class="loading-spinner-wrap">
                    <svg class="loading-spinner" width="40" height="40" viewBox="0 0 50 50">
                        <circle cx="25" cy="25" r="20" fill="none" stroke="#ddd" stroke-width="5"></circle>
                        <circle cx="25" cy="25" r="20" fill="none" stroke="#FF4F02" stroke-width="5" stroke-dasharray="90" stroke-dashoffset="60" stroke-linecap="round"></circle>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    @include('auto_posts.admin.layouts.script')
</body>

</html>