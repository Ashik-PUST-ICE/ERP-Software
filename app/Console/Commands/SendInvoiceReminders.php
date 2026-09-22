<?php

namespace App\Console\Commands;

use App\Jobs\SendInvoiceEmailJob;
use App\Models\Garments\Invoice;
use App\Models\MailHistory;
use App\Models\User;
use Illuminate\Console\Command;

class SendInvoiceReminders extends Command
{
    protected $signature = 'invoices:send-reminders';
    protected $description = 'Queue reminders for unpaid invoices due soon or overdue';

    public function handle(): int
    {
        if ((int) getOption('app_mail_status', STATUS_ACTIVE) !== STATUS_ACTIVE) {
            $this->warn('Email sending is disabled.');
            return self::SUCCESS;
        }

        $count = 0;
        $ownerId = User::query()->value('id');
        if (!$ownerId) {
            $this->warn('No user is available to own the mail history records.');
            return self::SUCCESS;
        }
        Invoice::query()
            ->whereIn('status', ['issued', 'partially_paid'])
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', today())
            ->update(['status' => 'overdue']);

        Invoice::with('order.buyer')
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<=', now()->addDays(3))
            ->where(function ($query) {
                $query->whereNull('last_reminder_at')->orWhere('last_reminder_at', '<', now()->subDay());
            })
            ->get()
            ->each(function (Invoice $invoice) use (&$count, $ownerId) {
                $email = $invoice->order?->buyer?->email;
                if (!$email) return;

                $history = MailHistory::create([
                    'owner_user_id' => $ownerId,
                    'host' => config('mail.mailers.' . config('mail.default') . '.host'),
                    'email' => $email,
                    'subject' => __('Payment reminder: Invoice :invoice', ['invoice' => $invoice->invoice_number]),
                    'message' => __('Invoice reminder queued for delivery.'),
                    'status' => 2,
                    'date' => now(),
                ]);

                $invoice->update(['last_reminder_at' => now()]);
                SendInvoiceEmailJob::dispatch($history->id, $invoice->id, 'invoice-reminder');
                $count++;
            });

        $this->info("Queued {$count} invoice reminder(s).");
        return self::SUCCESS;
    }
}
