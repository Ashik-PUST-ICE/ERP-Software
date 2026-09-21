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

<input type="hidden" id="garment-merchandiser-management-data-url" value="{{ route('admin.garments.merchandiser.management.data') }}">

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
                <span class="text-muted small">{{ __('Total Active Orders:') }} <strong id="managementOrderCount">--</strong></span>
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
                <span class="text-muted small">{{ __('Recent Tasks:') }} <strong id="managementTaskCount">--</strong></span>
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
                <span class="text-muted small">{{ __('Logged Entries:') }} <strong id="managementCommunicationCount">--</strong></span>
                <a href="{{ route('admin.garments.merchandiser.communication.create') }}" class="primary-btn d-inline-flex align-items-center gap-2 py-2 px-3" style="background:#10b981;border-color:#10b981;font-size:12.5px;border-radius:8px;">
                    <i class="fa-solid fa-plus"></i>{{ __('Log Entry') }}
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
            <tbody id="merchandiserOrderAssignmentsBody">
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Loading order assignments...') }}
                    </td>
                </tr>
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

            <div id="merchandiserRecentTasks">
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Loading recent tasks...') }}
                </div>
            </div>
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

            <div id="merchandiserRecentCommunications">
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Loading recent communications...') }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{ asset('admin/js/garment-merchandiser-management.js') }}?ver={{ env('VERSION', 0) }}"></script>
@endpush
