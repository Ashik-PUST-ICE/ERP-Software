@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('View AI Content') }}
@endpush

@section('content')
<div class="content-wrapper">
    <div class="section-title">
        <h2 class="title">{{ __('AI Content Details') }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.ai.generated-content.list') }}" class="primary-btn-outline btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Back to List') }}
            </a>
            <form action="{{ route('admin.ai.generated-content.toggle-save', $item->id) }}" method="POST" class="ajax" data-handler="commonResponseHandler">
                @csrf
                <input type="hidden" name="is_saved" value="0">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash-can me-1"></i> {{ __('Remove from Saved') }}
                </button>
            </form>
        </div>
    </div>

    <div class="section-wrap">
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.3);">
                    <div class="card-header bg-transparent border-0 pt-4 px-4">
                        <h5 class="card-title fw-bold mb-0 text-primary">{{ __('Generated Result') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        @if(($item->content_type ?? 'text') === 'image' && $item->generated_media_path)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $item->generated_media_path) }}" alt="" class="img-fluid rounded-4 shadow-sm border mb-3" style="max-height: 550px;">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ asset('storage/' . $item->generated_media_path) }}" target="_blank" class="primary-btn btn-sm">
                                    <i class="fa-solid fa-up-right-from-square me-1"></i> {{ __('Open Original') }}
                                </a>
                                <a href="{{ asset('storage/' . $item->generated_media_path) }}" download class="primary-btn btn-sm">
                                    <i class="fa-solid fa-download me-1"></i> {{ __('Download') }}
                                </a>
                            </div>
                        </div>
                        @elseif(($item->content_type ?? 'text') === 'video' && $item->generated_media_path)
                        <div class="text-center">
                            <video controls class="w-100 rounded-4 shadow-sm border mb-3" style="max-height: 500px;" src="{{ asset('storage/' . $item->generated_media_path) }}"></video>
                            <a href="{{ asset('storage/' . $item->generated_media_path) }}" download class="primary-btn btn-sm">
                                <i class="fa-solid fa-download me-1"></i> {{ __('Download Video') }}
                            </a>
                        </div>
                        @else
                        <div class="position-relative">
                            <textarea id="copyText" class="form-control border-0 bg-light rounded-4 p-4" rows="14" readonly style="line-height: 1.6; font-size: 1.05rem;">{{ $item->generated_text ?? '' }}</textarea>
                            <button onclick="copyToClipboard()" class="btn btn-primary btn-sm position-absolute top-0 end-0 mt-3 me-3 rounded-pill px-3 shadow-sm">
                                <i class="fa-regular fa-copy me-1"></i> {{ __('Copy Content') }}
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-muted text-uppercase small mb-3">{{ __('Metadata') }}</h6>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">{{ __('Content Type') }}</small>
                            <span class="badge bg-{{ $item->content_type === 'image' ? 'info' : ($item->content_type === 'video' ? 'secondary' : 'primary') }} rounded-pill px-3 mt-1">
                                {{ ucfirst($item->content_type ?? 'text') }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">{{ __('AI Model') }}</small>
                            <div class="fw-medium mt-1"><i class="fa-solid fa-microchip me-1 text-primary"></i> {{ $item->model ?? '—' }}</div>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">{{ __('Generated Date') }}</small>
                            <div class="fw-medium mt-1"><i class="fa-regular fa-calendar-check me-1 text-primary"></i> {{ $item->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small"><i class="fa-regular fa-clock me-1"></i> {{ $item->created_at->format('H:i A') }}</div>
                        </div>

                        <hr class="text-muted my-4 opacity-10">

                        <div class="mb-0">
                            <small class="text-muted d-block mb-2">{{ __('Original Prompt') }}</small>
                            <div class="p-3 bg-light rounded-3 small text-italic border-start border-4 border-primary">
                                " {{ $item->prompt }} "
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
    function copyToClipboard() {
        var copyText = document.getElementById("copyText");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        if(typeof toastr !== 'undefined') toastr.success("{{ __('Copied to clipboard!') }}");
    }
</script>
@endpush
@endsection
