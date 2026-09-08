<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Invoice;
use App\Models\Garments\GarmentPaymentGateway;
use App\Http\Services\Payment\Payment as GatewayPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('admin.garments.invoices.index', [
            'title' => __('Commercial Invoices'),
            'orders' => GarmentOrder::with('buyer')->latest()->get(),
            'invoices' => Invoice::with('order.buyer')->latest('issue_date')->get(),
            'gateways' => GarmentPaymentGateway::with('currencies')
                ->where('status', STATUS_ACTIVE)
                ->orderBy('title')
                ->get(),
            'activeGarments' => 'active',
            'activeGarmentInvoices' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => ['required', 'exists:garment_orders,id'],
            'invoice_number' => ['required', 'string', 'max:80', 'unique:garment_invoices,invoice_number'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'currency' => ['required', 'string', 'max:8'],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,issued,partially_paid,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['tax_amount'] = $data['tax_amount'] ?? 0;
        $data['paid_amount'] = $data['paid_amount'] ?? 0;
        $data['total_amount'] = (float) $data['amount'] + (float) $data['tax_amount'];
        Invoice::create($data);

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Invoice created successfully.'));
    }

    public function print($id)
    {
        return view('admin.garments.invoices.print', [
            'invoice' => Invoice::with('order.buyer')->findOrFail($id),
        ]);
    }

    public function edit($id)
    {
        return view('admin.garments.invoices.form', [
            'title' => __('Edit Commercial Invoice'),
            'invoice' => Invoice::findOrFail($id),
            'orders' => GarmentOrder::with('buyer')->latest()->get(),
            'activeGarments' => 'active',
            'activeGarmentInvoices' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $data = $request->validate([
            'order_id' => ['required', 'exists:garment_orders,id'],
            'invoice_number' => ['required', 'string', 'max:80', 'unique:garment_invoices,invoice_number,' . $invoice->id],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'currency' => ['required', 'string', 'max:8'],
            'amount' => ['required', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,issued,partially_paid,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['tax_amount'] = $data['tax_amount'] ?? 0;
        $data['total_amount'] = (float) $data['amount'] + (float) $data['tax_amount'];
        if ($data['total_amount'] < (float) $invoice->paid_amount) {
            abort(422, __('Invoice total cannot be less than the amount already paid.'));
        }
        $invoice->update($data);

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Invoice updated successfully.'));
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->payments()->exists()) {
            return redirect()->route('admin.garments.invoices.index')
                ->with('error', __('Paid invoices cannot be deleted. Reverse or refund payments first.'));
        }
        $invoice->delete();

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Invoice deleted successfully.'));
    }

    public function payment(Request $request, $id)
    {
        $data = $request->validate([
            'payment_date' => ['required', 'date'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_method' => ['required', 'string', 'max:40'],
            'reference' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($data, $id) {
            $invoice = Invoice::lockForUpdate()->findOrFail($id);
            $outstanding = (float) $invoice->total_amount - (float) $invoice->paid_amount;
            if ((float) $data['amount'] > $outstanding) {
                abort(422, __('Payment exceeds invoice outstanding balance.'));
            }

            $invoice->payments()->create(array_merge($data, [
                'gateway' => 'manual',
                'gateway_status' => 'success',
                'paid_at' => now(),
            ]));
            $invoice->paid_amount = (float) $invoice->paid_amount + (float) $data['amount'];
            $invoice->status = (float) $invoice->paid_amount >= (float) $invoice->total_amount ? 'paid' : 'partially_paid';
            $invoice->save();
        });

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Payment recorded successfully.'));
    }

    public function checkout(Request $request, $id)
    {
        $data = $request->validate([
            'gateway' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'gt:0'],
        ]);

        $invoice = Invoice::findOrFail($id);
        $gateway = GarmentPaymentGateway::where('slug', $data['gateway'])
            ->where('status', STATUS_ACTIVE)
            ->firstOrFail();
        if (!$gateway->currencies()->where('currency', $invoice->currency)->exists()) {
            abort(422, __('The selected gateway does not support the invoice currency.'));
        }
        $outstanding = (float) $invoice->total_amount - (float) $invoice->paid_amount;
        if ((float) $data['amount'] > $outstanding) {
            abort(422, __('Payment exceeds invoice outstanding balance.'));
        }

        $payment = $invoice->payments()->create([
            'payment_date' => now()->toDateString(),
            'amount' => $data['amount'],
            'payment_method' => $data['gateway'],
            'gateway' => $data['gateway'],
            'gateway_status' => 'pending',
        ]);

        $gatewayPayment = new GatewayPayment($gateway->slug, [
            'id' => $payment->id,
            'currency' => $invoice->currency,
            'gateway' => $gateway,
            'gateway_currency' => $gateway->currencies()->where('currency', $invoice->currency)->first(),
            'callback_url' => route('admin.garments.invoices.payment-callback', [
                'invoice' => $invoice->id,
                'payment' => $payment->id,
            ]),
        ]);
        $result = $gatewayPayment->makePayment((float) $data['amount']);

        if (!($result['success'] ?? false)) {
            $payment->update([
                'gateway_status' => 'failed',
                'gateway_response' => ['message' => $result['message'] ?? __('Gateway payment failed.')],
            ]);

            return back()->with('error', $result['message'] ?? __('Gateway payment failed.'));
        }

        $payment->update([
            'gateway_payment_id' => $result['payment_id'] ?? null,
            'gateway_response' => $result,
        ]);

        return redirect()->away($result['redirect_url']);
    }

    public function paymentCallback(Request $request, $invoiceId, $paymentId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $payment = $invoice->payments()->whereKey($paymentId)->firstOrFail();

        if ($payment->gateway_status === 'success') {
            return redirect()->route('admin.garments.invoices.index')->with('success', __('Payment already confirmed.'));
        }

        $gateway = GarmentPaymentGateway::where('slug', $payment->gateway)->firstOrFail();
        $gatewayPayment = new GatewayPayment($payment->gateway, [
            'id' => $payment->id,
            'currency' => $invoice->currency,
            'gateway' => $gateway,
            'gateway_currency' => $gateway->currencies()->where('currency', $invoice->currency)->firstOrFail(),
            'callback_url' => route('admin.garments.invoices.payment-callback', [
                'invoice' => $invoice->id,
                'payment' => $payment->id,
            ]),
        ]);
        $confirmation = $gatewayPayment->paymentConfirmation(
            $payment->gateway_payment_id ?: $request->input('payment_id', $request->input('sessionkey')),
            $request->input('PayerID', $request->input('payer_id'))
        );

        if (!($confirmation['success'] ?? false) || ($confirmation['data']['payment_status'] ?? null) !== 'success') {
            $payment->update(['gateway_status' => 'failed', 'gateway_response' => $confirmation]);
            return redirect()->route('admin.garments.invoices.index')->with('error', __('Payment could not be confirmed.'));
        }

        DB::transaction(function () use ($invoiceId, $paymentId, $confirmation) {
            $invoice = Invoice::lockForUpdate()->findOrFail($invoiceId);
            $payment = $invoice->payments()->lockForUpdate()->findOrFail($paymentId);
            if ($payment->gateway_status === 'success') {
                return;
            }
            $amount = (float) $payment->amount;
            $outstanding = (float) $invoice->total_amount - (float) $invoice->paid_amount;
            if ($amount > $outstanding) {
                abort(422, __('Payment exceeds invoice outstanding balance.'));
            }
            $payment->update([
                'gateway_status' => 'success',
                'gateway_transaction_id' => $confirmation['data']['transaction_id'] ?? null,
                'gateway_response' => $confirmation,
                'paid_at' => now(),
            ]);
            $invoice->update([
                'paid_amount' => (float) $invoice->paid_amount + $amount,
                'status' => ((float) $invoice->paid_amount + $amount) >= (float) $invoice->total_amount
                    ? 'paid'
                    : 'partially_paid',
            ]);
        });

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Payment confirmed successfully.'));
    }
}
