@extends('auto_posts.admin.layouts.admin')

@push('title')
    {{ $title }}
@endpush

@section('content')
    <div class="section-title">
        <h2 class="title">{{ __($title) }}</h2>
    </div>

    <div class="row g-4">
        <div class="col-xl-4">
            <div class="section-wrap p-4">
                <h4>{{ __('Assign Merchandisers') }}</h4>
                <p class="text-muted mb-3">{{ __('Assign primary and team merchandisers to a garment order on a dedicated page.') }}</p>
                <a href="{{ route('admin.garments.merchandiser.assign.create') }}" class="primary-btn d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-gear"></i>{{ __('Assign') }}
                </a>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="section-wrap p-4">
                <h4>{{ __('Create Task') }}</h4>
                <p class="text-muted mb-3">{{ __('Delegate a follow-up task to a merchandiser on a dedicated page.') }}</p>
                <a href="{{ route('admin.garments.merchandiser.task.create') }}" class="primary-btn d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-check"></i>{{ __('Create Task') }}
                </a>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="section-wrap p-4">
                <h4>{{ __('Buyer Communication') }}</h4>
                <p class="text-muted mb-3">{{ __('Log buyer communication entries (email, phone, meeting, WhatsApp) on a dedicated page.') }}</p>
                <a href="{{ route('admin.garments.merchandiser.communication.create') }}" class="primary-btn d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-comments"></i>{{ __('Log Entry') }}
                </a>
            </div>
        </div>
    </div>

    <div class="section-wrap p-4 mt-4">
        <h4>{{ __('Recent Tasks & Communication') }}</h4>
        @forelse($tasks as $task)
            <div class="border-bottom py-2">
                <strong>{{ $task->title }}</strong> - {{ $task->order?->order_number }}
                <span class="text-muted">{{ $task->due_date?->format('d M Y') }}</span>
            </div>
        @empty
            <p class="text-muted">{{ __('No tasks found') }}</p>
        @endforelse

        @forelse($communications as $communication)
            <div class="border-bottom py-2">
                <strong>{{ ucfirst($communication->channel) }}</strong> -
                {{ $communication->order?->order_number }}:
                {{ $communication->subject ?: $communication->notes }}
            </div>
        @empty
            <p class="text-muted">{{ __('No communications found') }}</p>
        @endforelse
    </div>
@endsection
