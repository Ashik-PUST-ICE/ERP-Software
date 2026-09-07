<div class="modal-header">
    <h2 class="modal-title">{{ __('Add Ticket') }}</h2>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<form action="{{ route('super_admin.ticket.store') }}" method="POST" class="ajax reset" data-handler="commonResponse"
    enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="client_id" id="clientId">
    <div class="modal-body">
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Select Package') }} <span class="required">*</span></label>
                        <select class="select form-control wide sf-select-without-search" name="order_id" id="selectPackage" required
                            onchange="updateClientId()">
                            <option value="">{{ __('Select Package') }}</option>
                            @forelse(($paymentOrderList ?? []) as $payment)
                            <option value="{{ $payment->id }}" data-user-id="{{ $payment->user_id }}">
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
                            placeholder="{{ __('Support Request') }}" required>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Priority') }}</label>
                        <select class="select form-control wide sf-select-without-search" name="priority">
                            <option value="{{ TICKET_PRIORITY_LOW }}">{{ __('Low') }}</option>
                            <option value="{{ TICKET_PRIORITY_MEDIUM }}">{{ __('Medium') }}</option>
                            <option value="{{ TICKET_PRIORITY_HIGH }}">{{ __('High') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Assign to Team Member') }}</label>
                        <select class="select form-control wide sf-select-without-search" name="assign_member">
                            <option value="">{{ __('Select Team Member') }}</option>
                            @foreach($teamMemberList ?? [] as $member)
                            <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Description') }} <span class="required">*</span></label>
                        <textarea id="description" class="summernote" name="description" rows="5"
                            placeholder="{{ __('Write description here...') }}" required></textarea>
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


            </div>
        </div>
    </div>

    <div class="modal-footer">

        <button type="submit" class="primary-btn">{{ __('Save Ticket') }}</button>
    </div>

</form>