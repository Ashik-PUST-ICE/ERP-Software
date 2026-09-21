@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <a href="{{ route('admin.garments.merchandiser.management') }}" class="primary-btn">
        <i class="fa fa-arrow-left me-2"></i>{{ __('Back to Activities') }}
    </a>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <form action="{{ route('admin.garments.merchandiser.assign') }}" method="POST">
                @csrf

                @php
                    $primaryId = old('primary_user_id', $selectedOrder?->merchandisers->firstWhere('pivot.is_primary', true)?->id);
                    $assignedIds = collect(old('user_ids', $selectedOrder ? $selectedOrder->merchandisers->pluck('id')->all() : []))
                        ->map(fn ($id) => (int) $id)->all();
                @endphp

                <div class="primary-form">
                    <div class="row gy-3">
                        {{-- Section Title --}}
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#4778c7;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-user-gear text-primary"></i>
                                {{ __('Order & Merchandiser Assignment') }}
                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>

                        {{-- Select Order --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Select Garment Order') }} <span class="required">*</span></label>
                                <select name="order_id" class="form-control" required>
                                    <option value="">{{ __('Select order...') }}</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}"
                                            {{ old('order_id', $selectedOrder->id ?? '') == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_number }}
                                            @if($order->buyer) ({{ $order->buyer->company_name }}) @endif
                                            @if($order->style) - {{ $order->style->style_code }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Lead / Primary Merchandiser --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Lead / Primary Merchandiser') }}</label>
                                <select name="primary_user_id" class="form-control">
                                    <option value="">{{ __('Select lead merchandiser (optional)...') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ (int) $primaryId === $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Merchandisers Team Multi-Select --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Assign Merchandisers (Team)') }} <span class="required">*</span></label>
                                <select class="form-control multiple-basic-single" multiple="multiple" name="user_ids[]" required>
                                    <option value=""></option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, $assignedIds) ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="fa-solid fa-circle-info me-1 text-primary"></i>
                                    {{ __('Search, select or remove multiple merchandisers for this order team.') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="btn-list mt-4 pt-3" style="border-top: 2px solid #f1f5f9;">
                    <a href="{{ route('admin.garments.merchandiser.management') }}" class="primary-btn-outline d-inline-flex align-items-center gap-2">
                        <i class="fa fa-arrow-left"></i>{{ __('Cancel') }}
                    </a>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-link"></i>{{ __('Assign Merchandisers') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
