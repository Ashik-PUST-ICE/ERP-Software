@extends('auto_posts.frontend.layouts.app')

@push('title'){{ $page->en_title }} - {{ getOption('app_name', 'Autopost') }}@endpush

@section('content')

<!-- Header Area Start -->
<header class="lp-header">
    <div class="container">
        <div class="nav-wrapper">

            <!-- Logo -->
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ getSettingImage('app_black_logo') }}" alt="{{ getOption('app_name', 'PostBot') }}">
                </a>
            </div>

            <!-- Navigation -->
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
                $navPages = $navPages ?? collect([]);
                @endphp
                <ul>
                    <li>
                        <a href="{{ url('/') }}">{{ __('Home') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('/#feature-section') }}">{{ __('Features') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('/#blog-section') }}">{{ __('Blog') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('/#faq-section') }}">{{ __('FAQ') }}</a>
                    </li>
                    <li>
                        <a href="{{ url('/#pricing-section') }}">{{ __('Pricing') }}</a>
                    </li>

                    @if($navPages->count())
                    <li class="has-dropdown">
                        <a href="javascript:void(0)">{{ __('Pages') }}</a>
                        <ul class="dropdown">
                            @foreach($navPages as $p)
                            <li>
                                <a href="{{ url($p->slug) }}">{{ __($p->en_title) }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>

            <!-- Actions -->
            <div class="nav-actions">
                @guest
                <a href="{{ route('login') }}" class="primary-btn bg-white">{{ __('Sign In') }}</a>
                <a href="{{ route('register') }}" class="primary-btn">{{ __('Sign Up') }}</a>
                @else
                <a href="{{ route('login') }}" class="primary-btn bg-white">
                    {{ __('Dashboard') }}
                </a>
                @endguest
            </div>

            <!-- Mobile Toggle -->
            <div class="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</header>
<!-- Header Area End -->

<main>
    <!-- Page Content Area Start -->
    <section class="lp-dynamic-page lp-section-padding" style="padding-top: 200px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h1 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                        {{ __($page->en_title) }}
                    </h1>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="dynamic-page-content" data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                        {!! $page->en_description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Page Content Area End -->
</main>

@endsection
