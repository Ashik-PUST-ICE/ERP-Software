@php
$ctaTitle = trim((string) getOption('landing_cta_title', ''));
$ctaDesc = trim((string) getOption('landing_cta_description', ''));
$ctaBtn = trim((string) getOption('landing_cta_button_text', ''));
$ctaBg = getOption('landing_cta_background');
$ctaShow = $ctaTitle !== '' || $ctaDesc !== '' || $ctaBtn !== '' || $ctaBg;
@endphp
@if(getOption('landing_cta_status', 1) == 1 && $ctaShow)
<section class="cta-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cta-wrapper" @if($ctaBg)
                    style="background-image: url({{ getSettingImage('landing_cta_background') }});" @endif
                    data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">

                    @if($ctaTitle !== '' || $ctaDesc !== '' || $ctaBtn !== '')
                    <div class="content">
                        @if($ctaTitle !== '')<h2 class="title">{{ $ctaTitle }}</h2>@endif
                        @if($ctaDesc !== '')<p class="description">{{ $ctaDesc }}</p>@endif
                        @if($ctaBtn !== '')<a href="{{ route('register') }}" class="primary-btn">{{ $ctaBtn }}</a>@endif
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="bg-bottom"></div>
</section>
@endif