<div class="settings-page-left">
    <nav class="settings-menu">
        <ul>
            <li>
                <a href="{{ route('super_admin.setting.application-settings') }}"
                    class="{{ isset($subApplicationSettingActiveClass) && $subApplicationSettingActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('App Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.logo-settings') }}"
                    class="{{ isset($subLogoSettingActiveClass) && $subLogoSettingActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('Logo Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.color-settings') }}"
                    class="{{ isset($subColorSettingActiveClass) && $subColorSettingActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('Color Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('super_admin.setting.storage.index') }}"
                    class="{{ isset($subStorageSettingActiveClass) && $subStorageSettingActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('Storage Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.maintenance') }}"
                    class="{{ isset($subMaintenanceModeActiveClass) && $subMaintenanceModeActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('Maintenance Mode') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.languages.index') }}"
                    class="{{ isset($subLanguageSettingActiveClass) && $subLanguageSettingActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.languages.*') ? 'active' : '') }}">
                    {{ __('Language Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.currencies.index') }}"
                    class="{{ isset($subCurrencySettingActiveClass) && $subCurrencySettingActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.currencies.*') ? 'active' : '') }}">
                    {{ __('Currency Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.gateway.index') }}"
                    class="{{ isset($subGatewaySettingActiveClass) && $subGatewaySettingActiveClass == 'active' ? 'active' : (request()->routeIs('super_admin.setting.gateway.*') ? 'active' : '') }}">
                    {{ __('Gateway Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.cache-settings') }}"
                    class="{{ isset($subCacheActiveClass) && $subCacheActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('Cache Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('super_admin.setting.ai-settings') }}"
                    class="{{ isset($subAISettingActiveClass) && $subAISettingActiveClass == 'active' ? 'active' : '' }}">
                    {{ __('AI Settings') }} <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>

        </ul>
    </nav>
</div>