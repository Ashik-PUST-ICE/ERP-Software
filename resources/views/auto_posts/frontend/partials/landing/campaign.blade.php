@if(getOption('landing_campaign_status', 1) == 1)
@php
    $cardClasses = ['pink', 'purple', 'yallow'];
    $hasAnyCard = false;
    for ($n = 1; $n <= 12; $n++) {
        $tk = 'landing_campaign_card' . $n . '_title';
        $dk = 'landing_campaign_card' . $n . '_description';
        $ik = 'landing_campaign_card' . $n . '_image';
        if (trim((string) getOption($tk, '')) !== '' || trim((string) getOption($dk, '')) !== '' || getOption($ik)) {
            $hasAnyCard = true;
            break;
        }
    }
    $sectionTitle = trim((string) getOption('landing_campaign_title', ''));
@endphp
@if($hasAnyCard || $sectionTitle !== '')
<section class="campaing-calender lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            @if($sectionTitle !== '')
            <div class="col-lg-8">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    {{ getOption('landing_campaign_title') }}
                </h2>
            </div>
            @endif

            <div class="col-lg-12">
                <div class="wrapper">
                    @for($n = 1; $n <= 12; $n++)
                        @php
                            $titleKey = 'landing_campaign_card' . $n . '_title';
                            $descKey  = 'landing_campaign_card' . $n . '_description';
                            $imgKey   = 'landing_campaign_card' . $n . '_image';
                            $hasTitle = trim((string) getOption($titleKey, '')) !== '';
                            $hasDesc  = trim((string) getOption($descKey, '')) !== '';
                            $hasImg   = (bool) getOption($imgKey);
                            $showCard = $hasTitle || $hasDesc || $hasImg;
                        @endphp
                        @if($showCard)
                        <div class="image-box {{ $cardClasses[($n - 1) % 3] }}">
                            @if($hasTitle || $hasDesc)
                            <div class="content">
                                @if($hasTitle)<h3>{{ getOption($titleKey) }}</h3>@endif
                                @if($hasDesc)<p>{{ getOption($descKey) }}</p>@endif
                            </div>
                            @endif
                            @if($hasImg)
                            <div class="right-image">
                                <img src="{{ getSettingImage($imgKey) }}" alt="{{ getOption($titleKey) }}" style="max-width:100%;height:auto;">
                            </div>
                            @endif
                        </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endif