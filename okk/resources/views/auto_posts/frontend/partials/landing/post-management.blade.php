@if(getOption('landing_post_management_status', 1) == 1)
<section class="post-management lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    {{ getOption('landing_post_management_title', __('Easy Post Creation With AI & Manage Posts.')) }}
                </h2>
            </div>
        </div>

        @php
            $postCards = [
                1 => ['bgClass' => ''],          2 => ['bgClass' => 'bg-orange'],
                3 => ['bgClass' => 'bg-teal'],   4 => ['bgClass' => 'bg-orange'],
            ];
        @endphp

        <div class="post-management-grid">
            @foreach($postCards as $n => $card)
                @php
                    $titleKey = 'landing_post_card' . $n . '_title';
                    $descKey  = 'landing_post_card' . $n . '_description';
                    $imgKey   = 'landing_post_card' . $n . '_image';
                    $hasTitle = trim((string) getOption($titleKey, '')) !== '';
                    $hasDesc  = trim((string) getOption($descKey, '')) !== '';
                    $hasImg   = (bool) getOption($imgKey);
                    $show     = $hasTitle || $hasDesc || $hasImg;
                @endphp
                @if($show)
                    <article class="post-management-card"
                             data-aos="fade-up"
                             data-aos-delay="{{ 200 + ($n - 1) * 100 }}"
                             data-aos-duration="1000">
                        @if($hasImg)
                        <div class="card-image {{ $card['bgClass'] }}">
                            <img src="{{ getSettingImage($imgKey) }}"
                                 alt="{{ getOption($titleKey) }}"
                                 style="max-width:100%;height:auto;">
                        </div>
                        @endif
                        @if($hasTitle || $hasDesc)
                        <div class="card-content">
                            @if($hasTitle)<h3>{{ getOption($titleKey) }}</h3>@endif
                            @if($hasDesc)<p>{{ getOption($descKey) }}</p>@endif
                        </div>
                        @endif
                    </article>
                @endif
            @endforeach
        </div>

        @php
            $manageAllImg   = getOption('landing_post_manage_all_image');
            $manageAllTitle = trim((string) getOption('landing_post_manage_all_title', ''));
            $manageAllDesc  = trim((string) getOption('landing_post_manage_all_description', ''));
            $showManageAll  = $manageAllImg || $manageAllTitle !== '' || $manageAllDesc !== '';
        @endphp
        @if($showManageAll)
        <div class="manage-posts-banner" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1000">
            @if($manageAllTitle !== '' || $manageAllDesc !== '')
            <div class="banner-text">
                @if($manageAllTitle !== '')<h3>{{ getOption('landing_post_manage_all_title') }}</h3>@endif
                @if($manageAllDesc !== '')<p>{{ getOption('landing_post_manage_all_description') }}</p>@endif
            </div>
            @endif
            @if($manageAllImg)
            <div class="banner-image">
                <img src="{{ getSettingImage('landing_post_manage_all_image') }}"
                     alt="{{ $manageAllTitle ?: __('Management Table') }}"
                     style="max-width:100%;height:auto;">
            </div>
            @endif
        </div>
        @endif
    </div>
    <div class="bg-bottom"></div>
</section>
@endif
