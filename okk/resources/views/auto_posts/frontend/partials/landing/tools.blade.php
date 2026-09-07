@if(getOption('landing_tools_status', 1) == 1)
<section class="our-tool-section lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    {{ getOption('landing_tools_title', __('We Have Some Amazing Features For Team.')) }}
                </h2>
            </div>
        </div>

        @php
            $toolIconClasses = [
                1 => 'orange', 2 => 'green',       3 => 'blue',         4 => 'pink',
                5 => 'purple', 6 => 'blue-alt',    7 => 'teal',         8 => 'yellow',
                9 => 'blue-vibrant', 10 => 'green-bright', 11 => 'indigo', 12 => 'coral',
            ];

            $toolCount = 0;
            for ($i = 1; $i <= 16; $i++) {
                if (trim((string) getOption('landing_tool_card' . $i . '_name', '')) !== '') {
                    $toolCount = $i;
                }
            }
        @endphp

        <div class="tool-grid">
            @for($i = 1; $i <= $toolCount; $i++)
                @php
                    $nameKey   = 'landing_tool_card' . $i . '_name';
                    $imgKey    = 'landing_tool_card' . $i . '_image';
                    $title     = trim((string) getOption($nameKey, ''));
                    $hasImg    = (bool) getOption($imgKey);
                    $iconClass = $toolIconClasses[$i] ?? $toolIconClasses[(($i - 1) % 12) + 1];
                @endphp
                @if($title !== '' || $hasImg)
                <div class="tool-card"
                     data-aos="fade-up"
                     data-aos-delay="{{ 100 + ($i - 1) * 100 }}"
                     data-aos-duration="1000">
                    @if($hasImg)
                    <div class="icon-box {{ $iconClass }}">
                        <img src="{{ getSettingImage($imgKey) }}" alt="{{ $title }}">
                    </div>
                    @endif
                    @if($title !== '')<span class="tool-name">{{ $title }}</span>@endif
                </div>
                @endif
            @endfor
        </div>
    </div>
</section>
@endif
