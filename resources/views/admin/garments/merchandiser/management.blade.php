@extends('auto_posts.admin.layouts.admin')

@push('title')
    {{ $title }}
@endpush

@push('style')
<style>
.mgmt-quick-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    border: 1px solid #e2e8f0;
    transition: transform .2s ease, box-shadow .2s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 100%;
}
.mgmt-quick-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
}
.mgmt-card-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.35rem;
    flex-shrink: 0;
}
</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="title">{{ __($title) }}</h2>
        <p class="text-muted mb-0" style="font-size:13.5px;">{{ __('Manage merchandiser assignments, task tracking, and buyer communication logs') }}</p>
    </div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <a href="{{ route('admin.garments.merchandiser.assign.create') }}" class="primary-btn d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-user-gear"></i>{{ __('Assign Merchandiser') }}
        </a>
        <a href="{{ route('admin.garments.merchandiser.task.create') }}" class="primary-btn d-inline-flex align-items-center gap-2" style="background:#f59e0b;border-color:#f59e0b;">
            <i class="fa-solid fa-list-check"></i>{{ __('Create Task') }}
        </a>
        <a href="{{ route('admin.garments.merchandiser.communication.create') }}" class="primary-btn d-inline-flex align-items-center gap-2" style="background:#10b981;border-color:#10b981;">
            <i class="fa-solid fa-comments"></i>{{ __('Log Communication') }}
        </a>
    </div>
</div>

