@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ $pageTitle }}
@endpush

@section('content')
<div class="p-30">
    <div class="row gy-4">
        @if($ticketDetails)
        <div class="col-12">
            <div class="section-title">
                <h2 class="title">{{ __($pageTitle) }}</h2>
                <a href="{{ route('admin.ticket.list') }}"
                    class="primary-btn">{{ __('Back') }}</a>
            </div>
            <div class="row gy-4">
                <div class="col-lg-4">
                    <div class="section-wrap my-plan-area h-100">
                        <div class="plan-head"
                            style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ebedf0;">
                            <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">{{ __('Ticket Info') }}
                            </h3>
                            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 4px;">
                                {{ $ticketDetails->ticket_title ?? __('Support Request') }}</h2>
                            <h3 style="font-size: 12px; font-weight: 500; color: #6b7280;">
                                {{ $ticketDetails->ticket_id ?? '-' }}</h3>
                        </div>
                        <div class="plan-body" style="padding-top: 10px;">
                            <ul class="plan-features" style="gap: 8px; margin: 0; padding: 0; list-style: none;">
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Package') }}:
                                    <strong>
                                        @php
                                        $packageName = '-';
                                        if(isset($ticketDetails->userPackage) &&
                                        isset($ticketDetails->userPackage->packageable)) {
                                        $packageName = $ticketDetails->userPackage->packageable->name;
                                        if(isset($ticketDetails->userPackage->billing_cycle)) {
                                        $packageName .= ' (' . $ticketDetails->userPackage->billing_cycle . ')';
                                        }
                                        } elseif(isset($ticketDetails->payment) &&
                                        isset($ticketDetails->payment->paymentable)) {
                                        $packageName = $ticketDetails->payment->paymentable->name;
                                        } elseif(isset($ticketDetails->order_id)) {
                                        $packageName = $ticketDetails->order_id;
                                        }
                                        @endphp
                                        {{ $packageName }}
                                    </strong>
                                </li>
                                @if(isset($ticketDetails->userPackage) && $ticketDetails->userPackage)
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Type') }}:
                                    <strong>
                                        @if(isset($ticketDetails->userPackage->subscription_type))
                                        @if($ticketDetails->userPackage->subscription_type == 1)
                                        {{ __('Monthly') }}
                                        @elseif($ticketDetails->userPackage->subscription_type == 2)
                                        {{ __('Yearly') }}
                                        @else
                                        {{ $ticketDetails->userPackage->subscription_type }}
                                        @endif
                                        @else
                                        -
                                        @endif
                                    </strong>
                                    </strong>
                                </li>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Start Date') }}:
                                    <strong>{{ $ticketDetails->userPackage->start_date ?? '-' }}</strong>
                                </li>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('End Date') }}:
                                    <strong>{{ $ticketDetails->userPackage->end_date ?? '-' }}</strong>
                                </li>
                                @endif
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Name') }}: <strong>{{ $ticketDetails->client_name ?? '-' }}</strong>
                                </li>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Email') }}: <strong>{{ $ticketDetails->client_email ?? '-' }}</strong>
                                </li>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Priority') }}:
                                    @if($ticketDetails->priority == TICKET_PRIORITY_LOW)
                                    <span style="color: #6b7280;">{{ __('Low') }}</span>
                                    @elseif($ticketDetails->priority == TICKET_PRIORITY_HIGH)
                                    <span style="color: #dc2626; font-weight: 600;">{{ __('High') }}</span>
                                    @else
                                    <span style="color: #f59e0b; font-weight: 600;">{{ __('Medium') }}</span>
                                    @endif
                                </li>
                                <li
                                    style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                    <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                            stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    {{ __('Status') }}:
                                    @if($ticketDetails->status == TICKET_STATUS_OPEN)
                                    <span class="zBadge zBadge-open">{{ __('Open') }}</span>
                                    @elseif($ticketDetails->status == TICKET_STATUS_IN_PROGRESS)
                                    <span class="zBadge zBadge-onHold">{{ __('In Progress') }}</span>
                                    @elseif($ticketDetails->status == TICKET_STATUS_RESOLVED)
                                    <span class="zBadge zBadge-complete">{{ __('Resolved') }}</span>
                                    @elseif($ticketDetails->status == TICKET_STATUS_CLOSED)
                                    <span class="zBadge zBadge-closed">{{ __('Closed') }}</span>
                                    @else
                                    <span class="zBadge zBadge-open">{{ __('Open') }}</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                        <div class="plan-footer"
                            style="padding-top: 15px; margin-top: 15px; border-top: 1px solid #ebedf0;">
                            <div class="btn-list" style="display: flex; gap: 10px;">
                                <a href="{{ route('admin.ticket.edit', encrypt($ticketDetails->id)) }}"
                                    class="primary-btn" style="padding: 8px 20px; font-size: 13px;">{{ __('Edit') }}</a>
                                <button type="button" class="primary-btn btn-outline ticket-delete-btn"
                                    data-route="{{ route('admin.ticket.delete', encrypt($ticketDetails->id)) }}"
                                    data-redirect="{{ route('admin.ticket.list') }}"
                                    style="padding: 8px 20px;">{{ __('Delete') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="section-wrap">
                        <div class="d-flex align-items-center g-10 pb-20 bd-b-one bd-c-ebedf0 mb-20 ticket-client-image-wrapper">
                            <div class="flex-shrink-0 w-40 h-40 rounded-circle overflow-hidden ticket-client-image">
                                @if(!empty($ticketDetails->client_image))
                                <img src="{{ getFileUrl($ticketDetails->client_image) }}" alt="" class="w-100 h-100"
                                    style="object-fit:cover; " />
                                @else
                                <img src="{{ asset('assets/images/avatar-image.png') }}" alt="" class="w-100 h-100"
                                    style="object-fit:cover;" />
                                @endif
                            </div>
                            <div>
                                <h4 class="fs-15 fw-600 lh-20 text-title-black mb-0">
                                    {{ $ticketDetails->client_name ?? 'Client' }}</h4>
                                <p class="fs-12 fw-400 lh-15 text-para-text mb-0">({{ __('Client') }})</p>
                            </div>
                        </div>
                        <div class="section-small-title pb-12 mb-15">
                            <h3 class="title fs-14 fw-600 lh-20 text-title-black">{{ __('Description') }}</h3>
                        </div>
                        <div class="fs-14 fw-400 lh-24 text-para-text text-justify pb-20">
                            {!! $ticketDetails->ticket_description !!}
                        </div>
                        @if($ticketDetails->file_id != null && count(json_decode($ticketDetails->file_id) ?? []) > 0)
                        <ul class="d-flex flex-wrap g-10 list-unstyled mb-0">
                            @foreach(json_decode($ticketDetails->file_id) as $file)
                            @if(in_array(getFileData($file, 'extension') ?? '',
                            ['jpg','png','jpeg','webp','JPG','PNG','JPEG','WEBP']))
                            <li>
                                <div class="sf-popup-gallery">
                                    <a href="{{ getFileUrl($file) }}">
                                        <img src="{{ getFileUrl($file) }}" alt="" class="bd-one bd-c-ebedf0 bd-ra-8"
                                            style="width:120px;height:120px;object-fit:cover;" />
                                    </a>
                                </div>
                            </li>
                            @else
                            <li>
                                <a href="{{ getFileUrl($file) }}" target="_blank"
                                    class="p-10 bd-one bd-c-ebedf0 bd-ra-10 bg-body-bg d-inline-flex flex-column g-10 text-decoration-none">
                                    <div><img src="{{ asset('assets/images/icon/files-1.svg') }}" alt="" /></div>
                                    <p class="fs-14 fw-400 lh-17 text-title-black mb-0">
                                        {{ getFileData($file, 'file_name') }}</p>
                                    <div class="d-flex align-items-center g-8">
                                        <span class="fs-12 fw-400 lh-15 text-para-text">{{ getFileData($file, 'size') }}
                                            B</span>
                                        <span class="fs-12 fw-400 lh-15 text-para-text">{{ __('File') }}</span>
                                    </div>
                                </a>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    @include('auto_posts.admin.ticket.conversation')
                </div>
            </div>
        </div>
        @else
        <div class="col-12">
            <div class="alert alert-danger">{{ __('Ticket not found') }}</div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/ticket.js') }}"></script>
@endpush