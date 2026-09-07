@extends('auto_posts.frontend.layouts.app')

@push('title')
    {{ getOption('app_name', 'Autopost') }} - {{ $blog->title }}
@endpush

@section('content')

    @include('auto_posts.frontend.layouts.landing-nav')
    <div id="smooth-wrapper">
        <div id="smooth-content">
    {{-- Blog Banner --}}
    <section class="lp-blog-details-banner lp-section-padding">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-6 col-md-12">
                    <div class="hero-content-wrapper">
                        <div class="top">
                            <ul class="breadcrum">
                                <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li><a href="{{ url('/#blog-section') }}"> / {{ __('Blog') }}</a></li>
                                <li> / {{ __('Blog Single') }}</li>
                            </ul>
                            <h1 class="title">{{ $blog->title }}</h1>
                        </div>
                        <div class="bottom">
                            <div class="info">
                                <div class="name">{{ getOption('landing_blog_author_name', 'Admin') }}</div>
                                <p class="position">{{ getOption('landing_blog_author_title', 'Editor') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($blog->image)
                <div class="col-lg-6 col-md-12">
                    <div class="blog-iamge">
                        <img src="{{ getFileUrl($blog->image) }}"
                             alt="{{ $blog->title }}">
                    </div>
                </div>
                @endif

            </div>
            <div class="border-bottom"></div>
        </div>

        <div class="middleshadowbox"></div>
        <div class="topshadowbox"></div>
    </section>

    {{-- Blog Content --}}
    <section class="blog-details-area">
        <div class="container">
            <div class="row">

                <div class="col-lg-8 col-md-8">
                    <div class="content">
                        <h2>{{ $blog->title }}</h2>
                        @if($blog->description)
                            {!! $blog->description !!}
                        @else
                            <p>{{ __('Content will be available soon.') }}</p>
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 offset-lg-1 col-md-4">
                    <div class="blog-sidebar">

                        @if($blog->date)
                            <div class="meta-item">
                                <span class="label">{{ __('Date') }}</span>
                                <span class="value">{{ $blog->date }}</span>
                            </div>
                        @endif

                        <div class="share-section">
                            <span class="label">{{ __('Share') }}</span>
                            <div class="social-links">

                                <a href="#" class="icon icon-x">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 21 21" fill="none">
                                        <path d="M1.97852 2.50775L7.90987 10.9844L2.29035 17.5541H4.49767L8.89761 12.3966L12.5057 17.5541H18.282L12.078 8.67257L17.3367 2.50775H15.1637L11.0935 7.26198L7.77273 2.50775H1.97852ZM5.18827 4.17957H6.9009L15.0739 15.8823H13.3759L5.18827 4.17957Z" fill="#212121"/>
                                    </svg>
                                </a>

                                <a href="#" class="icon icon-ig">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                        <path d="M4.05546 0C1.82937 0 0 1.82692 0 4.05546V9.73702C0 11.9631 1.82692 13.7925 4.05546 13.7925H9.73702C11.9631 13.7925 13.7925 11.9656 13.7925 9.73702V4.05546C13.7925 1.82937 11.9656 0 9.73702 0H4.05546ZM4.05546 1.25386H9.73702C11.2872 1.25386 12.5386 2.50528 12.5386 4.05546V9.73702C12.5386 11.2872 11.2872 12.5386 9.73702 12.5386H4.05546C2.50528 12.5386 1.25386 11.2872 1.25386 9.73702V4.05546C1.25386 2.50528 2.50528 1.25386 4.05546 1.25386ZM10.5991 2.62527C10.2831 2.62527 10.0309 2.87752 10.0309 3.19343C10.0309 3.50934 10.2831 3.76159 10.5991 3.76159C10.915 3.76159 11.1672 3.50934 11.1672 3.19343C11.1672 2.87752 10.915 2.62527 10.5991 2.62527ZM6.89624 3.13466C4.82688 3.13466 3.13466 4.82688 3.13466 6.89624C3.13466 8.9656 4.82688 10.6578 6.89624 10.6578C8.9656 10.6578 10.6578 8.9656 10.6578 6.89624C10.6578 4.82688 8.9656 3.13466 6.89624 3.13466ZM6.89624 4.38852C8.28969 4.38852 9.40397 5.50279 9.40397 6.89624C9.40397 8.28969 8.28969 9.40397 6.89624 9.40397C5.50279 9.40397 4.38852 8.28969 4.38852 6.89624C4.38852 5.50279 5.50279 4.38852 6.89624 4.38852Z" fill="#212121"/>
                                    </svg>
                                </a>

                                <a href="#" class="icon icon-in">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 14" fill="none">
                                        <path d="M3.42933 13.3745H0.253062L0.253062 4.44504L3.42933 4.44504L3.42933 13.3745ZM1.83949 3.22698C0.82382 3.22698 0 2.49255 0 1.60588C7.26972e-09 1.17997 0.193803 0.77151 0.538774 0.47035C0.883744 0.16919 1.35163 0 1.83949 0C2.32735 0 2.79523 0.16919 3.1402 0.47035C3.48517 0.77151 3.67898 1.17997 3.67898 1.60588C3.67898 2.49255 2.85481 3.22698 1.83949 3.22698ZM15.3171 13.3745H12.1477V9.02771C12.1477 7.99176 12.1237 6.66323 10.4963 6.66323C8.84486 6.66323 8.5918 7.78875 8.5918 8.95308V13.3745L5.41895 13.3745L5.41895 4.44504L8.46527 4.44504L8.46527 5.66311H8.50973C8.93378 4.96152 9.96962 4.22113 11.515 4.22113C14.7296 4.22113 15.3205 6.06913 15.3205 8.46943V13.3745H15.3171Z" fill="#212121"/>
                                    </svg>
                                </a>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
