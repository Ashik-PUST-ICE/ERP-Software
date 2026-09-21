<form class="ajax reset" action="{{ route('admin.garments.merchandiser.assign') }}" method="post"
    data-handler="commonResponseForModal">
    @csrf
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Manage Order Merchandisers') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        @php
            $primaryId = $selectedOrder?->merchandisers->firstWhere('pivot.is_primary', true)?->id;
            $assignedIds = $selectedOrder ? $selectedOrder->merchandisers->pluck('id')->map(fn ($id) => (int) $id)->all() : [];
        @endphp
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Select Garment Order') }} <span class="required">*</span></label>
                        <select name="order_id" class="form-control" required>
                            <option value="">{{ __('Select order...') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" {{ (int) ($selectedOrder->id ?? 0) === $order->id ? 'selected' : '' }}>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Lead / Primary Merchandiser') }}</label>
                        <select name="primary_user_id" class="form-control">
                            <option value="">{{ __('Select lead (optional)...') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (int) $primaryId === $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Assign Merchandisers (Team)') }} <span class="required">*</span></label>
                        <select class="form-control multiple-basic-single" multiple="multiple" name="user_ids[]" required>
                            <option value=""></option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ in_array($user->id, $assignedIds) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button type="submit" class="primary-btn">{{ __('Save') }}</button>
        </div>
    </div>
</form>
