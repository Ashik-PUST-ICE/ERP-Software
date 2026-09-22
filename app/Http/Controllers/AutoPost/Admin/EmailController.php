<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendAdminEmailJob;
use App\Models\EmailTemplate;
use App\Models\MailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmailController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::active()->orderBy('name')->get();

        return view('auto_posts.admin.email.index', [
            'title' => __('Email Center'),
            'histories' => MailHistory::latest('date')->paginate(15),
            'templates' => $templates,
            'templateData' => $templates->keyBy('id')->map(function ($template) {
                return [
                    'name' => $template->name,
                    'subject' => $template->subject,
                    'body' => $template->body,
                    'variables' => $template->variables,
                    'status' => (bool) $template->status,
                ];
            }),
            'activeEmailCenter' => 'active',
        ]);
    }

    public function history()
    {
        $histories = MailHistory::latest('date')->paginate(15);

        return response()->json([
            'data' => $histories->getCollection()->map(fn ($history) => [
                'id' => $history->id,
                'email' => $history->email,
                'subject' => $history->subject,
                'status' => $history->status,
                'error' => $history->error,
                'date' => optional($history->date)->format('d M Y, h:i A'),
            ])->values(),
            'pagination' => [
                'current_page' => $histories->currentPage(),
                'last_page' => $histories->lastPage(),
                'total' => $histories->total(),
            ],
        ]);
    }

    public function send(Request $request)
    {
        if ((int) getOption('app_mail_status', STATUS_ACTIVE) !== STATUS_ACTIVE) {
            return back()->withInput()->withErrors([
                'recipients' => __('Email sending is disabled. Enable email settings first.'),
            ]);
        }

        $data = $request->validate([
            'recipients' => ['required', 'string', 'max:5000'],
            'template_id' => ['nullable', 'integer', 'exists:email_templates,id'],
            'subject' => ['nullable', 'string', 'max:255', 'required_without:template_id'],
            'message' => ['nullable', 'string', 'max:50000', 'required_without:template_id'],
        ]);

        $template = !empty($data['template_id'])
            ? EmailTemplate::active()->find($data['template_id'])
            : null;
        if ($template) {
            $data['subject'] = $template->subject;
            $data['message'] = $template->body;
        }

        $recipients = collect(preg_split('/[,;\s]+/', $data['recipients'], -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn ($email) => Str::lower(trim($email)))
            ->unique()
            ->values();

        $invalid = $recipients->first(fn ($email) => !filter_var($email, FILTER_VALIDATE_EMAIL));
        if ($invalid) {
            return back()->withInput()->withErrors([
                'recipients' => __('Invalid email address: :email', ['email' => $invalid]),
            ]);
        }

        foreach ($recipients as $email) {
            $subject = $this->replaceVariables($data['subject'], $email);
            $message = $this->replaceVariables($data['message'], $email);
            $history = MailHistory::create([
                'owner_user_id' => auth()->id(),
                'host' => config('mail.mailers.' . config('mail.default') . '.host'),
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'status' => 2,
                'user_id' => auth()->id(),
                'date' => now(),
            ]);
            SendAdminEmailJob::dispatch($history->id);
        }

        return to_route('admin.email.index')->with('success', __('Email queued successfully. Delivery status is available in history.'));
    }

    private function replaceVariables(string $content, string $email): string
    {
        $name = (string) Str::of(str_replace(['_', '.', '-'], ' ', Str::before($email, '@')))->title();

        return str_replace([
            '{{name}}', '{{email}}', '{{app_name}}', '{{date}}',
        ], [
            $name, $email, getOption('app_name'), now()->format('d M Y'),
        ], $content);
    }

    public function retry(int $id)
    {
        $history = MailHistory::findOrFail($id);
        $history->update(['status' => 2, 'error' => null, 'date' => now()]);
        SendAdminEmailJob::dispatch($history->id);

        return back()->with('success', __('Email has been queued for retry.'));
    }

    public function storeTemplate(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:50000'],
            'variables' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['slug'] = $this->uniqueTemplateSlug($data['name']);
        $data['status'] = $request->boolean('status', true);
        EmailTemplate::create($data);

        return back()->with('success', __('Email template created successfully.'));
    }

    public function updateTemplate(Request $request, int $id)
    {
        $template = EmailTemplate::findOrFail($id);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:50000'],
            'variables' => ['nullable', 'string', 'max:1000'],
            'status' => ['nullable', 'boolean'],
        ]);

        $data['status'] = $request->boolean('status');
        $template->update($data);

        return back()->with('success', __('Email template updated successfully.'));
    }

    public function destroyTemplate(int $id)
    {
        EmailTemplate::findOrFail($id)->delete();
        return back()->with('success', __('Email template deleted successfully.'));
    }

    private function uniqueTemplateSlug(string $name): string
    {
        $slug = Str::slug($name) ?: 'email-template';
        $base = $slug;
        $counter = 1;
        while (EmailTemplate::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter++;
        }
        return $slug;
    }
}
