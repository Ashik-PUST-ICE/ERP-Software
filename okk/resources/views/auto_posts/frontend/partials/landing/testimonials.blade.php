@php
    $testimonialCount = max(1, min(10, (int) getOption('landing_testimonial_card_count', 2)));
    $testimonialSlides = [];
    for ($i = 1; $i <= $testimonialCount; $i++) {
        $quote = trim((string) getOption('landing_testimonial_card' . $i . '_quote', ''));
        if ($quote !== '') {
            $testimonialSlides[] = $i;
        }
    }
    $testimonialsTitle = trim((string) getOption('landing_testimonials_title', ''));
@endphp
@if(getOption('landing_testimonials_status', 1) == 1 && count($testimonialSlides) > 0)
<section class="testimonial-section lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                @if($testimonialsTitle !== '')
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    {{ $testimonialsTitle }}
                </h2>
                @endif
            </div>
        </div>
    </div>

    @if(count($testimonialSlides) > 0)
    <div class="swiper testimonialSwiper">
        <div class="swiper-wrapper">
            @foreach($testimonialSlides as $i)
                @php
                    $name        = trim((string) getOption('landing_testimonial_card' . $i . '_name', ''));
                    $designation = trim((string) getOption('landing_testimonial_card' . $i . '_designation', ''));
                    $rating      = max(0, min(5, (int) getOption('landing_testimonial_card' . $i . '_rating', 0)));
                    $imgKey      = 'landing_testimonial_card' . $i . '_image';
                    $hasImage    = (bool) getOption($imgKey);
                    $quote       = trim((string) getOption('landing_testimonial_card' . $i . '_quote', ''));
                @endphp
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <p class="feedback">{{ $quote }}</p>
                        <div class="user-info-wrapper">
                            <div class="user-details">
                                @if($hasImage)
                                    <img src="{{ getSettingImage($imgKey) }}"
                                         alt="{{ $name ?: 'Customer' }}"
                                         class="avatar">
                                @endif
                                <div class="meta">
                                    @if($name !== '')
                                        <div class="name">{{ $name }}</div>
                                    @endif
                                    @if($designation !== '')
                                        <p class="designation">{{ $designation }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="rating">
                                <span class="stars">
                                    {{ str_repeat('★', $rating) }}{{ str_repeat('☆', 5 - $rating) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</section>
@endif
