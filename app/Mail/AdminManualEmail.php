<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminManualEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $mailMessage,
    ) {
    }

    public function build(): static
    {
        return $this
            ->from(getOption('MAIL_FROM_ADDRESS'), getOption('app_name'))
            ->subject($this->mailSubject)
            ->view('mail.admin-manual-email');
    }
}
