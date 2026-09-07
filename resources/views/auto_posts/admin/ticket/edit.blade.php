@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ $pageTitle }}
@endpush

@section('content')
<div class="section-wrap">
    <div class="row bd-c-ebedf0 bd-half bd-ra-25 bg-white h-100 p-30">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center g-10 pb-12">
                <h4 class="fs-18 fw-600 lh-20 text-title-black">{{ __('Edit Ticket') }}</h4>

            </div>

            @if($ticketDetails)
            <form class="ajax reset" action="{{ route('admin.ticket.store') }}" method="POST"
                enctype="multipart/form-data" data-handler="commonResponseRedirect"
                data-redirect-url="{{ route('admin.ticket.list') }}">
                @csrf
                @method('PUT')
                <input type="hidden" value="{{ $ticketDetails->id }}" name="id">

                <div class="primary-form">
                    <div class="row gy-4">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Select Package') }} <span
                                        class="required">*</span></label>
                                <select class="form-control select wide" name="order_id" id="selectPackage" required>
                                    <option value="">{{ __('Select Package') }}</option>
                                    @forelse(($paymentOrderList ?? []) as $payment)
                                    <option value="{{ $payment->id }}" data-user-id="{{ $payment->user_id }}"
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
                                <label class="form-label">{{ __('Title') }} <span class="required">*</span></label>
                                <input type="text" class="form-control" name="ticket_title" id="ticket_title"
                                    value="{{ $ticketDetails->ticket_title }}" placeholder="{{ __('Support Request') }}"
                                    required>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Description') }} <span
                                        class="required">*</span></label>
                                <textarea id="description" class="summernote" name="description" rows="3"
                                    required>{{ $ticketDetails->ticket_description }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label class="form-label">{{ __('Priority') }}</label>
                                <select class="form-control select wide" name="priority" id="priority">
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
                                <select class="form-control select wide" name="status" id="status">
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
                                <label class="form-label">{{ __('Change Image (JPG, JPEG, PNG)') }}</label>
                                <div class="file-upload">
                                    <input type="file" class="file-input" id="mAttachment" name="file[]" multiple
                                        accept="image/*">
                                    <label for="mAttachment" class="file-input-label">
                                        <span class="file-text">{{ __('Choose image to upload') }}</span>
                                        <span class="file-btn">{{ __('Browse File') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                <button type="submit" class="primary-btn">{{ __('Update Ticket') }}</button>
                                <a href="{{ route('admin.ticket.list') }}" class="primary-btn btn-outline ">{{ __('Cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @else
            <div class="alert alert-danger">{{ __('Ticket not found') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection