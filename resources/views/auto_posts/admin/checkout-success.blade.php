@extends('auto_posts.admin.layouts.admin')
@push('title')
{{ __($title) }}
@endpush
@section('content')

<div class="text-center biling-submit-response-page">
    @if($success == true)
    <div class="d-flex justify-content-center pb-30">
        <img src="{{ asset('assets/images/successful-message.png')}}" alt="" />
    </div>
    <h4 class="title">{{ __('Successful') }}</h4>
    <p class="subtitle">{{ $message }}</p>
    @else
    <div class="d-flex justify-content-center pb-30 mt-20 mb-20">
        <img src="{{ asset('assets/images/failed-message.png')}}" alt="" />
    </div>
    <h4 class="title">{{ __('Failed') }}</h4>
    <p class="subtitle">{{ $message }}</p>
    @endif
    <a href="{{ route('admin.billings.index') }}" class="primary-btn">
        {{ $success ? __('Back to Billing') : __('Back to Billing') }}
    </a>
</div>

@endsection