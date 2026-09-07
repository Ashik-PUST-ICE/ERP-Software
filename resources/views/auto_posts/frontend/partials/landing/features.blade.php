@if(getOption('features_section_status', 1) == 1)
<section class="lp-featured-areas lp-section-padding" id="feature-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    {{ getOption('landing_features_title', __('Easily Manage Platform & Accounts.')) }}
                </h2>
            </div>
        </div>

        @php
            $featureCardClasses = ['orange', 'sky', 'blue', 'pink', 'orange', 'sky'];
        @endphp

        <div class="featured-card-grid">
            @for($i = 1; $i <= 6; $i++)
                @php
                    $titleKey = 'landing_feature_card' . $i . '_title';
                    $descKey  = 'landing_feature_card' . $i . '_description';
                    $imgKey   = 'landing_feature_card' . $i . '_image';
                    $hasTitle = trim((string) getOption($titleKey, '')) !== '';
                    $hasDesc  = trim((string) getOption($descKey, '')) !== '';
                    $hasImg   = (bool) getOption($imgKey);
                    $show     = $hasTitle || $hasDesc || $hasImg;
                @endphp
                @if($show)
                <div class="featured-card {{ $featureCardClasses[$i - 1] ?? 'orange' }}"
                     data-aos="fade-up"
                     data-aos-delay="{{ 300 + ($i - 1) * 100 }}"
                     data-aos-duration="1000">
                    @if($hasTitle || $hasDesc)
                    <div class="content">
                        @if($hasTitle)<h3 class="title">{{ getOption($titleKey) }}</h3>@endif
                        @if($hasDesc)<p>{{ getOption($descKey) }}</p>@endif
                    </div>
                    @endif
                    @if($hasImg)
                    <img src="{{ getSettingImage($imgKey) }}"
                         alt="{{ getOption($titleKey) }}">
                    @endif
                </div>
                @endif
            @endfor
        </div>
    </div>
</section>
@endif
