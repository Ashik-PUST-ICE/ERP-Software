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
                <div class="d-flex align-items-center gap-3 flex-wrap"><div class="email-variable-list"><code>&#123;&#123;name&#125;&#125;</code><code>&#123;&#123;email&#125;&#125;</code><code>&#123;&#123;date&#125;&#125;</code></div><button type="button" class="primary-btn-outline template-manage-btn" data-bs-toggle="modal" data-bs-target="#template-management-modal"><i class="fa-solid fa-sliders me-1"></i>{{ __('Manage Templates') }}</button></div>
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

<div class="modal fade zModalTwo" id="template-management-modal" tabindex="-1" aria-labelledby="template-management-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl template-management-dialog">
        <div class="modal-content zModalTwo-content">
            <div class="modal-header zModalTwo-header template-management-header"><div><h5 class="modal-title mb-1" id="template-management-modal-label"><i class="fa-solid fa-sliders me-2"></i>{{ __('Manage Email Templates') }}</h5><small>{{ __('Create reusable email messages for your daily work.') }}</small></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button></div>
            <div class="modal-body zModalTwo-body template-management-body">
                <form id="template-management-form" method="POST" action="{{ route('admin.email.templates.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="template-form-method" value="POST">
                    <input type="hidden" id="template-store-route" value="{{ route('admin.email.templates.store') }}">
                    <input type="hidden" id="template-update-route" value="{{ route('admin.email.templates.update', ['id' => '__ID__']) }}">
                    <div class="template-editor-card"><div class="template-editor-heading"><div><h6>{{ __('Template details') }}</h6><p>{{ __('Use variables like name, email or date to personalize your message.') }}</p></div><span class="template-editor-badge"><i class="fa-solid fa-pen-to-square"></i>{{ __('Editor') }}</span></div><div class="row gy-3">
                        <div class="col-md-6"><label class="form-label">{{ __('Template Name') }} <span class="required">*</span></label><input type="text" name="name" id="template_name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">{{ __('Subject') }} <span class="required">*</span></label><input type="text" name="subject" id="template_subject" class="form-control" required></div>
                        <div class="col-12"><label class="form-label">{{ __('Message') }} <span class="required">*</span></label><textarea name="body" id="template_body" class="form-control" rows="6" required></textarea></div>
                        <div class="col-md-8"><label class="form-label">{{ __('Variables') }}</label><input type="text" name="variables" id="template_variables" class="form-control" placeholder="&#123;&#123;name&#125;&#125;, &#123;&#123;email&#125;&#125;, &#123;&#123;date&#125;&#125;"></div>
                        <div class="col-md-4 d-flex align-items-end"><label class="template-active-toggle"><input type="checkbox" name="status" id="template_status" value="1" checked><span></span>{{ __('Active template') }}</label></div>
                    </div></div>
                    <div class="template-manage-actions mt-3"><button type="button" class="primary-btn-outline" id="template-new-btn"><i class="fa-solid fa-plus me-1"></i>{{ __('New Template') }}</button><div class="d-flex gap-2"><button type="button" class="primary-btn-outline" data-bs-dismiss="modal">{{ __('Cancel') }}</button><button type="submit" class="primary-btn" id="template-save-btn"><i class="fa-solid fa-check me-1"></i>{{ __('Save Template') }}</button></div></div>
                </form>
                <div class="template-list-heading"><div><h6>{{ __('Saved templates') }}</h6><p>{{ __('Edit or remove a template whenever you need.') }}</p></div><span><i class="fa-solid fa-layer-group"></i> {{ $templates->count() }} {{ __('templates') }}</span></div>
                <div class="template-list">
                    @forelse($templates as $template)
                        <div class="template-list-item">
                            <div class="template-list-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                            <div class="template-list-info"><div class="d-flex align-items-center gap-2 flex-wrap"><strong>{{ $template->name }}</strong><span class="template-status {{ $template->status ? 'is-active' : 'is-inactive' }}">{{ $template->status ? __('Active') : __('Inactive') }}</span></div><div class="template-list-subject">{{ \Illuminate\Support\Str::limit($template->subject, 90) }}</div><div class="template-list-vars">{{ $template->variables ?: __('No variables added') }}</div></div>
                            <div class="template-list-actions"><button type="button" class="btn btn-sm btn-outline-primary template-edit-btn" data-template-id="{{ $template->id }}"><i class="fa-solid fa-pen-to-square me-1"></i>{{ __('Edit') }}</button><button type="button" class="btn btn-sm btn-outline-danger template-delete-btn" data-bs-toggle="modal" data-bs-target="#template-delete-modal" data-template-id="{{ $template->id }}" data-template-name="{{ $template->name }}"><i class="fa-regular fa-trash-can me-1"></i>{{ __('Delete') }}</button></div>
                        </div>
                    @empty
                        <div class="template-list-empty"><i class="fa-regular fa-folder-open"></i><span>{{ __('No saved templates yet.') }}</span></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo" id="template-delete-modal" tabindex="-1" aria-labelledby="template-delete-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm"><div class="modal-content zModalTwo-content template-delete-content">
        <div class="modal-body text-center p-4"><div class="template-delete-icon"><i class="fa-regular fa-trash-can"></i></div><h5 id="template-delete-modal-label" class="mb-2">{{ __('Delete template?') }}</h5><p class="text-muted small mb-4">{{ __('This template will be permanently removed.') }}<br><strong id="template-delete-name"></strong></p><form id="template-delete-form" method="POST" action="">@csrf @method('DELETE')<div class="d-flex justify-content-center gap-2"><button type="button" class="primary-btn-outline" data-bs-dismiss="modal">{{ __('Cancel') }}</button><button type="submit" class="primary-btn template-delete-confirm"><i class="fa-regular fa-trash-can me-1"></i>{{ __('Delete') }}</button></div></form></div>
    </div></div>
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
<input type="hidden" id="email-history-route" value="{{ route('admin.email.history') }}">
<input type="hidden" id="email-retry-route" value="{{ route('admin.email.retry', ['id' => '__ID__']) }}">
<input type="hidden" id="template-delete-route" value="{{ route('admin.email.templates.destroy', ['id' => '__ID__']) }}">
<input type="hidden" id="email-app-name" value="{{ getOption('app_name') }}">
<script type="application/json" id="email-templates-data">@json($templateData)</script>
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
    .template-management-dialog { max-width: 980px; }
    .template-management-header { align-items: flex-start; padding: 20px 24px; background: #fff; border-bottom: 1px solid #eef1f5; }
    .template-management-header .modal-title { color: #1b1c17; font-size: 18px; font-weight: 600; }
    .template-management-header small { color: #8991a3; font-size: 12px; }
    .template-management-body { max-height: calc(100vh - 150px); overflow-y: auto; padding: 24px; }
    .template-editor-card { border: 1px solid #e8edf3; border-radius: 10px; padding: 18px; background: #fbfcfe; }
    .template-editor-heading, .template-list-heading { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 17px; }
    .template-editor-heading h6, .template-list-heading h6 { margin: 0 0 4px; color: #1b1c17; font-size: 15px; font-weight: 600; }
    .template-editor-heading p, .template-list-heading p { margin: 0; color: #8991a3; font-size: 12px; }
    .template-editor-badge, .template-list-heading > span { white-space: nowrap; color: #4778c7; background: #eff6ff; border: 1px solid #dbeafe; border-radius: 20px; padding: 5px 10px; font-size: 11px; font-weight: 600; }
    .template-editor-card .form-label { color: #475569; font-size: 13px; font-weight: 500; margin-bottom: 7px; }
    .template-editor-card .form-control { border-color: #e1e7ef; min-height: 42px; }
    .template-editor-card textarea.form-control { min-height: 125px; resize: vertical; }
    .template-manage-actions { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .template-active-toggle { display: inline-flex; align-items: center; gap: 8px; min-height: 42px; font-size: 13px; color: #475569; cursor: pointer; }.template-active-toggle input { accent-color: #4778c7; width: 16px; height: 16px; }
    .template-list-heading { align-items: center; margin: 25px 0 12px; padding-top: 22px; border-top: 1px solid #eef1f5; }
    .template-list { display: grid; gap: 9px; }
    .template-list-item { display: flex; align-items: center; gap: 13px; padding: 12px 13px; border: 1px solid #e8edf3; border-radius: 9px; background: #fff; transition: .18s ease; }
    .template-list-item:hover { border-color: #b9cdec; box-shadow: 0 5px 14px rgba(30, 64, 175, .07); }
    .template-list-icon { display: grid; place-items: center; width: 37px; height: 37px; flex: 0 0 37px; color: #4778c7; background: #eff6ff; border-radius: 8px; }
    .template-list-info { min-width: 0; flex: 1; }.template-list-info strong { color: #1b1c17; font-size: 13px; }.template-list-subject { color: #64748b; font-size: 12px; margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }.template-list-vars { color: #94a3b8; font-size: 11px; margin-top: 4px; }
    .template-status { border-radius: 12px; padding: 3px 7px; font-size: 10px; font-weight: 600; }.template-status.is-active { color: #16734a; background: #eaf8f0; }.template-status.is-inactive { color: #64748b; background: #f1f5f9; }
    .template-list-actions { display: flex; flex: 0 0 auto; gap: 6px; }.template-list-actions .btn { border-radius: 5px; font-size: 11px; padding: 5px 9px; }
    .template-list-empty { display: flex; justify-content: center; align-items: center; gap: 8px; padding: 26px; color: #94a3b8; border: 1px dashed #dbe2eb; border-radius: 9px; font-size: 12px; }.template-list-empty i { font-size: 20px; }
    .template-delete-content { border: 0; }.template-delete-icon { width: 48px; height: 48px; display: grid; place-items: center; margin: 0 auto 13px; color: #b42318; background: #fff0ee; border-radius: 50%; font-size: 19px; }.template-delete-content h5 { color: #1b1c17; font-size: 17px; }.template-delete-content strong { color: #475569; }
    .email-modal-preview { height: 100%; min-height: 275px; padding: 16px; border: 1px solid #e5eaf0; border-radius: 10px; background: #f8fafc; }
    .email-modal-preview-heading { color: #4778c7; font-weight: 600; font-size: 13px; padding-bottom: 12px; margin-bottom: 15px; border-bottom: 1px solid #e5eaf0; }
    .email-modal-preview-heading i { margin-right: 7px; }
    .email-modal-preview-subject { color: #0f172a; font-weight: 600; margin-bottom: 14px; word-break: break-word; }
    .email-modal-preview-message { color: #64748b; font-size: 13px; line-height: 1.7; white-space: pre-line; max-height: 205px; overflow-y: auto; word-break: break-word; }
    #email-history-pagination .pagination { justify-content: flex-end; margin-bottom: 0; }
    .zModalTwo-footer { border-top: 1px solid #eef1f5; padding: 16px 24px; display: flex; justify-content: flex-end; gap: 10px; }
    @media (max-width: 767px) { .email-panel-heading { display: block; }.email-panel-heading .email-variable-list { margin-top: 15px; }.email-center-page .section-wrap { padding: 16px; }.template-management-body { padding: 16px; max-height: calc(100vh - 100px); }.template-management-header { padding: 16px; }.template-editor-heading, .template-list-heading, .template-manage-actions { display: block; }.template-editor-badge, .template-list-heading > span { display: inline-block; margin-top: 10px; }.template-manage-actions > div { margin-top: 10px; justify-content: flex-end; }.template-list-item { align-items: flex-start; }.template-list-actions { flex-direction: column; }.template-list-actions .btn { width: 78px; } }
</style>
@endpush

@push('script')
<script src="{{ asset('admin/js/email-center.js') }}"></script>
@endpush
