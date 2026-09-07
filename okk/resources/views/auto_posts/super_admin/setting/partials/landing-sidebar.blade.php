<div class="settings-page-left">
    <nav class="settings-menu">
        <ul>
            <li>
                <a href="{{ route('super_admin.setting.frontend.landing-page.hero') }}"
                    class="{{ isset($subHeroSectionActiveClass) && $subHeroSectionActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.frontend.landing-page.hero') ? 'active' : '') }}">
                    {{ __('Hero Section') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.frontend.landing-page') }}"
                    class="{{ isset($subLandingOtherActiveClass) && $subLandingOtherActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.frontend.landing-page') && !request()->routeIs('super_admin.setting.frontend.landing-page.hero') ? 'active' : '') }}">
                    {{ __('Section Titles, CTA & Images') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </nav>
</div>
