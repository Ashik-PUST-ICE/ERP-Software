<?php

namespace App\Jobs;

use App\Mail\AdminManualEmail;
use App\Models\MailHistory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SendAdminEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(public int $historyId)
    {
    }

    public function handle(): void
    {
        $history = MailHistory::find($this->historyId);
        if (!$history || $history->status === 1) {
            return;
        }

        try {
            Mail::to($history->email)->send(new AdminManualEmail($history->subject, $history->message));
            $history->update(['status' => 1, 'error' => null]);
        } catch (\Throwable $exception) {
            $history->update(['status' => 0, 'error' => Str::limit($exception->getMessage(), 250)]);
            throw $exception;
        }
    }
}
