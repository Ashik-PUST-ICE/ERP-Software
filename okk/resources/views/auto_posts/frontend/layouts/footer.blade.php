<!-- Footer Area Start -->
<footer class="footer-section">
    <div class="footer-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 border-end-custom">
                    <div class="footer-brand">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name', 'PostBot') }} Logo" class="brand-logo">
                    </div>
                    @if(getOption('footer_left_status', 1) == STATUS_ACTIVE)
                    <p class="footer-description">
                        {{ getOption('footer_left_text', 'Our AI-powered platform helps businesses schedule smarter, post consistently, and grow their audience faster. Manage all your social media in one place with ease.') }}
                    </p>
                    @endif
                </div>

                <div class="col-lg-7 col-md-12">
                    <div class="right-side">
                        <div class="row footer-right-side">
                            <div class="col-md-3 col-6">
                                <div class="footer-title">{{ __('Pages') }}</div>
                                <ul class="list-unstyled footer-links">
                                    <li><a href="{{ url('/#home') }}">{{ __('Home') }}</a></li>
                                    <li><a href="{{ url('/#feature-section') }}">{{ __('Features') }}</a></li>
                                    <li><a href="{{ url('/#pricing-section') }}">{{ __('Pricing') }}</a></li>
                                    <li><a href="{{ url('/#blog-section') }}">{{ __('Blog') }}</a></li>
                                </ul>
                            </div>

                            <div class="col-md-4 col-6">
                                <div class="footer-title">{{ __('Utilities') }}</div>
                                <ul class="list-unstyled footer-links">
                                    <li><a href="{{ route('register') }}">{{ __('Sign Up') }}</a></li>
                                    <li><a href="{{ route('login') }}">{{ __('Sign In') }}</a></li>
                                    <li><a href="#">{{ __('Privacy Policy') }}</a></li>
                                    <li><a href="#">{{ __('Terms & Condition') }}</a></li>
                                </ul>
                            </div>

                            <div class="col-md-5 col-12">
                                @if(getOption('footer_social_status', 1) == 1)
                                <div class="footer-title">{{ __('Social Media') }}</div>
                                <ul class="social-grid">
                                    @for($i = 1; $i <= 4; $i++)
                                        @php
                                            $urlKey = 'social_media_' . $i . '_url';
                                            $nameKey = 'social_media_' . $i . '_name';
                                            $statusKey = 'social_media_' . $i . '_status';
                                            $socialUrl = getOption($urlKey);
                                            $socialName = getOption($nameKey);
                                            $socialStatus = getOption($statusKey, 1);
                                        @endphp
                                        @if($socialUrl && $socialStatus == 1)
                                            <li>
                                                <a href="{{ $socialUrl }}" target="_blank" class="social-link">
                                                    {{ $socialName }}
                                                    <span class="icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                            <path d="M10.8994 0.999547L0.999954 10.899M10.8994 0.999547H2.41417M10.8994 0.999547V9.48483" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </li>
                                        @endif
                                    @endfor
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="copyright-text">
                {!! nl2br(getOption('app_copyright', 'Copyright &copy; ' . date('Y') . ' - All Rights Reserved By <a href="#" class="brand-link">' . getOption('app_name', 'PostBot') . '</a>')) !!}
            </p>
        </div>
    </div>
    <div class="bg-bottom"></div>
</footer>
<!-- Footer Area End -->

    </div>
</div>