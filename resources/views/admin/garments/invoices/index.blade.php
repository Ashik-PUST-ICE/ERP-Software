@extends('auto_posts.admin.layouts.admin')
@push('title') {{ $title }} @endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ $title }}</h2>
    <button class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal">
        <i class="fa fa-plus me-2"></i>{{ __('Create Invoice') }}
    </button>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <table class="display primary-table">
                    <thead>
                        <tr>
                            <th>{{ __('Invoice') }}</th>
                            <th>{{ __('Order') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Issue Date') }}</th>
                            <th>{{ __('Due Date') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Paid') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->order?->order_number }}</td>
                            <td>{{ $invoice->order?->buyer?->company_name }}</td>
                            <td>{{ $invoice->issue_date?->format('d M Y') }}</td>
                            <td>{{ $invoice->due_date?->format('d M Y') ?: '-' }}</td>
                            <td>{{ $invoice->currency }} {{ number_format((float) $invoice->total_amount, 2) }}</td>
                            <td>{{ number_format((float) $invoice->paid_amount, 2) }}</td>
                            <td><span class="zBadge zBadge-complete">{{ __(ucwords(str_replace('_', ' ', $invoice->status))) }}</span></td>
                            <td>
                                <button class="primary-btn" data-bs-toggle="modal" data-bs-target="#payment-modal-{{ $invoice->id }}">{{ __('Payment') }}</button>
                                @if((float) $invoice->total_amount > (float) $invoice->paid_amount && $gateways->contains(fn($gateway) => $gateway->currencies->contains('currency', $invoice->currency)))
                                    <button class="primary-btn" data-bs-toggle="modal" data-bs-target="#gateway-payment-modal-{{ $invoice->id }}">{{ __('Online Payment') }}</button>
                                @endif
                                <a class="primary-btn" href="{{ route('admin.garments.invoices.edit', $invoice->id) }}">{{ __('Edit') }}</a>
                                <a class="primary-btn" target="_blank" href="{{ route('admin.garments.invoices.print', $invoice->id) }}">{{ __('Print') }}</a>
                                <form method="post" action="{{ route('admin.garments.invoices.destroy', $invoice->id) }}" class="d-inline">
                                    @csrf @method('delete')
                                    <button class="primary-btn" type="submit" onclick="return confirm('{{ __('Delete this invoice?') }}')">{{ __('Delete') }}</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="text-center">{{ __('No invoices found.') }}</td></tr>
                        @endforelse
                    </tbody>
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
            <form method="post" action="{{ route('admin.garments.invoices.payments.store', $invoice->id) }}">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Record Payment') }} - {{ $invoice->invoice_number }}</h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <p class="mb-0">{{ __('Outstanding') }}: {{ $invoice->currency }} {{ number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Payment Date') }}</label>
                                    <input type="date" name="payment_date" class="form-control" value="{{ now()->toDateString() }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Amount') }}</label>
                                    <input type="number" name="amount" class="form-control" step="0.0001" min="0.0001" max="{{ max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Method') }}</label>
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
                                    <input name="reference" class="form-control">
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
                                <p class="mb-0">{{ __('Outstanding') }}: {{ $invoice->currency }} {{ number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Gateway') }}</label>
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
                                    <label class="form-label">{{ __('Amount') }}</label>
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
            <form method="post" action="{{ route('admin.garments.invoices.store') }}">
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
                                    <label class="form-label">{{ __('Order') }}</label>
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
                                    <label class="form-label">{{ __('Invoice Number') }}</label>
                                    <input name="invoice_number" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Issue Date') }}</label>
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
                                    <label class="form-label">{{ __('Currency') }}</label>
                                    <input name="currency" class="form-control" value="USD" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Amount') }}</label>
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
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select name="status" class="form-control">
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
                                    <textarea name="notes" class="form-control" rows="3"></textarea>
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
