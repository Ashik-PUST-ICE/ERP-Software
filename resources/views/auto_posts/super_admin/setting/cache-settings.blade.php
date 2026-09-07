@extends('auto_posts.super_admin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ $title }}</h2>
</div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title">{{ __($title) }}</h3>
            </div>
            <div class="primary-form">
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <h4 class="cache-title">{{ __('Clear View Cache') }}</h4>
                            <p class="cache-description">{{ __('Clear all compiled view files') }}</p>
                            <a href="{{ route('super_admin.setting.cache-update', 1) }}" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i>{{ __('Clear Cache') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-route"></i>
                            </div>
                            <h4 class="cache-title">{{ __('Clear Route Cache') }}</h4>
                            <p class="cache-description">{{ __('Clear all route cache files') }}</p>
                            <a href="{{ route('super_admin.setting.cache-update', 2) }}" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i>{{ __('Clear Cache') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-gear"></i>
                            </div>
                            <h4 class="cache-title">{{ __('Clear Config Cache') }}</h4>
                            <p class="cache-description">{{ __('Clear all configuration cache files') }}</p>
                            <a href="{{ route('super_admin.setting.cache-update', 3) }}" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i>{{ __('Clear Cache') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-broom"></i>
                            </div>
                            <h4 class="cache-title">{{ __('Application Clear Cache') }}</h4>
                            <p class="cache-description">{{ __('Clear all application cache files') }}</p>
                            <a href="{{ route('super_admin.setting.cache-update', 4) }}" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-trash-can me-2"></i>{{ __('Clear Cache') }}
                            </a>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="cache-action-card">
                            <div class="cache-icon">
                                <i class="fa-solid fa-link"></i>
                            </div>
                            <h4 class="cache-title">{{ __('Storage Link') }}</h4>
                            <p class="cache-description">{{ __('Create symbolic link for storage') }}</p>
                            <a href="{{ route('super_admin.setting.cache-update', 5) }}" 
                               class="primary-btn cache-action-btn">
                                <i class="fa-solid fa-link me-2"></i>{{ __('Create Link') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/cache-settings.css') }}">
@endpush



