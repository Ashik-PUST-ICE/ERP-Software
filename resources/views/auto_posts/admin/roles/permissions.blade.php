@extends('auto_posts.admin.layouts.admin')
@push('admin-style')
<link rel="stylesheet" href="{{ asset('admin/styles/main.css') }}">
@endpush
@push('title')
{{$title}}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
</div>
<div class="settings-page-area">
    <div class="settings-page-left">
        <nav class="settings-menu">
            <ul>
                @foreach($permissions as $module => $modulePermissions)
                <li>
                    <a href="javascript:void(0)"
                        class="fs-15 border-0 w-100 text-left fw-500 lh-25 text-black py-10 px-26 bg-cdef84 bd-ra-12 hover-bg-one module-trigger {{ $loop->first ? 'active' : '' }}"
                        data-module="{{ $module }}">
                        {{ moduleName($module) }} <i class="fa-solid fa-angle-right"></i>
                    </a>
                </li>
                @endforeach
            </ul>
        </nav>
    </div>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">{{ $role->display_name }} - {{ __('User Permissions') }}</h3>
                    <a href="{{route('admin.roles.index')}}" class="primary-btn btn-secondary">
                        <i class="fa fa-arrow-left me-2"></i>{{ __('Back') }}
                    </a>
                </div>
            </div>

            <form data-handler="commonResponse" action="{{route('admin.roles.update.permissions', [$role->id])}}"
                method="POST" class="ajax">
                @method('put')
                @csrf
                <div class="row gy-4">
                    <!-- Permissions Content -->
                    <div class="col-lg-12">
                        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                            <!-- Search Box -->
                            <div class="search-input-wrap mb-3">
                                <label class="icon" for="permission-search">
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
                                <input type="text" class="search-input" id="permission-search"
                                    placeholder="{{ __('Search permissions...') }}">
                            </div>

                            @foreach($permissions as $module => $modulePermissions)
                            <div class="module-content {{ $loop->first ? '' : 'd-none' }}" data-module="{{ $module }}">
                                <h5 class="fs-18 fw-600 text-black mb-20">{{ moduleName($module) }}
                                    {{ __('Permissions') }}</h5>
                                <div class="row">
                                    @foreach($modulePermissions as $permission)
                                    <div class="col-md-6 mb-3 permission-item">
                                        <div class="form-check">
                                            <input {{ in_array($permission->id, $oldPermissions) ? 'checked' : '' }}
                                                class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $permission->name }}" id="permission-{{ $permission->id }}">
                                            <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                {{ $permission->display_name }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="btn-list mt-4 pt-3 border-top">
                    <button type="submit" class="primary-btn">
                        {{__('Save Permissions')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
$(document).ready(function() {
    // Module switching
    $('.module-trigger').on('click', function(e) {
        e.preventDefault();
        const module = $(this).data('module');

        // Update active button styling
        $('.module-trigger').removeClass('active');
        $(this).addClass('active');

        // Show corresponding content
        $('.module-content').addClass('d-none');
        $(`.module-content[data-module="${module}"]`).removeClass('d-none');

        // Clear search when switching modules
        $('#permission-search').val('');
        $('.permission-item').show();
    });

    // Permission search functionality
    $('#permission-search').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        const activeModule = $('.module-trigger.active').data('module');

        $('.permission-item').each(function() {
            const permissionText = $(this).find('.form-check-label').text().toLowerCase();
            const permissionModule = $(this).closest('.module-content').data('module');

            if (permissionModule === activeModule && permissionText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
</script>
@endpush