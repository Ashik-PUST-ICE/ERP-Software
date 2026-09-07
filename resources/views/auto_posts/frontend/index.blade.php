@extends('auto_posts.frontend.layouts.app')

@push('title')
    {{ getOption('app_name', 'Autopost') }} - {{ getOption('landing_meta_title', 'AI-Powered Social Media Scheduling') }}
@endpush

@section('content')

    @include('auto_posts.frontend.layouts.landing-nav')

    <div id="smooth-wrapper">
        <div id="smooth-content">
            <main id="home">

                @include('auto_posts.frontend.partials.landing.hero')
                @include('auto_posts.frontend.partials.landing.features')
                @include('auto_posts.frontend.partials.landing.post-management')
                @include('auto_posts.frontend.partials.landing.campaign')
                @include('auto_posts.frontend.partials.landing.tools')
                @include('auto_posts.frontend.partials.landing.pricing')
                @include('auto_posts.frontend.partials.landing.testimonials')
                @include('auto_posts.frontend.partials.landing.blog')
                @include('auto_posts.frontend.partials.landing.faq')
                @include('auto_posts.frontend.partials.landing.cta')

            </main>

@endsection
