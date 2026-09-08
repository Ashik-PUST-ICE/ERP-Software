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
                <form method="post" action="{{ route('admin.garments.merchandiser.assign') }}">
                    @csrf
                    <select name="order_id" class="form-control mb-2" required>
                        <option value="">{{ __('Order') }}</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                        @endforeach
                    </select>
                    <select name="user_ids[]" class="form-control mb-2" multiple required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <select name="primary_user_id" class="form-control mb-2">
                        <option value="">{{ __('Primary merchandiser') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <button class="primary-btn">{{ __('Assign') }}</button>
                </form>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="section-wrap p-4">
                <h4>{{ __('Create Task') }}</h4>
                <form method="post" action="{{ route('admin.garments.merchandiser.task') }}">
                    @csrf
                    <select name="order_id" class="form-control mb-2" required>
                        <option value="">{{ __('Order') }}</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                        @endforeach
                    </select>
                    <select name="user_id" class="form-control mb-2" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <input name="title" class="form-control mb-2" placeholder="{{ __('Task title') }}" required>
                    <input type="date" name="due_date" class="form-control mb-2">
                    <textarea name="notes" class="form-control mb-2" placeholder="{{ __('Notes') }}"></textarea>
                    <input type="hidden" name="priority" value="2">
                    <button class="primary-btn">{{ __('Create Task') }}</button>
                </form>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="section-wrap p-4">
                <h4>{{ __('Buyer Communication') }}</h4>
                <form method="post" action="{{ route('admin.garments.merchandiser.communication') }}">
                    @csrf
                    <select name="order_id" class="form-control mb-2" required>
                        <option value="">{{ __('Order') }}</option>
                        @foreach($orders as $order)
                            <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                        @endforeach
                    </select>
                    <select name="channel" class="form-control mb-2">
                        @foreach(['email', 'phone', 'meeting', 'whatsapp'] as $channel)
                            <option value="{{ $channel }}">{{ ucfirst($channel) }}</option>
                        @endforeach
                    </select>
                    <input type="datetime-local" name="communicated_at" class="form-control mb-2" required>
                    <input name="subject" class="form-control mb-2" placeholder="{{ __('Subject') }}">
                    <textarea name="notes" class="form-control mb-2" placeholder="{{ __('Communication notes') }}" required></textarea>
                    <button class="primary-btn">{{ __('Save Log') }}</button>
                </form>
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
