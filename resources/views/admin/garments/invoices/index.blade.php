@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title) }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Create Invoice') }}
    </button>
</div>

<div class="invoice-summary-grid">
    <div class="invoice-summary-card total"><span class="invoice-summary-icon"><i class="fa-solid fa-file-invoice"></i></span><div><strong>{{ $invoiceSummary['total'] }}</strong><small>{{ __('Total Invoices') }}</small></div></div>
    <div class="invoice-summary-card paid"><span class="invoice-summary-icon"><i class="fa-solid fa-circle-check"></i></span><div><strong>{{ $invoiceSummary['paid'] }}</strong><small>{{ __('Paid Invoices') }}</small></div></div>
    <div class="invoice-summary-card outstanding"><span class="invoice-summary-icon"><i class="fa-solid fa-clock"></i></span><div><strong>{{ $invoiceSummary['outstanding'] }}</strong><small>{{ __('Outstanding') }}</small></div></div>
    <div class="invoice-summary-card overdue"><span class="invoice-summary-icon"><i class="fa-solid fa-triangle-exclamation"></i></span><div><strong>{{ $invoiceSummary['overdue'] }}</strong><small>{{ __('Overdue') }}</small></div></div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData"
                        placeholder="{{ __('Search Invoices...') }}" />
                </div>
                <input type="hidden" id="garment-invoice-data-route" value="{{ route('admin.garments.invoices.index') }}">
                <table class="display primary-table dataTable dtr-inline" id="garmentInvoiceDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('SL') }}</th>
                            <th>{{ __('Invoice') }}</th>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Issue Date') }}</th>
                            <th>{{ __('Due Date') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Paid') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($invoices as $invoice)
<!-- Payment Modal -->
<div class="modal fade zModalTwo" id="payment-modal-{{ $invoice->id }}" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" class="ajax reset" action="{{ route('admin.garments.invoices.payments.store', $invoice->id) }}" data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Record Payment') }} - {{ $invoice->invoice_number }}</h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <p class="mb-0 fw-500 text-primary">{{ __('Outstanding') }}: {{ $invoice->currency }} {{ number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.0001" min="0.0001" max="{{ max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount) }}" value="{{ max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Method') }} <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-control" required>
                                        <option value="bank_transfer">{{ __('Bank Transfer') }}</option>
                                        <option value="lc">{{ __('L/C') }}</option>
                                        <option value="cash">{{ __('Cash') }}</option>
                                        <option value="other">{{ __('Other') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Reference') }}</label>
                                    <input name="reference" class="form-control" placeholder="{{ __('Transaction / Check Ref') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="primary-btn" type="submit">{{ __('Save Payment') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach($invoices as $invoice)
<!-- Gateway Payment Modal -->
<div class="modal fade zModalTwo" id="gateway-payment-modal-{{ $invoice->id }}" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" action="{{ route('admin.garments.invoices.payments.checkout', $invoice->id) }}">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Online Invoice Payment') }} - {{ $invoice->invoice_number }}</h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <p class="mb-0 fw-500 text-primary">{{ __('Outstanding') }}: {{ $invoice->currency }} {{ number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Gateway') }} <span class="text-danger">*</span></label>
                                    <select name="gateway" class="form-control" required>
                                        @forelse($gateways->filter(fn($gateway) => $gateway->currencies->contains('currency', $invoice->currency)) as $gateway)
                                            <option value="{{ $gateway->slug }}">{{ $gateway->title }} ({{ $invoice->currency }})</option>
                                        @empty
                                            <option value="" disabled>{{ __('No configured gateway supports this invoice currency') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.0001" min="0.0001" max="{{ max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount) }}" value="{{ max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="primary-btn" type="submit">{{ __('Continue to Payment') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Add Invoice Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" class="ajax reset" action="{{ route('admin.garments.invoices.store') }}" data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Create Commercial Invoice') }}</h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Order') }} <span class="text-danger">*</span></label>
                                    <select name="order_id" class="form-control" required>
                                        <option value="">{{ __('Select order') }}</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}">{{ $order->order_number }} - {{ $order->buyer?->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Invoice Number') }} <span class="text-danger">*</span></label>
                                    <input name="invoice_number" class="form-control" placeholder="{{ __('e.g. INV-2026-001') }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Issue Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="issue_date" class="form-control" value="{{ now()->toDateString() }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Due Date') }}</label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Currency') }} <span class="text-danger">*</span></label>
                                    <input name="currency" class="form-control" value="USD" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" min="0" name="amount" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Tax') }}</label>
                                    <input type="number" step="0.0001" min="0" name="tax_amount" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Paid Amount') }}</label>
                                    <input type="number" step="0.0001" min="0" name="paid_amount" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="issued">{{ __('Issued') }}</option>
                                        <option value="draft">{{ __('Draft') }}</option>
                                        <option value="partially_paid">{{ __('Partially Paid') }}</option>
                                        <option value="paid">{{ __('Paid') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Notes') }}</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="{{ __('Additional terms, payment instructions...') }}"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button class="primary-btn" type="submit">{{ __('Save Invoice') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/garment-invoices.js') }}"></script>
@endpush

@push('style')
<style>
.invoice-summary-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin: 0 0 22px; }.invoice-summary-card { display: flex; align-items: center; gap: 12px; min-height: 84px; padding: 16px; border: 1px solid #e8edf3; border-radius: 10px; background: #fff; }.invoice-summary-icon { display: grid; place-items: center; width: 42px; height: 42px; border-radius: 10px; font-size: 17px; }.invoice-summary-card strong, .invoice-summary-card small { display: block; }.invoice-summary-card strong { color: #1b1c17; font-size: 22px; line-height: 1.15; }.invoice-summary-card small { color: #64748b; font-size: 11px; margin-top: 4px; }.invoice-summary-card.total .invoice-summary-icon { color: #2455a4; background: #eff6ff; }.invoice-summary-card.paid .invoice-summary-icon { color: #16734a; background: #eaf8f0; }.invoice-summary-card.outstanding .invoice-summary-icon { color: #946200; background: #fff7df; }.invoice-summary-card.overdue .invoice-summary-icon { color: #b42318; background: #fff0ee; }
@media (max-width: 991px) { .invoice-summary-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } } @media (max-width: 575px) { .invoice-summary-grid { grid-template-columns: 1fr; gap: 10px; } }
</style>
@endpush
