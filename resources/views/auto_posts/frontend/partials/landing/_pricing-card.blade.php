@if($package->icon)
<div class="pricing-card" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
    <div class="text-center">
        <div class="icon-circle">
            <img src="{{ getFileUrl($package->icon) }}" alt="{{ $package->name }}">
        </div>
        <h3>{{ $package->name }}</h3>
        <div class="price">${{ $price }}</div>
    </div>

    <div class="pricing-body">
        <ul class="features-list">
            @if(!empty($package->description))
            <li class="included">{{ $package->description }}</li>
            @endif

            @if(is_array($package->features) && count($package->features))
            @foreach($package->features as $feature)
            <li class="included">{{ $feature }}</li>
            @endforeach
            @endif

            @if($package->ai_enabled)
            <li class="included">{{ __('AI Features Included') }}</li>
            @endif

            @if($package->post_limit)
            <li class="included">{{ __('Post limit') }}: {{ $package->post_limit }}</li>
            @endif
        </ul>


    </div>

    <a href="{{ route('register') }}" class="primary-btn">{{ __('Purchase Now') }}</a>
</div>
@endif