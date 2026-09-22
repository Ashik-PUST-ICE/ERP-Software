<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Models\Garments\Buyer;
use App\Models\Garments\GarmentOrder;
use App\Models\Garments\Invoice;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $term = trim((string) $request->input('q'));
        if (mb_strlen($term) < 2) {
            return response()->json(['data' => []]);
        }

        $like = '%' . $term . '%';
        $results = collect();

        Buyer::query()
            ->where(fn ($query) => $query->where('company_name', 'like', $like)->orWhere('buyer_code', 'like', $like)->orWhere('email', 'like', $like))
            ->limit(5)->get(['id', 'company_name', 'buyer_code'])
            ->each(fn ($buyer) => $results->push([
                'type' => __('Buyer'), 'label' => $buyer->company_name, 'meta' => $buyer->buyer_code,
                'url' => route('admin.garments.buyers.edit', $buyer->id),
            ]));

        GarmentOrder::query()
            ->where('order_number', 'like', $like)
            ->limit(5)->get(['id', 'order_number', 'status'])
            ->each(fn ($order) => $results->push([
                'type' => __('Order'), 'label' => $order->order_number, 'meta' => ucfirst((string) $order->status),
                'url' => route('admin.garments.orders.edit', $order->id),
            ]));

        Invoice::query()
            ->where('invoice_number', 'like', $like)
            ->limit(5)->get(['id', 'invoice_number', 'status'])
            ->each(fn ($invoice) => $results->push([
                'type' => __('Invoice'), 'label' => $invoice->invoice_number, 'meta' => ucfirst((string) $invoice->status),
                'url' => route('admin.garments.invoices.edit', $invoice->id),
            ]));

        return response()->json(['data' => $results->take(12)->values()]);
    }
}
