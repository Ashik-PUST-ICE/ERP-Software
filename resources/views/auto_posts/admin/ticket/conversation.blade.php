@if(count($ticketConversations) > 0)
<div class="section-wrap mt-20">
    <div class="section-small-title pb-15 mb-20 bd-b-one bd-c-ebedf0">
        <h3 class="title fs-15 fw-600 lh-20 text-title-black">{{ __('Ticket Replies') }}</h3>
    </div>
    <div class="d-flex flex-column gap-3">
        @foreach($ticketConversations as $item)
        <div class="bg-white border rounded p-3">
            <div class="d-flex align-items-center gap-2 mb-2 ticket-client-image-wrapper">
                <div class="flex-shrink-0 w-32 h-32 rounded-circle overflow-hidden ticket-client-image">
                    <img src="{{ getFileUrl($item->client_image ?? '') }}" alt="" class="w-100 h-100"
                        style="object-fit:cover;" onerror="this.src='{{ asset('assets/images/avatar-image.png') }}'" />
                </div>
                @if($item->user_id == auth()->id())
                <h4 class="fs-13 fw-600 lh-18 text-title-black mb-0">{{ __('You') }}</h4>
                @else
                <h4 class="fs-13 fw-600 lh-18 text-title-black mb-0">{{ $item->client_name }}</h4>
                @endif
            </div>
            <div class="mb-2">
                <p class="fs-13 fw-400 lh-20 text-para-text mb-0">{!! $item->conversation_text !!}</p>
            </div>
            @if($item->attachment != null && count(json_decode($item->attachment) ?? []) > 0)
            <div class="d-flex flex-wrap gap-2">
                @foreach(json_decode($item->attachment) as $file)
                @if(in_array(getFileData($file, 'extension') ?? '',
                ['jpg','png','jpeg','webp','JPG','PNG','JPEG','WEBP']))
                <div class="sf-popup-gallery">
                    <a href="{{ getFileUrl($file) }}">
                        <img src="{{ getFileUrl($file) }}" alt="" class="border rounded"
                            style="width:80px;height:80px;object-fit:cover;" />
                    </a>
                </div>
                @else
                <a href="{{ getFileUrl($file) }}" target="_blank"
                    class="p-2 border rounded d-inline-flex flex-column text-decoration-none">
                    <div><img src="{{ asset('assets/images/icon/files-1.svg') }}" alt="" /></div>
                    <p class="fs-12 fw-400 lh-14 text-title-black mb-0">{{ getFileData($file, 'file_name') }}</p>
                </a>
                @endif
                @endforeach
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="section-wrap mt-20">
    <div class="section-small-title pb-15 mb-20 bd-b-one bd-c-ebedf0">
        <h3 class="title fs-15 fw-600 lh-20 text-title-black">{{ __('Write a Reply') }}</h3>
    </div>
    <form class="ajax reset" action="{{ route('admin.ticket.conversations.store') }}" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseWithPageLoad">
        @csrf
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="d-flex justify-content-end align-items-center flex-wrap g-10 pb-8">
                            <div class="d-flex flex-wrap align-items-center gap-10 write-replay-radio-box" style="column-gap: 20px; margin-bottom: 20px">
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="radio" name="status"
                                        class="form-check-input ticket-status-change status{{ TICKET_STATUS_RESOLVED }}"
                                        {{ $ticketDetails->status == TICKET_STATUS_RESOLVED ? 'checked' : '' }}
                                        data-ticket="{{ encrypt($ticketDetails->id) }}" id="solved"
                                        value="{{ TICKET_STATUS_RESOLVED }}"
                                        data-status="{{ $ticketDetails->status }}" />
                                    <label for="solved">{{ __('Solved') }}</label>
                                </div>
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="radio" name="status"
                                        class="form-check-input ticket-status-change status{{ TICKET_STATUS_CLOSED }}"
                                        {{ $ticketDetails->status == TICKET_STATUS_CLOSED ? 'checked' : '' }}
                                        data-ticket="{{ encrypt($ticketDetails->id) }}" id="closed"
                                        value="{{ TICKET_STATUS_CLOSED }}" data-status="{{ $ticketDetails->status }}" />
                                    <label for="closed">{{ __('Closed') }}</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value="{{ encrypt($ticketDetails->id) }}" name="ticket_id">
                        <textarea id="ticketReply" class="form-control" rows="5" style="min-height: 170px"
                            placeholder="{{ __('Write Reply here') }}...." name="conversation_text"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Upload Image (JPG, JPEG, PNG)') }}</label>
                        <div class="file-upload">
                            <input type="file" name="file[]" id="mAttachment" class="file-input" multiple
                                accept="image/*" />
                            <label for="mAttachment" class="file-input-label">
                                <span class="file-text">{{ __('Choose image to upload') }}</span>
                                <span class="file-btn">{{ __('Browse File') }}</span>
                            </label>
                        </div>
                    </div>
                </div>
                @if($ticketDetails->status == TICKET_STATUS_CLOSED)
                <div class="col-lg-12">
                    <p class="fs-14 fw-400 lh-20 text-para-text mb-0">
                        {{ __('Note: Not possible to conversation for this ticket. Because, This ticket is closed') }}.
                    </p>
                </div>
                @else
                <div class="col-lg-12">
                    <button type="submit" class="primary-btn">{{ __('Send Message') }}</button>
                </div>
                @endif
            </div>
        </div>
    </form>
</div>
<input type="hidden" id="statusChangeRoute" value="{{ route('admin.ticket.status.change') }}">