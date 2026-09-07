@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Publish Posts') }}
@endpush

@section('content')



{{-- Stat Cards --}}
<div class="row gy-4">
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02" />
                    <path d="M13 19L16.5 22.5L25 14" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
            <div class="card-info">
                <h2 id="stat-pending">{{ number_format($pendingPosts) }}</h2>
                <h3>{{ __('Ready to Publish') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FFC402" />
                    <path d="M19 13V19L22.5 22.5" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <circle cx="19" cy="19" r="7" stroke="white" stroke-width="1.5" />
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($pendingPosts) }}</h2>
                <h3>{{ __('Pending Posts') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#10A958" />
                    <path
                        d="M14.3333 20.6665C14.3333 20.6665 15.3333 20.6665 16.6666 22.9998C16.6666 22.9998 20.3725 16.8887 23.6666 15.6665"
                        stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <div class="card-info">
                <h2 id="stat-posted-today">{{ number_format($postedToday) }}</h2>
                <h3>{{ __('Posted Today') }}</h3>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0D0D0D" />
                    <path d="M19 14V20M19 24V24.01" stroke="white" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <circle cx="19" cy="19" r="7" stroke="white" stroke-width="1.5" />
                </svg>
            </span>
            <div class="card-info">
                <h2>{{ number_format($failedPosts) }}</h2>
                <h3>{{ __('Failed Posts') }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Main Content --}}
<div class="row gy-4 mt-2">

    {{-- Left: Publish Action --}}
    <div class="col-xl-8">
        <div class="section-wrap">
            <div class="section-small-title">
                <h3 class="title">{{ __('Manual Post Publishing') }}</h3>
            </div>

            <div class="info-box mb-20"
                style="background:#fff8f5;border:1px solid #ffe0d3;border-radius:8px;padding:16px 20px;">
                <div class="d-flex align-items-start gap-3">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="flex-shrink:0;margin-top:2px">
                        <circle cx="10" cy="10" r="9" stroke="#FF4F02" stroke-width="1.5" />
                        <path d="M10 6V10.5M10 13.5V14" stroke="#FF4F02" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <div>
                        <strong style="color:#FF4F02;">{{ __('About Automatic Publishing') }}</strong>
                        <p class="mb-0 mt-1" style="color:#6e5858;font-size:13px;">
                            {{ __('Use the button below to manually publish all pending posts immediately, regardless of their scheduled time. Posts are also published automatically by the cron job when their scheduled time arrives.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Alert area --}}
            <div id="publish-alert-area"></div>

            {{-- Publish Button --}}
            <div class="text-center py-30" style="padding:30px 0;">
                <form action="{{ route('admin.publish-posts.publish') }}" method="POST" id="publish-form"
                    data-publishing-label="{{ __('Publishing…') }}"
                    data-button-label="{{ __('Publish All Pending Posts Now') }}"
                    data-error-message="{{ __('An error occurred while publishing posts.') }}">
                    @csrf
                    <button type="submit" class="primary-btn px-5" id="publish-btn"
                        style="font-size:15px;padding:12px 40px;display:inline-flex;align-items:center;gap:8px;">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="9" cy="9" r="8" stroke="currentColor" stroke-width="1.5" />
                            <path d="M7 6L12 9L7 12V6Z" fill="currentColor" />
                        </svg>
                        {{ __('Publish All Pending Posts Now') }}
                    </button>
                </form>

                @if($pendingPosts > 0)
                <p class="mt-3" style="color:#FF4F02;font-size:13px;font-weight:500;">
                    {{ $pendingPosts }} {{ __('pending post(s) will be published') }}
                </p>
                @else
                <p class="mt-3" style="color:#808080;font-size:13px;">
                    {{ __('No pending posts found') }}
                </p>
                @endif
            </div>

            {{-- Result Statistics --}}
            <div id="publish-results" style="display:none;">
                <hr style="border-color:#ece3e1;margin:20px 0;">
                <h4 style="font-size:14px;font-weight:600;color:#0d0d0d;margin-bottom:16px;">
                    {{ __('Last Publish Results') }}</h4>
                <div class="row gy-3 text-center">
                    <div class="col-md-4">
                        <div style="background:#f9f6f5;border-radius:8px;padding:16px;">
                            <h3 id="result-processed" style="font-size:28px;font-weight:700;color:#0d0d0d;margin:0;">0
                            </h3>
                            <p style="margin:4px 0 0;color:#808080;font-size:13px;">{{ __('Processed') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:#f0faf5;border-radius:8px;padding:16px;">
                            <h3 id="result-successful" style="font-size:28px;font-weight:700;color:#10A958;margin:0;">0
                            </h3>
                            <p style="margin:4px 0 0;color:#808080;font-size:13px;">{{ __('Successful') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="background:#fff5f5;border-radius:8px;padding:16px;">
                            <h3 id="result-failed" style="font-size:28px;font-weight:700;color:#e53535;margin:0;">0</h3>
                            <p style="margin:4px 0 0;color:#808080;font-size:13px;">{{ __('Failed') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Right: Quick Actions + System Status --}}
    <div class="col-xl-4">
        <div class="section-wrap mb-20">
            <div class="section-small-title">
                <h3 class="title">{{ __('Quick Actions') }}</h3>
            </div>
            <div class="d-flex flex-column gap-2">
                <a href="{{ route('admin.all-posts.index') }}" class="primary-btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 4h12M2 8h12M2 12h7" stroke="currentColor" stroke-width="1.3"
                            stroke-linecap="round" />
                    </svg>
                    {{ __('View All Posts') }}
                </a>
                <a href="{{ route('admin.all-posts.create') }}" class="primary-btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 2v12M2 8h12" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    {{ __('Create New Post') }}
                </a>
                <a href="{{ route('admin.social.account.index') }}" class="primary-btn">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="6" cy="5" r="2.5" stroke="currentColor" stroke-width="1.3" />
                        <path d="M2 13c0-2.21 1.79-4 4-4s4 1.79 4 4" stroke="currentColor" stroke-width="1.3"
                            stroke-linecap="round" />
                        <path d="M12 7v4M10 9h4" stroke="currentColor" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    {{ __('Manage Accounts') }}
                </a>
            </div>
        </div>

        <div class="section-wrap">
            <div class="section-small-title">
                <h3 class="title">{{ __('System Status') }}</h3>
            </div>
            <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:14px;">
                <li style="display:flex;align-items:flex-start;gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="flex-shrink:0;margin-top:1px">
                        <circle cx="9" cy="9" r="7.5" stroke="#FF4F02" stroke-width="1.3" />
                        <path d="M9 5.5V9L11.5 11" stroke="#FF4F02" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    <div>
                        <strong style="font-size:12px;color:#0d0d0d;">{{ __('Current Time') }}</strong>
                        <p style="margin:2px 0 0;font-size:12px;color:#808080;">{{ now()->format('Y-m-d H:i:s T') }}</p>
                    </div>
                </li>
                <li style="display:flex;align-items:flex-start;gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="flex-shrink:0;margin-top:1px">
                        <rect x="2" y="4" width="14" height="10" rx="2" stroke="#10A958" stroke-width="1.3" />
                        <path d="M6 8h6M6 11h4" stroke="#10A958" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    <div>
                        <strong style="font-size:12px;color:#0d0d0d;">{{ __('Cron Status') }}</strong>
                        <p style="margin:2px 0 0;font-size:12px;color:#808080;">
                            {{ __('Automatic publishing runs every minute') }}</p>
                    </div>
                </li>
                <li style="display:flex;align-items:flex-start;gap:10px;">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"
                        style="flex-shrink:0;margin-top:1px">
                        <path d="M3 9a6 6 0 1 0 12 0A6 6 0 0 0 3 9Z" stroke="#FFC402" stroke-width="1.3" />
                        <path d="M9 6v3.5L11.5 11" stroke="#FFC402" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    <div>
                        <strong style="font-size:12px;color:#0d0d0d;">{{ __('Next Auto Run') }}</strong>
                        <p style="margin:2px 0 0;font-size:12px;color:#808080;">
                            {{ now()->addMinute()->format('Y-m-d H:i:s T') }}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

</div>

@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('admin/css/publish-posts.css') }}">
@endpush
@push('script')
<script src="{{ asset('admin/js/publish-posts.js') }}"></script>
@endpush