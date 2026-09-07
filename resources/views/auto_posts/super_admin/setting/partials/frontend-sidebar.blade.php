<div class="settings-page-left">
    <nav class="settings-menu">
        <ul>
            <li>
                <a href="{{ route('super_admin.setting.frontend.landing-page.hero') }}"
                    class="{{ isset($subLandingPageActiveClass) && $subLandingPageActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.frontend.landing-page*') ? 'active' : '') }}">
                    {{ __('Landing Page') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
