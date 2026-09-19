<form class="ajax reset" action="{{ $profitLoss ? route('admin.garments.profit-loss.update', $profitLoss->id) : route('admin.garments.profit-loss.store') }}" method="post" data-handler="commonResponseWithPageLoad">
    @csrf
    @if($profitLoss)
        @method('put')
    @endif
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ $profitLoss ? __('Edit Order Profit & Loss') : __('Add Order Profit & Loss') }}</h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Order') }} <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value="">{{ __('Select Order') }}</option>
                            @foreach($orders as $order)
                                <option value="{{ $order->id }}" @selected(old('order_id', $profitLoss?->order_id) == $order->id)>{{ $order->order_number }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Sales Revenue') }} <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="sales_revenue" value="{{ old('sales_revenue', $profitLoss?->sales_revenue ?? 0) }}" required>
                    </div>
                </div>
                @foreach(['material_cost' => 'Material Cost', 'production_cost' => 'Production Cost', 'salary_cost' => 'Salary Cost', 'overhead_cost' => 'Overhead Cost', 'other_cost' => 'Other Cost'] as $field => $label)
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">{{ __($label) }} <span class="required">*</span></label>
                            <input type="number" min="0" step="0.0001" class="form-control" name="{{ $field }}" value="{{ old($field, $profitLoss?->{$field} ?? 0) }}" required>
                        </div>
                    </div>
                @endforeach
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">{{ __('Status') }} <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            @foreach(garmentProfitLossStatuses() as $value => $status)
                                <option value="{{ $value }}" @selected(old('status', $profitLoss?->status ?? GARMENT_PROFIT_LOSS_STATUS_DRAFT) == $value)>{{ __($status[0]) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea class="form-control" name="notes" rows="3">{{ old('notes', $profitLoss?->notes) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
            <button class="primary-btn" type="submit">{{ $profitLoss ? __('Update') : __('Save') }}</button>
        </div>
    </div>
</form>
