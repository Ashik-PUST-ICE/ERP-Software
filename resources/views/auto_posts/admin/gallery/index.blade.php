@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Manage Image Library') }}
@endpush

@section('content')

<div>
    <div class="section-title">
        <h2 class="title">{{ __('Manage Image Library') }}</h2>
        <a href="#" class="primary-btn" data-bs-toggle="modal" data-bs-target="#UploadGalleryModal">+
            {{ __('Upload') }}</a>
    </div>
    <div class="section-wrap">
        <div class="table-waraper">
            <div class="search-input-wrap">
                <form action="{{ route('admin.gallery.index') }}" method="GET" id="gallerySearchForm">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData" name="search"
                        placeholder="{{ __('Search By Title...') }}" value="{{ request()->get('search') }}" />
                </form>
            </div>

            <div class="row gy-4">
                @forelse($galleries as $gallery)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image">
                            <div class="dropdown options-area">
                                <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis"></i>
                                </a>
                                <ul class="dropdown-menu {{ selectedLanguage()->rtl == 1 ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#EditGalleryModal{{ $gallery->id }}">{{ __('Edit') }}</a>
                                    </li>
                                    <li><a class="dropdown-item delete-item" href="#"
                                            data-route="{{ route('admin.gallery.destroy', $gallery->id) }}">{{ __('Delete') }}</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="image-wrapper">
                                <img class="logo" src="{{ asset('storage/' . $gallery->file_path) }}"
                                    alt="{{ $gallery->title ?? $gallery->file_name }}">
                                @if($gallery->title)
                                    <div class="image-title-overlay">
                                        <h4>{{ $gallery->title }}</h4>
                                    </div>
                                @endif
                            </div>
                            <div class="image-info">
                                @if($gallery->dimensions)
                                    <h5 class="image-size">{{ $gallery->dimensions }}</h5>
                                @endif
                                <h5 class="image-format">{{ $gallery->file_name }}</h5>
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
                                            stroke="#E5E7EB" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M20 24L28 32L44 16" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 class="empty-state-title">{{ __('No Images Found') }}</h3>
                                <p class="empty-state-text">{{ __('Upload your first image to get started!') }}</p>
                                <a href="#" class="primary-btn" data-bs-toggle="modal" data-bs-target="#UploadGalleryModal">
                                    {{ __('Upload First Image') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            @foreach($galleries as $gallery)
            <div class="modal fade primary-modal" id="EditGalleryModal{{ $gallery->id }}" tabindex="-1"
                aria-labelledby="EditGalleryModalLabel{{ $gallery->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2 class="modal-title" id="EditGalleryModalLabel{{ $gallery->id }}">
                                {{ __('Edit Image') }}</h2>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.gallery.update', $gallery->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="primary-form">
                                    <div class="row gy-4">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Title') }}</label>
                                                <input type="text" class="form-control" name="title"
                                                    value="{{ $gallery->title }}"
                                                    placeholder="{{ __('Enter image title') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="platform" class="form-label">{{ __('Platform') }}
                                                    <span class="required">*</span></label>
                                                <select class="form-control select wide sf-select-without-search" id="platform" name="platform" required>
                                                    <option value="">{{ __('Select Platform') }}</option>
                                                    @foreach(SOCIAL_MEDIA_PLATFORMS as $key => $platform)
                                                    <option value="{{ $key }}"
                                                        {{ $gallery->platform == $key ? 'selected' : '' }}>
                                                        {{ __($platform) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label
                                                    class="form-label">{{ __('Change Image (JPG, JPEG, PNG)') }}</label>
                                                <div class="file-upload">
                                                    <input type="file" class="file-input" id="file{{ $gallery->id }}"
                                                        name="file">
                                                    <label for="file{{ $gallery->id }}" class="file-input-label">
                                                        <span class="file-text">{{ $gallery->file_name }}</span>
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
                                <button type="submit" class="primary-btn">{{ __('Update Image') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach

            @if($galleries->total() > $galleries->perPage())
                @include('auto_posts.super_admin.pagination.common-pagination', [
                    'total' => $galleries->total(),
                    'perPage' => $galleries->perPage(),
                    'page' => $galleries->currentPage(),
                    'paginationUrl' => route('admin.gallery.index')
                ])
            @endif

        </div>
    </div>
</div>

<!-- Upload Gallery Modal -->
<div class="modal fade primary-modal" id="UploadGalleryModal" tabindex="-1" aria-labelledby="UploadGalleryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="UploadGalleryModalLabel">{{ __('Upload Image') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="primary-form">
                        <div class="row gy-4">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="{{ __('Enter image title') }}">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="platform" class="form-label">{{ __('Platform') }} <span
                                            class="required">*</span></label>
                                    <select class="form-control select wide sf-select-without-search" id="platform" name="platform" required>
                                        <option value="">{{ __('Select Platform') }}</option>
                                        @foreach(SOCIAL_MEDIA_PLATFORMS as $key => $platform)
                                        <option value="{{ $key }}">{{ __($platform) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Add Attachments (JPG, JPEG, PNG)') }}<span
                                            class="required">*</span></label>
                                    <div class="file-upload">
                                        <input type="file" class="file-input" id="Attachments" name="file"
                                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required>
                                        <label for="Attachments" class="file-input-label">
                                            <span class="file-text">{{ __('Choose image to upload') }}</span>
                                            <span class="file-btn">{{ __('Browse File') }}</span>
                                        </label>
                                    </div>
                                    <span class="recommended-text">{{ __('Recommended: 800 PX/400 PX') }}</span>
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
</div>
</div>
</div>


@endsection

@push('script')
<script src="{{ asset('admin/js/gallery.js') }}"></script>
@endpush