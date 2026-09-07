<form class="ajax reset"
    action="{{ route('super_admin.subscription-refund.subscription-model-status-change', $statusChange->id) }}"
    method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Refund Status Change') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="row gy-3">



                    <div class="row rg-20">

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Transaction ID') }}</label>
                                <div class="position-relative">
                                    <span
                                        class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted"></span>
                                    <input type="text" class="form-control ps-4" name="transaction_id"
                                        value="{{ $statusChange->transaction_id ?? '' }}" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Refund Amount') }}</label>
                                <div class="position-relative">
                                    <span
                                        class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">$</span>
                                    <input type="number" step="0.01" class="form-control padding-left-increase" name="refund_amount"
                                        value="{{ $statusChange->buy_amount ?? 0 }}" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Admin Feedback') }}</label>
                                <textarea class="form-control" name="admin_feedback" rows="3"
                                    placeholder="{{ __('Enter admin feedback...') }}"></textarea>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Status') }}</label>
                                <select class="form-control select wide sf-select-without-search" name="status" required>
                                    <option value="{{ STATUS_ACTIVE }}"
                                        {{ $statusChange->status == STATUS_ACTIVE ? 'selected' : '' }}>
                                        {{ __('Approve') }}</option>
                                    <option value="{{ STATUS_REJECT }}"
                                        {{ $statusChange->status == STATUS_REJECT ? 'selected' : '' }}>
                                        {{ __('Reject') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Submit') }}</button>
        </div>
    </div>
</form>