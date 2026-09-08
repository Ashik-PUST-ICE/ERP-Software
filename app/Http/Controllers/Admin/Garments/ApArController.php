<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\AccountingEntry;
use App\Models\Garments\Invoice;

class ApArController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('order.buyer')->latest('due_date')->get();
        $payables = AccountingEntry::whereIn('entry_type', ['payable', 'ap'])->sum('debit');
        $receivables = AccountingEntry::whereIn('entry_type', ['receivable', 'ar'])->sum('debit');

        return view('admin.garments.ap-ar.index', [
            'title' => __('AP / AR Summary'),
            'invoices' => $invoices,
            'payables' => $payables,
            'receivables' => $receivables,
            'invoiceTotal' => $invoices->sum('total_amount'),
            'paidTotal' => $invoices->sum('paid_amount'),
            'outstandingTotal' => $invoices->sum(fn ($invoice) => max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount)),
            'activeGarments' => 'active',
            'activeGarmentApAr' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }
}
