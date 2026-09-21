<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Garments\AccountingEntry;
use App\Models\Garments\Invoice;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ApArController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $invoices = Invoice::with('order.buyer')->latest('due_date');

            $allInvoices = Invoice::get();
            $invoiceTotal = $allInvoices->sum('total_amount');
            $paidTotal = $allInvoices->sum('paid_amount');
            $outstandingTotal = $allInvoices->sum(fn ($invoice) => max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount));
            $payables = AccountingEntry::whereIn('entry_type', ['payable', 'ap'])->sum('debit');

            $stats = [
                'invoiceTotal' => number_format((float) $invoiceTotal, 2),
                'paidTotal' => number_format((float) $paidTotal, 2),
                'outstandingTotal' => number_format((float) $outstandingTotal, 2),
                'payables' => number_format((float) $payables, 2),
            ];

            return datatables($invoices)
                ->addIndexColumn()
                ->addColumn('sl', function ($row) {
                    static $count = 0;
                    return ++$count;
                })
                ->addColumn('invoice_number', fn ($row) => e($row->invoice_number))
                ->addColumn('buyer_name', fn ($row) => e($row->order?->buyer?->company_name ?? '-'))
                ->addColumn('due_date_display', fn ($row) => $row->due_date ? $row->due_date->format('d M Y') : '-')
                ->addColumn('total_display', fn ($row) => e($row->currency) . ' ' . number_format((float) $row->total_amount, 2))
                ->addColumn('outstanding_display', fn ($row) => e($row->currency) . ' ' . number_format(max(0, (float) $row->total_amount - (float) $row->paid_amount), 2))
                ->addColumn('status', function ($row) {
                    $statusClass = match ($row->status) {
                        'paid' => 'zBadge-complete',
                        'partially_paid' => 'zBadge-warning',
                        'overdue', 'cancelled' => 'zBadge-deactive',
                        default => 'zBadge-active',
                    };
                    return '<div class="zBadge ' . $statusClass . '">' . __(ucwords(str_replace('_', ' ', $row->status))) . '</div>';
                })
                ->rawColumns(['status'])
                ->with('stats', $stats)
                ->make(true);
        }

        return view('admin.garments.ap-ar.index', [
            'title' => __('AP / AR Summary'),
            'activeGarments' => 'active',
            'activeGarmentApAr' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }
}
