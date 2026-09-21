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
            <form action="{{ route('admin.garments.merchandiser.communication') }}" method="POST">
                @csrf

                <div class="primary-form">
                    <div class="row gy-3">
                        {{-- Section Title --}}
                        <div class="col-12">
                            <h5 class="fw-600 mb-2 d-flex align-items-center gap-2" style="color:#0f172a;">
                                <span style="width:4px;height:20px;background:#10b981;border-radius:4px;display:inline-block;"></span>
                                <i class="fa-solid fa-comments text-success"></i>
                                {{ __('Communication Details') }}
                            </h5>
                            <hr style="border-color:#f1f5f9;margin-bottom:16px;">
                        </div>

                        {{-- Order --}}
                        <div class="col-md-4">
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

                        {{-- Channel --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Communication Channel') }} <span class="required">*</span></label>
                                <select name="channel" class="form-control" required>
                                    <option value="email" {{ old('channel') == 'email' ? 'selected' : '' }}>{{ __('Email') }}</option>
                                    <option value="phone" {{ old('channel') == 'phone' ? 'selected' : '' }}>{{ __('Phone Call') }}</option>
                                    <option value="meeting" {{ old('channel') == 'meeting' ? 'selected' : '' }}>{{ __('In-person / Online Meeting') }}</option>
                                    <option value="whatsapp" {{ old('channel') == 'whatsapp' ? 'selected' : '' }}>{{ __('WhatsApp / Messaging') }}</option>
                                </select>
                            </div>
                        </div>

                        {{-- Date & Time --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">{{ __('Date & Time') }} <span class="required">*</span></label>
                                <input type="datetime-local" name="communicated_at" class="form-control"
                                    value="{{ old('communicated_at', now()->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>

                        {{-- Subject --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Subject / Topic') }}</label>
                                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}"
                                    placeholder="{{ __('e.g. Fit sample approval & bulk fabric delivery update') }}">
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Discussion Summary / Notes') }} <span class="required">*</span></label>
                                <textarea name="notes" class="form-control" rows="5" required
                                    placeholder="{{ __('Provide detailed summary of communication, decisions made, buyer expectations, or next action items...') }}">{{ old('notes') }}</textarea>
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
                        <i class="fa-solid fa-floppy-disk"></i>{{ __('Save Communication Log') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
