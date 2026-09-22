<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Jobs\SendInvoiceEmailJob;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Invoice;
use App\Models\Garments\GarmentPaymentGateway;
use App\Models\MailHistory;
use App\Http\Services\Payment\Payment as GatewayPayment;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::with('order.buyer')->latest('issue_date');

            return datatables($invoices)
                ->addIndexColumn()
                ->addColumn('sl', function ($row) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('invoice_number', fn ($row) => e($row->invoice_number))
                ->addColumn('order_number', fn ($row) => e($row->order?->order_number ?? '-'))
                ->addColumn('buyer_name', fn ($row) => e($row->order?->buyer?->company_name ?? '-'))
                ->addColumn('issue_date_display', fn ($row) => $row->issue_date ? $row->issue_date->format('d M Y') : '-')
                ->addColumn('due_date_display', fn ($row) => $row->due_date ? $row->due_date->format('d M Y') : '-')
                ->addColumn('total_display', fn ($row) => e($row->currency) . ' ' . number_format((float) $row->total_amount, 2))
                ->addColumn('paid_display', fn ($row) => number_format((float) $row->paid_amount, 2))
                ->addColumn('status', function ($row) {
                    $statusClass = match ($row->status) {
                        'paid' => 'zBadge-complete',
                        'partially_paid' => 'zBadge-warning',
                        'overdue', 'cancelled' => 'zBadge-deactive',
                        default => 'zBadge-active',
                    };
                    return '<div class="zBadge ' . $statusClass . '">' . __(ucwords(str_replace('_', ' ', $row->status))) . '</div>';
                })
                ->addColumn('action', function ($row) {
                    $hasOutstanding = ((float) $row->total_amount > (float) $row->paid_amount);
                    $paymentBtn = '<li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#payment-modal-' . $row->id . '">' . __('Record Payment') . '</a></li>';
                    $onlineBtn = $hasOutstanding ? '<li><a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#gateway-payment-modal-' . $row->id . '">' . __('Online Payment') . '</a></li>' : '';
                    $sendEmailBtn = $row->order?->buyer?->email
                        ? '<li><form method="POST" action="' . route('admin.garments.invoices.send-email', $row->id) . '">' . csrf_field() . '<button type="submit" class="dropdown-item" onclick="return confirm(\'' . __('Send this invoice to the buyer by email?') . '\')"><i class="fa-regular fa-envelope me-2"></i>' . __('Send Invoice Email') . '</button></form></li>'
                        : '';
                    $editBtn = '<li><a class="dropdown-item" href="' . route('admin.garments.invoices.edit', $row->id) . '">' . __('Edit') . '</a></li>';
                    $printBtn = '<li><a class="dropdown-item" target="_blank" href="' . route('admin.garments.invoices.print', $row->id) . '">' . __('Print') . '</a></li>';
                    $deleteBtn = '<li><a class="dropdown-item" href="javascript:void(0)" onclick="deleteItem(\'' . route('admin.garments.invoices.destroy', $row->id) . '\', \'garmentInvoiceDataTable\')">' . __('Delete') . '</a></li>';

                    return '<div class="inline-flex">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                ' . $paymentBtn . '
                                ' . $onlineBtn . '
                                ' . $sendEmailBtn . '
                                ' . $editBtn . '
                                ' . $printBtn . '
                                ' . $deleteBtn . '
                            </ul>
                        </div>
                    </div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        $invoiceSummary = [
            'total' => Invoice::count(),
            'paid' => Invoice::where('status', 'paid')->count(),
            'outstanding' => Invoice::whereNotIn('status', ['paid', 'cancelled'])->whereColumn('paid_amount', '<', 'total_amount')->count(),
            'overdue' => Invoice::where('status', 'overdue')->count(),
        ];

        return view('admin.garments.invoices.index', [
            'title' => __('Commercial Invoices'),
            'orders' => GarmentOrder::with('buyer')->latest()->get(),
            'invoices' => Invoice::with('order.buyer')->latest('issue_date')->get(),
            'invoiceSummary' => $invoiceSummary,
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

        if ($request->ajax()) {
            return $this->success([], __('Invoice created successfully.'));
        }

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Invoice created successfully.'));
    }

    public function print($id)
    {
        return view('admin.garments.invoices.print', [
            'invoice' => Invoice::with('order.buyer')->findOrFail($id),
        ]);
    }

    public function printReport()
    {
        return view('admin.garments.invoices.print-report', [
            'invoices' => Invoice::with('order.buyer')->latest('issue_date')->get(),
            'title' => __('Commercial Invoice Report'),
        ]);
    }

    public function export()
    {
        $invoices = Invoice::with('order.buyer')->latest('issue_date')->get();
        $filename = 'invoices-' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($invoices) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Invoice', 'Order', 'Buyer', 'Issue Date', 'Due Date', 'Currency', 'Total', 'Paid', 'Outstanding', 'Status']);
            foreach ($invoices as $invoice) {
                fputcsv($handle, [
                    $invoice->invoice_number,
                    $invoice->order?->order_number ?: '-',
                    $invoice->order?->buyer?->company_name ?: '-',
                    optional($invoice->issue_date)->format('Y-m-d'),
                    optional($invoice->due_date)->format('Y-m-d'),
                    $invoice->currency,
                    number_format((float) $invoice->total_amount, 2, '.', ''),
                    number_format((float) $invoice->paid_amount, 2, '.', ''),
                    number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2, '.', ''),
                    $invoice->status,
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function sendEmail($id)
    {
        if ((int) getOption('app_mail_status', STATUS_ACTIVE) !== STATUS_ACTIVE) {
            return back()->with('error', __('Email sending is disabled. Enable email settings first.'));
        }

        $invoice = Invoice::with('order.buyer')->findOrFail($id);
        $email = $invoice->order?->buyer?->email;
        if (!$email) {
            return back()->with('error', __('The buyer does not have an email address.'));
        }

        $history = MailHistory::create([
            'owner_user_id' => auth()->id(),
            'host' => config('mail.mailers.' . config('mail.default') . '.host'),
            'email' => $email,
            'subject' => __('Invoice :invoice', ['invoice' => $invoice->invoice_number]),
            'message' => __('Invoice email queued for delivery.'),
            'status' => 2,
            'user_id' => auth()->id(),
            'date' => now(),
        ]);

        SendInvoiceEmailJob::dispatch($history->id, $invoice->id);

        return back()->with('success', __('Invoice email queued successfully.'));
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
            if ($request->ajax()) {
                return $this->error([], __('Invoice total cannot be less than the amount already paid.'));
            }
            abort(422, __('Invoice total cannot be less than the amount already paid.'));
        }
        $invoice->update($data);

        if ($request->ajax()) {
            return $this->success([], __('Invoice updated successfully.'));
        }

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Invoice updated successfully.'));
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->payments()->exists()) {
            if (request()->ajax()) {
                return $this->error([], __('Paid invoices cannot be deleted. Reverse or refund payments first.'));
            }
            return redirect()->route('admin.garments.invoices.index')
                ->with('error', __('Paid invoices cannot be deleted. Reverse or refund payments first.'));
        }
        $invoice->delete();

        if (request()->ajax()) {
            return $this->success([], __('Invoice deleted successfully.'));
        }

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

        if ($request->ajax()) {
            return $this->success([], __('Payment recorded successfully.'));
        }

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
