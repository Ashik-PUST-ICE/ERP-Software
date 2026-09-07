@if(getOption('homepage_hero_status', 1) == 1)
<section class="lp-banner-area lp-section-padding" id="home">

    {{-- Desktop layout --}}
    <div class="content-wrapper mid-device-none">
        <div class="fixed-content-area">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-7 col-md-8">
                        <div class="lp-banner-content">
                            <h1 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                                {{ getOption('landing_hero_title', __('AI-Powered Social Media Scheduling Made Simple.')) }}
                            </h1>
                            <p data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                                {{ getOption('landing_hero_subtitle', __('Plan, create, and publish smarter content automatically — all from one powerful, easy-to-use dashboard.')) }}
                            </p>
                            <a href="{{ getOption('landing_hero_button_url', route('register')) }}"
                               class="primary-btn"
                               data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                                {{ getOption('landing_hero_button_text', __('Get Started For Free')) }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if(getOption('landing_hero_background'))
            <div class="bgimg">
                <img src="{{ getSettingImage('landing_hero_background') }}"
                     alt="{{ __('Banner Background') }}">
            </div>
            @endif
            <div class="middleshadowbox"></div>
        </div>

        <div class="social-icons-area">
            <div class="container">
                @include('auto_posts.frontend.partials.landing._social-icons', ['device' => 'desktop'])
            </div>
        </div>
    </div>

    {{-- Mobile layout --}}
    <div class="lg-device-none">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-7 col-md-8">
                    <div class="lp-banner-content">
                        <h1 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                            {{ getOption('landing_hero_title', __('AI-Powered Social Media Scheduling Made Simple.')) }}
                        </h1>
                        <p data-aos="fade-up" data-aos-delay="300" data-aos-duration="1000">
                            {{ getOption('landing_hero_subtitle', __('Plan, create, and publish smarter content automatically — all from one powerful, easy-to-use dashboard.')) }}
                        </p>
                        <a href="{{ getOption('landing_hero_button_url', route('register')) }}"
                           class="primary-btn"
                           data-aos="fade-up" data-aos-delay="400" data-aos-duration="1000">
                            {{ getOption('landing_hero_button_text', __('Get Started For Free')) }}
                        </a>
                    </div>
                </div>
            </div>
            @include('auto_posts.frontend.partials.landing._social-icons', ['device' => 'mobile'])
        </div>
    </div>

    @if(getOption('landing_hero_image'))
    <div class="dasboard-image" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1000">
        <img src="{{ getSettingImage('landing_hero_image') }}"
             alt="{{ __('Dashboard image') }}">
    </div>
    @endif

    <div class="topshadowbox"></div>
</section>
@endif
