<div class="modal-header">
    <h2 class="modal-title">{{ __('Add Ticket') }}</h2>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="{{ route('admin.ticket.store') }}" method="POST" class="ajax reset" data-handler="settingCommonHandler"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Select Package') }} <span class="required">*</span></label>
                        <select class="form-control select wide" name="order_id" id="selectPackage" required>
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
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Description') }} <span class="required">*</span></label>
                        <textarea id="description" class="summernote" name="description"
                            rows="3">{{ old('description') }}</textarea>
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