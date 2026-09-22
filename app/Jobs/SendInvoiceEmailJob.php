<?php

namespace App\Jobs;

use App\Mail\InvoiceEmail;
use App\Models\EmailTemplate;
use App\Models\Garments\Invoice;
use App\Models\MailHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendInvoiceEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(public int $historyId, public int $invoiceId)
    {
    }

    public function handle(): void
    {
        $history = MailHistory::find($this->historyId);
        $invoice = Invoice::with('order.buyer')->find($this->invoiceId);

        if (!$history || !$invoice || $history->status === 1) {
            return;
        }

        try {
            $buyer = $invoice->order?->buyer;
            $template = EmailTemplate::active()->where('slug', 'invoice-issued')->first();
            $subject = $template?->subject ?: 'Invoice {{invoice_number}} from {{app_name}}';
            $message = $template?->body ?: "Hello {{name}},\n\nPlease find your invoice details below.\n\nInvoice: {{invoice_number}}\nTotal: {{total}}\nDue date: {{due_date}}\n\nRegards,\n{{app_name}}";
            $variables = [
                '{{name}}' => $buyer?->contact_person ?: $buyer?->company_name ?: 'Customer',
                '{{email}}' => $buyer?->email ?: $history->email,
                '{{app_name}}' => getOption('app_name'),
                '{{invoice_number}}' => $invoice->invoice_number,
                '{{order_number}}' => $invoice->order?->order_number ?: '-',
                '{{total}}' => $invoice->currency . ' ' . number_format((float) $invoice->total_amount, 2),
                '{{due_date}}' => $invoice->due_date?->format('d M Y') ?: 'Not specified',
                '{{invoice_link}}' => route('admin.garments.invoices.print', $invoice->id),
            ];
            $subject = str_replace(array_keys($variables), array_values($variables), $subject);
            $message = str_replace(array_keys($variables), array_values($variables), $message);

            Mail::to($history->email)->send(new InvoiceEmail($invoice, $subject, $message));
            $history->update(['status' => 1, 'subject' => $subject, 'message' => $message, 'error' => null]);
        } catch (\Throwable $exception) {
            $history->update(['status' => 0, 'error' => Str::limit($exception->getMessage(), 250)]);
            throw $exception;
        }
    }
}
