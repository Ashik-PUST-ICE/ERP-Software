@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ $pageTitle }}
@endpush
@section('content')
<div class="p-30">
    <div class="row gy-4">
        <div class="col-12">
            <div class="section-title">
                <h2 class="title">{{ __($pageTitle) }}</h2>
                <a href="{{ route('super_admin.ticket.details', encrypt($ticketDetails->id)) }}"
                    class="primary-btn">{{ __('Back') }}</a>
            </div>
            <form action="{{ route('super_admin.ticket.store') }}" method="POST" class="ajax reset"
                data-handler="commonResponseRedirect" data-redirect-url="{{ route('super_admin.ticket.list') }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $ticketDetails->id }}">
                <input type="hidden" name="client_id" id="clientId" value="{{ $ticketDetails->client_id }}">

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-wrap">
                            <div class="primary-form">
                                <div class="row gy-4">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Select Package') }} <span
                                                    class="required">*</span></label>
                                            <select class="select form-control wide sf-select-without-search" name="order_id" id="selectPackage" required
                                                onchange="updateClientId()">
                                                <option value="">{{ __('Select Package') }}</option>
                                                @forelse(($paymentOrderList ?? []) as $payment)
                                                <option value="{{ $payment->id }}"
                                                    data-user-id="{{ $payment->user_id }}"
                                                    {{ $ticketDetails->order_id == $payment->id ? 'selected' : '' }}>
                                                    {{ $payment->packageable->name ?? 'Package' }}
                                                    ({{ $payment->user->email ?? 'N/A' }})
                                                </option>
                                                @empty
                                                <option value="">{{ __('No packages found') }}</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Title') }} <span
                                                    class="required">*</span></label>
                                            <input type="text" class="form-control" name="ticket_title"
                                                id="ticket_title" value="{{ $ticketDetails->ticket_title }}"
                                                placeholder="{{ __('Support Request') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Priority') }}</label>
                                            <select class="select form-control wide sf-select-without-search" name="priority" id="priority">
                                                <option value="{{ TICKET_PRIORITY_LOW }}"
                                                    {{ $ticketDetails->priority == TICKET_PRIORITY_LOW ? 'selected' : '' }}>
                                                    {{ __('Low') }}</option>
                                                <option value="{{ TICKET_PRIORITY_MEDIUM }}"
                                                    {{ $ticketDetails->priority == TICKET_PRIORITY_MEDIUM ? 'selected' : '' }}>
                                                    {{ __('Medium') }}</option>
                                                <option value="{{ TICKET_PRIORITY_HIGH }}"
                                                    {{ $ticketDetails->priority == TICKET_PRIORITY_HIGH ? 'selected' : '' }}>
                                                    {{ __('High') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Status') }}</label>
                                            <select class="select form-control wide sf-select-without-search" name="status" id="status">
                                                <option value="{{ TICKET_STATUS_OPEN }}"
                                                    {{ $ticketDetails->status == TICKET_STATUS_OPEN ? 'selected' : '' }}>
                                                    {{ __('Open') }}</option>
                                                <option value="{{ TICKET_STATUS_IN_PROGRESS }}"
                                                    {{ $ticketDetails->status == TICKET_STATUS_IN_PROGRESS ? 'selected' : '' }}>
                                                    {{ __('In Progress') }}</option>
                                                <option value="{{ TICKET_STATUS_RESOLVED }}"
                                                    {{ $ticketDetails->status == TICKET_STATUS_RESOLVED ? 'selected' : '' }}>
                                                    {{ __('Resolved') }}</option>
                                                <option value="{{ TICKET_STATUS_CLOSED }}"
                                                    {{ $ticketDetails->status == TICKET_STATUS_CLOSED ? 'selected' : '' }}>
                                                    {{ __('Closed') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Assign to Team Member') }}</label>
                                            <select class="select form-control wide sf-select-without-search" name="assign_member">
                                                <option value="">{{ __('Select Team Member') }}</option>
                                                @foreach($teamMemberList ?? [] as $member)
                                                <option value="{{ $member->id }}"
                                                    {{ in_array($member->id, $ticketAssignee ?? []) ? 'selected' : '' }}>
                                                    {{ $member->name }}
                                                    ({{ $member->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Description') }} <span
                                                    class="required">*</span></label>
                                            <textarea id="editDescription" class="summernote" name="description"
                                                rows="5" placeholder="{{ __('Write description here...') }}"
                                                required>{{ $ticketDetails->ticket_description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label">{{ __('Change Image (JPG, JPEG, PNG)') }}</label>
                                            <div class="file-upload">
                                                <input type="file" class="file-input" id="mAttachment" name="file[]"
                                                    multiple accept="image/*">
                                                <label for="mAttachment" class="file-input-label">
                                                    <span class="file-text">{{ __('Choose image to upload') }}</span>
                                                    <span class="file-btn">{{ __('Browse File') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="section-wrap my-plan-area h-100">
                            <div class="plan-head"
                                style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ebedf0;">
                                <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">
                                    {{ __('Ticket Actions') }}</h3>
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
                                        {{ __('Ticket ID') }}: <strong>{{ $ticketDetails->ticket_id ?? '-' }}</strong>
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
                                <button type="submit" class="primary-btn w-100">{{ __('Update Ticket') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection