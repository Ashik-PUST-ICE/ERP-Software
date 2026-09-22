<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Welcome Email',
                'slug' => 'welcome-email',
                'subject' => 'Welcome to {{app_name}}',
                'body' => "Hello {{name}},\n\nWelcome to {{app_name}}. We are happy to have you with us.\n\nRegards,\n{{app_name}}",
                'variables' => '{{name}}, {{email}}, {{app_name}}, {{date}}',
            ],
            [
                'name' => 'Payment Confirmation',
                'slug' => 'payment-confirmation',
                'subject' => 'Payment confirmation - {{app_name}}',
                'body' => "Hello {{name}},\n\nYour payment has been received successfully. Thank you for using {{app_name}}.\n\nRegards,\n{{app_name}}",
                'variables' => '{{name}}, {{email}}, {{app_name}}, {{date}}',
            ],
            [
                'name' => 'Account Notification',
                'slug' => 'account-notification',
                'subject' => 'Important account notification',
                'body' => "Hello {{name}},\n\nThis is an important notification regarding your account. Please contact support if you need help.\n\nRegards,\n{{app_name}}",
                'variables' => '{{name}}, {{email}}, {{app_name}}, {{date}}',
            ],
            [
                'name' => 'Garments Alert',
                'slug' => 'garments-alert',
                'subject' => 'Garments operation alert - {{app_name}}',
                'body' => "Hello {{name}},\n\nA garments operation needs your attention. Please sign in to {{app_name}} to review the latest update.\n\nRegards,\n{{app_name}}",
                'variables' => '{{name}}, {{email}}, {{app_name}}, {{date}}',
            ],
            [
                'name' => 'Invoice Issued',
                'slug' => 'invoice-issued',
                'subject' => 'Invoice {{invoice_number}} from {{app_name}}',
                'body' => "Hello {{name}},\n\nYour invoice has been issued.\n\nInvoice: {{invoice_number}}\nOrder: {{order_number}}\nTotal: {{total}}\nDue date: {{due_date}}\n\nYou can view the invoice using the link below.\n{{invoice_link}}\n\nRegards,\n{{app_name}}",
                'variables' => '{{name}}, {{email}}, {{app_name}}, {{invoice_number}}, {{order_number}}, {{total}}, {{due_date}}, {{invoice_link}}',
            ],
        ];

        foreach ($templates as $template) {
            EmailTemplate::updateOrCreate(
                ['slug' => $template['slug']],
                [...$template, 'status' => true]
            );
        }
    }
}
