<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Invoice;
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

            $invoice->payments()->create($data);
            $invoice->paid_amount = (float) $invoice->paid_amount + (float) $data['amount'];
            $invoice->status = (float) $invoice->paid_amount >= (float) $invoice->total_amount ? 'paid' : 'partially_paid';
            $invoice->save();
        });

        return redirect()->route('admin.garments.invoices.index')->with('success', __('Payment recorded successfully.'));
    }
}