{{-- 3 Quick-Action Direct Cards --}}
<div class="row g-4 mb-4">
    {{-- Card 1: Assign Merchandisers --}}
    <div class="col-xl-4 col-md-6">
        <div class="mgmt-quick-card" style="border-top: 4px solid #4778c7;">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="mgmt-card-icon" style="background:rgba(71,120,199,0.12);color:#4778c7;">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <span class="zBadge zBadge-primary">{{ __('Assignments') }}</span>
                </div>
                <h4 class="fw-700 mb-1" style="color:#0f172a;font-size:1.1rem;">{{ __('Order Assignments') }}</h4>
                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5;">
                    {{ __('Assign primary and team merchandisers to garment orders to streamline order execution.') }}
                </p>
            </div>
            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">{{ __('Total Active Orders:') }} <strong>{{ $orders->count() }}</strong></span>
                <a href="{{ route('admin.garments.merchandiser.assign.create') }}" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-arrow-right"></i>{{ __('Assign New') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Card 2: Create Tasks --}}
    <div class="col-xl-4 col-md-6">
        <div class="mgmt-quick-card" style="border-top: 4px solid #f59e0b;">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="mgmt-card-icon" style="background:rgba(245,158,11,0.12);color:#f59e0b;">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <span class="zBadge zBadge-warning">{{ __('Tasks') }}</span>
                </div>
                <h4 class="fw-700 mb-1" style="color:#0f172a;font-size:1.1rem;">{{ __('Merchandiser Tasks') }}</h4>
                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5;">
                    {{ __('Delegate follow-up tasks, trim card submissions, lab dip approvals, and set milestone deadlines.') }}
                </p>
            </div>
            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">{{ __('Recent Tasks:') }} <strong>{{ $tasks->count() }}</strong></span>
                <a href="{{ route('admin.garments.merchandiser.task.create') }}" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="background:#f59e0b;border-color:#f59e0b;font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-plus"></i>{{ __('Create Task') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Card 3: Buyer Communications --}}
    <div class="col-xl-4 col-md-12">
        <div class="mgmt-quick-card" style="border-top: 4px solid #10b981;">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="mgmt-card-icon" style="background:rgba(16,185,129,0.12);color:#10b981;">
                        <i class="fa-solid fa-comments"></i>
                    </div>
                    <span class="zBadge zBadge-complete">{{ __('Communications') }}</span>
                </div>
                <h4 class="fw-700 mb-1" style="color:#0f172a;font-size:1.1rem;">{{ __('Buyer Communication') }}</h4>
                <p class="text-muted mb-3" style="font-size:13px;line-height:1.5;">
                    {{ __('Log and document all buyer interactions across email, phone calls, meetings, and WhatsApp.') }}
                </p>
            </div>
            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                <span class="text-muted small">{{ __('Logged Entries:') }} <strong>{{ $communications->count() }}</strong></span>
                <a href="{{ route('admin.garments.merchandiser.communication.create') }}" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="background:#10b981;border-color:#10b981;font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-plus"></i>{{ __('Log Log Entry') }}
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Order Merchandiser Assignments Overview --}}
<div class="section-wrap p-4 mb-4">
    <div class="section-small-title d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h3 class="title">
            <i class="fa-solid fa-diagram-project text-primary me-2"></i>{{ __('Current Order Merchandiser Assignments') }}
        </h3>
        <a href="{{ route('admin.garments.merchandiser.assign.create') }}" class="primary-btn py-1 px-3 d-inline-flex align-items-center gap-2" style="font-size:12px;border-radius:6px;">
            <i class="fa-solid fa-plus"></i>{{ __('Assign Order') }}
        </a>
    </div>

    <div class="table-responsive">
        <table class="display primary-table w-100">
            <thead>
                <tr>
                    <th>{{ __('Order #') }}</th>
                    <th>{{ __('Buyer') }}</th>
                    <th>{{ __('Style') }}</th>
                    <th>{{ __('Primary Merchandiser') }}</th>
                    <th>{{ __('Assigned Team') }}</th>
                    <th class="text-end">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $primary = $order->merchandisers->firstWhere('pivot.is_primary', true);
                        $team = $order->merchandisers->where('pivot.is_primary', false);
                    @endphp
                    <tr>
                        <td>
                            <strong class="text-primary">{{ $order->order_number }}</strong>
                        </td>
                        <td>{{ $order->buyer?->company_name ?? 'N/A' }}</td>
                        <td>{{ $order->style ? $order->style->style_code : 'N/A' }}</td>
                        <td>
                            @if($primary)
                                <span class="d-inline-flex align-items-center gap-1 fw-600 text-dark">
                                    <i class="fa-solid fa-star text-warning me-1"></i>{{ $primary->name }}
                                </span>
                            @else
                                <span class="text-muted small">{{ __('Not designated') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($order->merchandisers->count() > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($order->merchandisers as $m)
                                        <span class="zBadge {{ $m->pivot->is_primary ? 'zBadge-primary' : 'zBadge-outline' }}" style="font-size:11px;">
                                            {{ $m->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">{{ __('No merchandisers assigned') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.garments.merchandiser.assign.create', ['order_id' => $order->id]) }}" class="primary-btn-outline py-1 px-3 d-inline-flex align-items-center gap-1" style="font-size:12px;border-radius:6px;">
                                <i class="fa-solid fa-pen-to-square"></i>{{ __('Manage') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fa-regular fa-folder-open fa-2x mb-2 d-block"></i>
                            {{ __('No order assignments found.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Two-Column Recent Activity Section --}}
<div class="row g-4">
    {{-- Recent Tasks --}}
    <div class="col-xl-6">
        <div class="section-wrap p-4 h-100">
            <div class="section-small-title d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h3 class="title">
                    <i class="fa-solid fa-bars-progress text-warning me-2"></i>{{ __('Recent Tasks') }}
                </h3>
                <a href="{{ route('admin.garments.merchandiser.task.create') }}" class="text-warning fw-600" style="font-size:12px;">
                    <i class="fa-solid fa-plus me-1"></i>{{ __('New Task') }}
                </a>
            </div>

            @forelse($tasks as $task)
                <div class="d-flex align-items-start gap-3 py-3" style="border-bottom:1px solid #f1f5f9;">
                    <div style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="fw-600" style="color:#0f172a;font-size:13.5px;">{{ $task->title }}</div>
                        <div class="d-flex gap-3 flex-wrap mt-1" style="font-size:12px;color:#64748b;">
                            <span><i class="fa-solid fa-hashtag me-1"></i>{{ $task->order?->order_number ?? 'N/A' }}</span>
                            @if($task->due_date)
                                <span><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($task->due_date)->format('d M Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        @if($task->status == 3)
                            <span class="zBadge zBadge-complete">{{ __('Done') }}</span>
                        @elseif($task->status == 2)
                            <span class="zBadge zBadge-primary">{{ __('Active') }}</span>
                        @else
                            <span class="zBadge zBadge-warning">{{ __('Pending') }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fa-regular fa-folder-open fa-2x mb-2 d-block"></i>
                    {{ __('No recent tasks') }}
                </div>
            @endforelse
        </div>
    </div>

    {{-- Recent Communications --}}
    <div class="col-xl-6">
        <div class="section-wrap p-4 h-100">
            <div class="section-small-title d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h3 class="title">
                    <i class="fa-solid fa-comments text-success me-2"></i>{{ __('Recent Communications') }}
                </h3>
                <a href="{{ route('admin.garments.merchandiser.communication.create') }}" class="text-success fw-600" style="font-size:12px;">
                    <i class="fa-solid fa-plus me-1"></i>{{ __('Log New') }}
                </a>
            </div>

            @forelse($communications as $comm)
                @php
                    $channelIcons  = ['email'=>'fa-envelope','phone'=>'fa-phone','meeting'=>'fa-handshake','whatsapp'=>'fa-whatsapp'];
                    $channelColors = ['email'=>'#4778c7','phone'=>'#10b981','meeting'=>'#f59e0b','whatsapp'=>'#25d366'];
                    $icon  = $channelIcons[$comm->channel] ?? 'fa-comments';
                    $color = $channelColors[$comm->channel] ?? '#64748b';
                @endphp
                <div class="d-flex align-items-start gap-3 py-3" style="border-bottom:1px solid #f1f5f9;">
                    <div style="width:36px;height:36px;border-radius:10px;background:{{ $color }}1a;color:{{ $color }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <div style="flex:1;">
                        <div class="fw-600" style="color:#0f172a;font-size:13.5px;">
                            {{ ucfirst($comm->channel) }}@if($comm->subject) &mdash; {{ $comm->subject }}@endif
                        </div>
                        <div class="d-flex gap-3 flex-wrap mt-1" style="font-size:12px;color:#64748b;">
                            <span><i class="fa-solid fa-hashtag me-1"></i>{{ $comm->order?->order_number ?? 'N/A' }}</span>
                            @if($comm->communicated_at)
                                <span><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($comm->communicated_at)->format('d M Y, H:i') }}</span>
                            @endif
                        </div>
                        @if($comm->notes)
                            <p class="text-muted mb-0 mt-1" style="font-size:12px;line-height:1.45;">{{ Str::limit($comm->notes, 90) }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">
                    <i class="fa-regular fa-folder-open fa-2x mb-2 d-block"></i>
                    {{ __('No recent communications') }}
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
