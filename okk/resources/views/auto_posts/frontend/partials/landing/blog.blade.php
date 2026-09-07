@if(getOption('landing_blog_status', 1) == 1 && isset($blogs) && $blogs->count())
<section class="blog-area lp-section-padding" id="blog-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    {{ getOption('landing_blog_title', __('Latest From Our Blog.')) }}
                </h2>
            </div>
        </div>

        <div class="row">
            @foreach($blogs as $index => $blog)
                @if($blog->image)
                <div class="col-lg-4 col-md-6 col-12"
                     data-aos="fade-up"
                     data-aos-delay="{{ 100 + ($index * 100) }}"
                     data-aos-duration="1000">
                    <a href="{{ route('blog.show', $blog) }}"
                       target="_blank" rel="noopener"
                       class="blog-card">
                        <div class="image">
                            <img src="{{ getFileUrl($blog->image) }}" alt="{{ $blog->title }}">
                        </div>
                        @if($blog->date)<span class="time">{{ $blog->date }}</span>@endif
                        <h3 class="title">{{ $blog->title }}</h3>
                    </a>
                </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
