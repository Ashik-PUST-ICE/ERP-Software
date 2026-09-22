@extends('auto_posts.admin.layouts.admin')

@push('title') {{ $title }} @endpush

@section('content')
<div class="section-title d-flex justify-content-between align-items-center">
    <div>
        <h2 class="title mb-1">{{ __($title) }}</h2>
        <p class="text-muted mb-0">{{ __('Choose a template and send an email in a few clicks.') }}</p>
    </div>
    <button type="button" class="primary-btn d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#send-template-modal" data-template-id="">
        <i class="fa-solid fa-pen-to-square"></i>{{ __('Custom Email') }}
    </button>
</div>

<div class="settings-page-area email-center-page">
    <div class="settings-page-right w-100">
        <div class="section-wrap email-template-panel">
            <div class="email-panel-heading">
                <div>
                    <h5 class="email-section-title mb-1"><span></span><i class="fa-solid fa-layer-group"></i>{{ __('Email Templates') }}</h5>
                    <p class="text-muted mb-0">{{ __('Click any card to open the email composer with the template ready.') }}</p>
                </div>
                <div class="email-variable-list"><code>&#123;&#123;name&#125;&#125;</code><code>&#123;&#123;email&#125;&#125;</code><code>&#123;&#123;date&#125;&#125;</code></div>
            </div>
            <div class="row g-3 mt-1">
                @forelse($templates as $template)
                    <div class="col-md-6 col-xl-4">
                        <button type="button" class="email-template-card w-100 text-start" data-bs-toggle="modal" data-bs-target="#send-template-modal" data-template-id="{{ $template->id }}">
                            <div class="email-card-icon"><i class="fa-solid {{ ['fa-hand-sparkles', 'fa-receipt', 'fa-bell', 'fa-shirt'][$loop->index % 4] }}"></i></div>
                            <div class="email-card-content">
                                <h6>{{ $template->name }}</h6>
                                <p>{{ \Illuminate\Support\Str::limit($template->subject, 64) }}</p>
                                <span>{{ __('Use template') }} <i class="fa-solid fa-arrow-right"></i></span>
                            </div>
                        </button>
                    </div>
                @empty
                    <div class="col-12"><div class="email-empty-state"><i class="fa-regular fa-folder-open"></i><p>{{ __('No active email templates found.') }}</p></div></div>
                @endforelse
            </div>
        </div>

        <div class="section-wrap mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="email-section-title mb-1"><span></span><i class="fa-solid fa-clock-rotate-left"></i>{{ __('Email History') }}</h5>
                    <small class="text-muted">{{ __('Pending emails are handled by the queue worker.') }}</small>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle email-history-table">
                    <thead><tr><th>{{ __('Recipient') }}</th><th>{{ __('Subject') }}</th><th>{{ __('Status') }}</th><th>{{ __('Date') }}</th><th class="text-end">{{ __('Action') }}</th></tr></thead>
                            <tbody id="email-history-body">
                                <tr><td colspan="5" class="text-muted text-center py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Loading email history...') }}</td></tr>
                            </tbody>
                </table>
            </div>
            <div id="email-history-pagination" class="mt-3"></div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo" id="send-template-modal" tabindex="-1" aria-labelledby="send-template-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content zModalTwo-content">
            <div class="modal-header zModalTwo-header">
                <h5 class="modal-title" id="send-template-modal-label"><i class="fa-solid fa-paper-plane me-2"></i>{{ __('Send Email') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <form action="{{ route('admin.email.send') }}" method="POST">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <input type="hidden" name="template_id" id="modal_template_id" value="">
                    <div class="email-selected-template" id="selected-template-name"><i class="fa-solid fa-pen-to-square"></i>{{ __('Custom email') }}</div>
                    <div class="row gy-3">
                        <div class="col-lg-7">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Recipients') }} <span class="required">*</span></label>
                                <input type="text" name="recipients" value="{{ old('recipients') }}" class="form-control" placeholder="name@example.com, another@example.com" required>
                                <small class="text-muted">{{ __('Separate multiple addresses with comma, semicolon or space.') }}</small>
                                @error('recipients') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Subject') }} <span class="required">*</span></label>
                                <input type="text" name="subject" id="modal_email_subject" value="{{ old('subject') }}" class="form-control" maxlength="255">
                                @error('subject') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">{{ __('Message') }} <span class="required">*</span></label>
                                <textarea name="message" id="modal_email_message" rows="9" class="form-control">{{ old('message') }}</textarea>
                                @error('message') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="email-modal-preview">
                                <div class="email-modal-preview-heading"><i class="fa-solid fa-eye"></i>{{ __('Email Preview') }}</div>
                                <div class="email-modal-preview-subject" id="modal_preview_subject">{{ __('Your subject will appear here') }}</div>
                                <div class="email-modal-preview-message" id="modal_preview_message">{{ __('Your message preview will appear here.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer zModalTwo-footer">
                    <button type="button" class="primary-btn-outline" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="primary-btn d-inline-flex align-items-center gap-2"><i class="fa-solid fa-paper-plane"></i>{{ __('Send Email') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .email-center-page .section-wrap { border: 1px solid #eef1f5; border-radius: 12px; background: #fff; padding: 24px; }
    .email-panel-heading { display: flex; justify-content: space-between; align-items: center; gap: 20px; }
    .email-section-title { color: #0f172a; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .email-section-title > span { width: 4px; height: 21px; background: #4778c7; border-radius: 4px; display: inline-block; }
    .email-section-title i { color: #4778c7; font-size: 15px; }
    .email-variable-list { display: flex; flex-wrap: wrap; gap: 6px; }
    .email-variable-list code { color: #2455a4; background: #f7fbff; border: 1px solid #dbeafe; border-radius: 5px; padding: 4px 7px; font-size: 12px; }
    .email-template-card { min-height: 145px; display: flex; align-items: flex-start; gap: 14px; padding: 18px; border: 1px solid #e8edf3; border-radius: 12px; background: #fff; transition: .2s ease; }
    .email-template-card:hover { border-color: #4778c7; box-shadow: 0 8px 24px rgba(30, 64, 175, .10); transform: translateY(-2px); }
    .email-card-icon { width: 43px; height: 43px; flex: 0 0 43px; display: grid; place-items: center; border-radius: 10px; color: #4778c7; background: #eff6ff; font-size: 17px; }
    .email-card-content h6 { color: #0f172a; margin: 2px 0 7px; font-weight: 600; }
    .email-card-content p { color: #64748b; font-size: 13px; line-height: 1.45; min-height: 38px; margin: 0 0 9px; }
    .email-card-content span { color: #4778c7; font-size: 12px; font-weight: 600; }
    .email-card-content span i { margin-left: 5px; }
    .email-empty-state { text-align: center; padding: 30px; color: #94a3b8; }.email-empty-state i { font-size: 30px; margin-bottom: 8px; }.email-empty-state p { margin: 0; }
    .email-status { display: inline-flex; align-items: center; gap: 6px; border-radius: 20px; padding: 5px 10px; font-size: 12px; font-weight: 600; }.email-status.sent { color: #16734a; background: #eaf8f0; }.email-status.pending { color: #946200; background: #fff7df; }.email-status.failed { color: #b42318; background: #fff0ee; }
    .email-history-table thead th { color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; }
    .email-selected-template { display: inline-flex; align-items: center; gap: 8px; color: #2455a4; background: #eff6ff; border: 1px solid #dbeafe; border-radius: 7px; padding: 8px 11px; margin-bottom: 18px; font-size: 13px; font-weight: 600; }
    .email-modal-preview { height: 100%; min-height: 275px; padding: 16px; border: 1px solid #e5eaf0; border-radius: 10px; background: #f8fafc; }
    .email-modal-preview-heading { color: #4778c7; font-weight: 600; font-size: 13px; padding-bottom: 12px; margin-bottom: 15px; border-bottom: 1px solid #e5eaf0; }
    .email-modal-preview-heading i { margin-right: 7px; }
    .email-modal-preview-subject { color: #0f172a; font-weight: 600; margin-bottom: 14px; word-break: break-word; }
    .email-modal-preview-message { color: #64748b; font-size: 13px; line-height: 1.7; white-space: pre-line; max-height: 205px; overflow-y: auto; word-break: break-word; }
    #email-history-pagination .pagination { justify-content: flex-end; margin-bottom: 0; }
    .zModalTwo-footer { border-top: 1px solid #eef1f5; padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px; }
    @media (max-width: 767px) { .email-panel-heading { display: block; }.email-panel-heading .email-variable-list { margin-top: 15px; }.email-center-page .section-wrap { padding: 16px; } }
</style>
@endpush

@push('script')
<script>
$(function () {
    const templates = @json($templates->keyBy('id')->map(fn ($template) => ['name' => $template->name, 'subject' => $template->subject, 'body' => $template->body]));
    const $modal = $('#send-template-modal');
    const $templateId = $('#modal_template_id');
    const $subject = $('#modal_email_subject');
    const $message = $('#modal_email_message');
    const $templateName = $('#selected-template-name');
    const $previewSubject = $('#modal_preview_subject');
    const $previewMessage = $('#modal_preview_message');

    function previewText(value, fallback) {
        return value
            .replaceAll('\u007b\u007bname\u007d\u007d', 'Recipient Name')
            .replaceAll('\u007b\u007bemail\u007d\u007d', 'recipient@example.com')
            .replaceAll('\u007b\u007bapp_name\u007d\u007d', @json(getOption('app_name')))
            .replaceAll('\u007b\u007bdate\u007d\u007d', new Date().toLocaleDateString()) || fallback;
    }

    function refreshPreview() {
        $previewSubject.text(previewText($subject.val() || '', @json(__('Your subject will appear here'))));
        $previewMessage.text(previewText($message.val() || '', @json(__('Your message preview will appear here.'))));
    }

    function statusMarkup(history) {
        if (history.status === 1) return '<span class="email-status sent"><i class="fa-solid fa-check"></i>{{ __('Sent') }}</span>';
        if (history.status === 2) return '<span class="email-status pending"><i class="fa-solid fa-clock"></i>{{ __('Pending') }}</span>';
        return '<span class="email-status failed"><i class="fa-solid fa-xmark"></i>{{ __('Failed') }}</span>' + (history.error ? '<div class="small text-danger mt-1">' + escapeHtml(history.error.substring(0, 70)) + '</div>' : '');
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function loadHistory(page = 1) {
        const $body = $('#email-history-body');
        $body.html('<tr><td colspan="5" class="text-muted text-center py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>{{ __('Loading email history...') }}</td></tr>');
        $.get('{{ route('admin.email.history') }}', { page: page })
            .done(function (response) {
                if (!response.data.length) {
                    $body.html('<tr><td colspan="5" class="text-muted text-center py-4">{{ __('No email history found') }}</td></tr>');
                    $('#email-history-pagination').empty();
                    return;
                }
                $body.html(response.data.map(function (history) {
                    const retry = history.status === 0 ? '<form method="POST" action="{{ url('/admin/email') }}/' + history.id + '/retry"><input type="hidden" name="_token" value="{{ csrf_token() }}"><button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-rotate-right"></i> {{ __('Retry') }}</button></form>' : '<span class="text-muted">—</span>';
                    return '<tr><td class="fw-500">' + escapeHtml(history.email) + '</td><td>' + escapeHtml((history.subject || '').substring(0, 45)) + '</td><td>' + statusMarkup(history) + '</td><td>' + escapeHtml(history.date || '') + '</td><td class="text-end">' + retry + '</td></tr>';
                }).join(''));
                let pagination = '';
                if (response.pagination.last_page > 1) {
                    for (let pageNo = 1; pageNo <= response.pagination.last_page; pageNo++) {
                        pagination += '<button type="button" class="btn btn-sm ' + (pageNo === response.pagination.current_page ? 'primary-btn' : 'btn-outline-secondary') + ' me-1 history-page" data-page="' + pageNo + '">' + pageNo + '</button>';
                    }
                }
                $('#email-history-pagination').html(pagination);
            })
            .fail(function () { $body.html('<tr><td colspan="5" class="text-danger text-center py-4">{{ __('Unable to load email history.') }}</td></tr>'); });
    }

    $modal.on('show.bs.modal', function (event) {
        const id = $(event.relatedTarget).data('template-id') || '';
        const template = templates[id];
        $templateId.val(id);
        if (template) {
            $templateName.html('<i class="fa-solid fa-layer-group"></i>' + $('<div>').text(template.name).html());
            $subject.val(template.subject);
            $message.val(template.body);
        } else {
            $templateName.html('<i class="fa-solid fa-pen-to-square"></i>{{ __('Custom email') }}');
            $subject.val('');
            $message.val('');
        }
        refreshPreview();
    });
    $subject.add($message).on('input', refreshPreview);
    $('#email-history-pagination').on('click', '.history-page', function () { loadHistory($(this).data('page')); });
    loadHistory();
});
</script>
@endpush
