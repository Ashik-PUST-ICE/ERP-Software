@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Manage Video Library') }}
@endpush

@section('content')
<div class="p-30">
    <div>
        <div class="section-title">
            <h2 class="title">{{ __('Manage Video Library') }}</h2>
            <a href="#" class="primary-btn" data-bs-toggle="modal" data-bs-target="#UploadVideoModal">+
                {{ __('Upload') }}</a>
        </div>
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap">
                    <form action="{{ route('admin.video-gallery.index') }}" method="GET" id="videoSearchForm">
                        <label class="icon" for="searchData">
                            <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                    stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                    stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </label>
                        <input type="text" class="search-input" id="searchData" name="search"
                            placeholder="{{ __('Search By Title...') }}" value="{{ request()->get('search') }}" />
                    </form>
                </div>

                <div class="row gy-4">
                    @forelse($videos as $video)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#EditVideoModal{{ $video->id }}">{{ __('Edit') }}</a>
                                    </li>
                                    <li><a class="dropdown-item delete-item" href="#"
                                            data-route="{{ route('admin.video-gallery.destroy', $video->id) }}">{{ __('Delete') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="image-wrapper">
                                <video controls preload="metadata" class="logo">
                                    <source src="{{ asset('storage/' . $video->file_path) }}"
                                        type="{{ $video->file_type }}">
                                    Your browser does not support the video tag.
                                </video>
                                @if($video->title)
                                <div class="image-title-overlay">
                                    <h4>{{ $video->title }}</h4>
                                </div>
                                @endif
                            </div>
                            <div class="image-info">
                                @if($video->duration)
                                <h5 class="image-size">{{ $video->duration }}s</h5>
                                @endif
                                <h5 class="image-format">{{ $video->file_name }}</h5>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="section-wrap">
                            <div class="empty-state text-center">
                                <div class="empty-state-icon">
                                    <svg width="64" height="64" viewBox="0 0 64 64" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M32 58C45.2548 58 56 47.2548 56 34C56 20.7452 45.2548 10 32 10C18.7452 10 8 20.7452 8 34C8 47.2548 18.7452 58 32 58Z"
                                            stroke="#E5E7EB" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M20 24L28 32L44 16" stroke="#9CA3AF" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="empty-state-title">{{ __('No Videos Found') }}</h3>
                                <p class="empty-state-text">{{ __('Upload your first video to get started!') }}</p>
                                <a href="#" class="primary-btn" data-bs-toggle="modal"
                                    data-bs-target="#UploadVideoModal">
                                    {{ __('Upload First Video') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforelse

                    @if($videos->total() > $videos->perPage())
                        @include('auto_posts.super_admin.pagination.common-pagination', [
                            'total' => $videos->total(),
                            'perPage' => $videos->perPage(),
                            'page' => $videos->currentPage(),
                            'paginationUrl' => route('admin.video-gallery.index')
                        ])
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach($videos as $video)
    <div class="modal fade primary-modal" id="EditVideoModal{{ $video->id }}" tabindex="-1"
        aria-labelledby="EditVideoModalLabel{{ $video->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="EditVideoModalLabel{{ $video->id }}">
                        {{ __('Edit Video') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.video-gallery.update', $video->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="primary-form">
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" name="title" value="{{ $video->title }}"
                                            placeholder="{{ __('Enter video title') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="platform" class="form-label">{{ __('Platform') }} <span
                                                class="required">*</span></label>
                                        <select class="form-control" id="platform" name="platform" required>
                                            <option value="">{{ __('Select Platform') }}</option>
                                            @foreach(SOCIAL_MEDIA_PLATFORMS as $key => $platform)
                                            <option value="{{ $key }}" {{ $video->platform == $key ? 'selected' : '' }}>
                                                {{ __($platform) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label
                                            class="form-label">{{ __('Change Video (MP4, AVI, MOV, WMV, FLV)') }}</label>
                                        <div class="file-upload">
                                            <input type="file" class="file-input" id="video{{ $video->id }}"
                                                name="file">
                                            <label for="video{{ $video->id }}" class="file-input-label">
                                                <span class="file-text">{{ $video->file_name }}</span>
                                                <span class="file-btn">{{ __('Browse File') }}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="primary-btn btn-outline"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Update Video') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Upload Video Modal -->
    <div class="modal fade primary-modal" id="UploadVideoModal" tabindex="-1" aria-labelledby="UploadVideoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="UploadVideoModalLabel">{{ __('Upload Video') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.video-gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="primary-form">
                            <div class="row gy-4">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Title') }}</label>
                                        <input type="text" class="form-control" name="title"
                                            placeholder="{{ __('Enter video title') }}">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="platform" class="form-label">{{ __('Platform') }} <span
                                                class="required">*</span></label>
                                        <select class="form-control" id="platform" name="platform" required>
                                            <option value="">{{ __('Select Platform') }}</option>
                                            @foreach(SOCIAL_MEDIA_PLATFORMS as $key => $platform)
                                            <option value="{{ $key }}">{{ __($platform) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Add Video (MP4, AVI, MOV, WMV, FLV)') }}<span
                                                class="required">*</span></label>
                                        <div class="file-upload">
                                            <input type="file" class="file-input" id="VideoFile" name="file"
                                                accept="video/mp4,video/avi,video/mov,video/wmv,video/flv,video/webm"
                                                required>
                                            <label for="VideoFile" class="file-input-label">
                                                <span class="file-text">{{ __('Choose video to upload') }}</span>
                                                <span class="file-btn">{{ __('Browse File') }}</span>
                                            </label>
                                        </div>
                                        <span class="recommended-text">{{ __('Max file size: 100 MB') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="primary-btn btn-outline"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Upload') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endsection

    @push('script')
    <script src="{{ asset('admin/js/video-gallery.js') }}"></script>
    @endpush