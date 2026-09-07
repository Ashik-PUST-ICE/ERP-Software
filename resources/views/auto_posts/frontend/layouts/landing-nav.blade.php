<header class="lp-header">
    <div class="container">
        <div class="nav-wrapper">

            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ getSettingImage('app_black_logo') }}" alt="{{ getOption('app_name', 'PostBot') }}">
                </a>
            </div>

            <nav class="nav-menu">
                <div class="mobile-menu-heading">
                    <div class="logo">
                        <a href="{{ url('/') }}">
                            <img src="{{ getSettingImage('app_black_logo') }}" alt="{{ getOption('app_name', 'PostBot') }}">
                        </a>
                    </div>
                    <div class="close-button">
                        <img src="{{ asset('assets/images/close-menu.svg') }}" alt="close menu">
                    </div>
                </div>

                @php
                $landingNavLinks = $landingNavLinks ?? [
                ['label' => __('Home'), 'url' => url('/#home')],
                ['label' => __('Features'), 'url' => url('/#feature-section')],
                ['label' => __('Blog'), 'url' => url('/#blog-section')],
                ['label' => __('FAQ'), 'url' => url('/#faq-section')],
                ['label' => __('Pricing'), 'url' => url('/#pricing-section')],
                ];
                $staticMenus = $staticMenus ?? collect([]);
                $navPages = $navPages ?? collect([]);
                @endphp

                <ul>
                    @foreach($landingNavLinks as $item)
                    <li><a href="{{ $item['url'] }}">{{ $item['label'] }}</a></li>
                    @endforeach

                    @foreach($staticMenus as $menu)
                    <li>
                        <a href="{{ $menu->url ? url($menu->url) : url($menu->slug ?? '') }}">
                            {{ __($menu->name) }}
                        </a>
                    </li>
                    @endforeach

                    @if($navPages->count())
                    <li class="has-dropdown">
                        <a href="javascript:void(0)">{{ __('Pages') }}</a>
                        <ul class="dropdown">
                            @foreach($navPages as $p)
                            <li><a href="{{ url($p->slug) }}">{{ __($p->en_title) }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>

            <div class="nav-actions">
                @guest
                <a href="{{ route('login') }}" class="primary-btn bg-white">{{ __('Sign In') }}</a>
                <a href="{{ route('register') }}" class="primary-btn">{{ __('Sign Up') }}</a>
                @else
                @if(auth()->user()->role == USER_ROLE_SUPER_ADMIN)
                <a href="{{ route('super_admin.dashboard') }}" class="primary-btn bg-white">{{ __('Dashboard') }}</a>
                @else
                <a href="{{ route('admin.dashboard') }}" class="primary-btn bg-white">{{ __('Dashboard') }}</a>
                @endif
                @endguest
            </div>

            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>
    </div>
</header>
