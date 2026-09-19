@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush

@push('style')
<style>
.buyer-order-info-card {
    border-radius: 12px;
    background: #ffffff;
    box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
}
.order-overview-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13.5px;
}
.order-overview-row:last-child {
    border-bottom: none;
}
.order-overview-label {
    color: #64748b;
    font-weight: 500;
}
.order-overview-value {
    color: #0f172a;
    font-weight: 600;
}
</style>
@endpush

@section('content')
{{-- Page Header --}}
<div class="section-title d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div class="title-wrap">
        <h2 class="title">{{ $title }} - {{ $order->order_number }}</h2>
        <p class="text-muted mb-0" style="font-size: 13.5px;">{{ __('Comprehensive status, production tracking, and shipments for this order') }}</p>
    </div>
    <a class="primary-btn d-inline-flex align-items-center gap-2" href="{{ route('admin.garments.buyer-portal.index', ['buyer_id' => $order->buyer_id]) }}">
        <i class="fa fa-arrow-left"></i>{{ __('Back to Buyer Portal') }}
    </a>
</div>

<div class="row g-4">
    {{-- Order Summary Card --}}
    <div class="col-xl-4 col-lg-5">
        <div class="section-wrap p-4 h-100 buyer-order-info-card">
            <h4 class="fw-700 mb-3" style="color: #0f172a;">
                <i class="fa-solid fa-file-invoice text-primary me-2"></i>{{ __('Order Overview') }}
            </h4>

            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-hashtag me-2 text-muted"></i>{{ __('Order #:') }}</span>
                <span class="order-overview-value"><strong>{{ $order->order_number }}</strong></span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-building me-2 text-muted"></i>{{ __('Buyer:') }}</span>
                <span class="order-overview-value">{{ $order->buyer?->company_name ?? 'N/A' }}</span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-shirt me-2 text-muted"></i>{{ __('Style:') }}</span>
                <span class="order-overview-value">{{ $order->style ? $order->style->style_code . ' - ' . $order->style->style_name : 'N/A' }}</span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-boxes-stacked me-2 text-muted"></i>{{ __('Quantity:') }}</span>
                <span class="order-overview-value text-primary"><strong>{{ number_format($order->quantity) }} Pcs</strong></span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-dollar-sign me-2 text-muted"></i>{{ __('Unit Price:') }}</span>
                <span class="order-overview-value">${{ number_format($order->unit_price, 2) }}</span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-calendar me-2 text-muted"></i>{{ __('Order Date:') }}</span>
                <span class="order-overview-value">{{ $order->order_date?->format('d M Y') ?? 'N/A' }}</span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-truck me-2 text-muted"></i>{{ __('Delivery Date:') }}</span>
                <span class="order-overview-value text-danger"><strong>{{ $order->delivery_date?->format('d M Y') ?? 'N/A' }}</strong></span>
            </div>
            <div class="order-overview-row">
                <span class="order-overview-label"><i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i>{{ __('Status:') }}</span>
                @php
                    $statuses = garmentOrderStatuses();
                    [$label, $badgeClass] = $statuses[$order->status] ?? ['Unknown', 'zBadge-warning'];
                @endphp
                <span class="zBadge {{ $badgeClass }}">{{ __($label) }}</span>
            </div>

            @if($order->product_description)
                <div class="mt-3 pt-3 border-top">
                    <span class="text-muted d-block small mb-1 fw-600">{{ __('Product Description:') }}</span>
                    <p class="mb-0 text-muted" style="font-size: 13px; line-height: 1.5;">{{ $order->product_description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Production Progress Card --}}
    <div class="col-xl-8 col-lg-7">
        <div class="section-wrap p-4 h-100 buyer-order-info-card">
            <h4 class="fw-700 mb-3" style="color: #0f172a;">
                <i class="fa-solid fa-industry text-primary me-2"></i>{{ __('Production Progress') }}
            </h4>

            @php
                $totalOutput = (int) $order->sewingProductions->sum('total_output');
                $targetQty = (int) ($order->quantity ?: 1);
                $percent = min(100, round(($totalOutput / $targetQty) * 100));
            @endphp

            <div class="p-3 mb-4 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-600 small text-muted">
                        {{ __('Total Output Completed:') }} <strong class="text-dark">{{ number_format($totalOutput) }}</strong> / {{ number_format($targetQty) }} Pcs
                    </span>
                    <strong class="text-primary fs-5">{{ $percent }}%</strong>
                </div>
                <div class="progress" style="height: 10px; border-radius: 6px; background-color: #e2e8f0;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%; border-radius: 6px;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="display primary-table w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Line') }}</th>
                            <th>{{ __('Target') }}</th>
                            <th>{{ __('Output') }}</th>
                            <th>{{ __('Efficiency') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->sewingProductions as $production)
                        <tr>
                            <td>{{ $production->production_date?->format('d M Y') ?? 'N/A' }}</td>
                            <td>{{ $production->line_number ?? 'Line 1' }}</td>
                            <td>{{ number_format($production->daily_target ?? 0) }}</td>
                            <td><strong>{{ number_format($production->total_output ?? 0) }}</strong></td>
                            <td>
                                @php $eff = round($production->efficiency_percentage ?? 0); @endphp
                                <span class="zBadge {{ $eff >= 80 ? 'zBadge-complete' : ($eff >= 50 ? 'zBadge-primary' : 'zBadge-warning') }}">
                                    {{ $eff }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open mb-2 d-block fa-2x"></i>{{ __('No sewing production entries yet') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Time & Action (TNA) Tasks Card --}}
    <div class="col-xl-6">
        <div class="section-wrap p-4 h-100 buyer-order-info-card">
            <h4 class="fw-700 mb-3" style="color: #0f172a;">
                <i class="fa-solid fa-list-check text-primary me-2"></i>{{ __('Time & Action (TNA) Tasks') }}
            </h4>
            <div class="table-responsive">
                <table class="display primary-table w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Task Name') }}</th>
                            <th>{{ __('Planned Date') }}</th>
                            <th>{{ __('Actual Date') }}</th>
                            <th>{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->tnaTasks as $task)
                        <tr>
                            <td><strong>{{ $task->task_name }}</strong></td>
                            <td>{{ $task->planned_date?->format('d M Y') ?? 'N/A' }}</td>
                            <td>{{ $task->actual_date?->format('d M Y') ?? '-' }}</td>
                            <td>
                                @if($task->status == 3)
                                    <span class="zBadge zBadge-complete">{{ __('Completed') }}</span>
                                @elseif($task->status == 2)
                                    <span class="zBadge zBadge-primary">{{ __('In Progress') }}</span>
                                @else
                                    <span class="zBadge zBadge-warning">{{ __('Pending') }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open mb-2 d-block fa-2x"></i>{{ __('No TNA tasks assigned') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Shipment & Export Documents Card --}}
    <div class="col-xl-6">
        <div class="section-wrap p-4 h-100 buyer-order-info-card">
            <h4 class="fw-700 mb-3" style="color: #0f172a;">
                <i class="fa-solid fa-truck-ramp-box text-primary me-2"></i>{{ __('Shipment & Export Documents') }}
            </h4>
            <div class="table-responsive">
                <table class="display primary-table w-100">
                    <thead>
                        <tr>
                            <th>{{ __('Document Type') }}</th>
                            <th>{{ __('Document #') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th class="text-end">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->shipmentDocuments as $document)
                        <tr>
                            <td><strong>{{ ucfirst($document->document_type) }}</strong></td>
                            <td>{{ $document->document_number }}</td>
                            <td>{{ $document->issue_date?->format('d M Y') ?? 'N/A' }}</td>
                            <td class="text-end">
                                @if($document->file_path)
                                    <a href="{{ asset($document->file_path) }}" target="_blank" class="primary-btn-outline py-1 px-3" style="font-size: 12px; border-radius: 6px;">
                                        <i class="fa-solid fa-download me-1"></i>{{ __('View') }}
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fa-regular fa-folder-open mb-2 d-block fa-2x"></i>{{ __('No shipment documents recorded') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
