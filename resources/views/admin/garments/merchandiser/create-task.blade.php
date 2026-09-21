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
            <form action="{{ route('admin.garments.merchandiser.task') }}" method="POST">
                @csrf

                <div class="primary-form">
                    <div class="row gy-3">
                        {{-- Section Title --}}
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#f59e0b;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-list-check text-warning"></i>
                                {{ __('Task Details & Assignment') }}
                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>

                        {{-- Order --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Select Garment Order') }} <span class="required">*</span></label>
                                <select name="order_id" class="form-control" required>
                                    <option value="">{{ __('Select order...') }}</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            {{ $order->order_number }}
                                            @if($order->buyer) ({{ $order->buyer->company_name }}) @endif
                                            @if($order->style) - {{ $order->style->style_code }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- Merchandiser --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Assigned To (Merchandiser)') }} <span class="required">*</span></label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">{{ __('Select merchandiser...') }}</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Task Title --}}
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="form-label">{{ __('Task Title') }} <span class="required">*</span></label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title') }}" placeholder="{{ __('e.g. Submit lab dip / trim card to buyer') }}" required>
                            </div>
                        </div>

                        {{-- Priority --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Priority') }} <span class="required">*</span></label>
                                <select name="priority" class="form-control" required>
                                    <option value="1" {{ old('priority', 2) == 1 ? 'selected' : '' }}>{{ __('Low') }}</option>
                                    <option value="2" {{ old('priority', 2) == 2 ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                    <option value="3" {{ old('priority', 2) == 3 ? 'selected' : '' }}>{{ __('High') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Due Date --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Due Date') }}</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date') }}">
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Notes & Instructions') }}</label>
                                <textarea name="notes" class="form-control" rows="4"
                                    placeholder="{{ __('Provide detailed task instructions, buyer feedback, or milestones...') }}">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="btn-list mt-4 pt-3" style="border-top: 2px solid #f1f5f9;">
                    <a href="{{ route('admin.garments.merchandiser.management') }}" class="primary-btn-outline d-inline-flex align-items-center gap-2">
                        <i class="fa fa-arrow-left"></i>{{ __('Cancel') }}
                    </a>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2">
                        <i class="fa-solid fa-plus"></i>{{ __('Create Task') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection