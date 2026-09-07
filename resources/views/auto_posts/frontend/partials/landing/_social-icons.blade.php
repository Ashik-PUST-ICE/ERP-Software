@php
    $icons = [
        ['class' => 'facebook',  'src' => 'assets/images/hero/fb.svg',        'alt' => 'Facebook',   'dir' => 'right', 'delay' => 100],
        ['class' => 'threads',   'src' => 'assets/images/hero/threads.svg',    'alt' => 'Threads',    'dir' => 'right', 'delay' => 200],
        ['class' => 'linkedin',  'src' => 'assets/images/hero/linkedin.svg',   'alt' => 'LinkedIn',   'dir' => 'right', 'delay' => 300],
        ['class' => 'instagram', 'src' => 'assets/images/hero/instagram.svg',  'alt' => 'Instagram',  'dir' => 'left',  'delay' => 100],
        ['class' => 'tik-tok',   'src' => 'assets/images/hero/tik-tok.svg',    'alt' => 'TikTok',     'dir' => 'left',  'delay' => 200],
        ['class' => 'youtube',   'src' => 'assets/images/hero/youtube.svg',    'alt' => 'YouTube',    'dir' => 'left',  'delay' => 300],
    ];
@endphp

<div class="social-icons">
    @foreach($icons as $icon)
        <div class="icon {{ $icon['class'] }}"
             data-aos="fade-{{ $icon['dir'] }}"
             data-aos-delay="{{ $icon['delay'] }}"
             data-aos-duration="1000">
            <img src="{{ asset($icon['src']) }}" alt="{{ $icon['alt'] }}">
        </div>
    @endforeach
</div>
