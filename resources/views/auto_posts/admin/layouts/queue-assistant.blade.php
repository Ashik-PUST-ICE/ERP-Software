<div id="queue-assistant" class="queue-assistant" data-status-url="{{ route('admin.queue.status') }}" data-start-url="{{ route('admin.queue.start') }}" data-csrf="{{ csrf_token() }}">
    <button type="button" class="queue-assistant-launcher" aria-label="{{ __('Open Queue Center') }}" aria-expanded="false">
        <i class="fa-solid fa-layer-group"></i><span>{{ __('Queue Center') }}</span>
    </button>
    <section class="queue-assistant-panel" aria-label="{{ __('Queue Center') }}" hidden>
        <header class="queue-assistant-header">
            <div><strong>{{ __('Queue Center') }}</strong><small>{{ __('Monitor background tasks') }}</small></div>
            <button type="button" class="queue-assistant-close" aria-label="{{ __('Close') }}">&times;</button>
        </header>
        <div class="queue-assistant-body">
            <div class="queue-assistant-status-row"><span>{{ __('Connection') }}</span><strong id="queue-connection">—</strong></div>
            <div class="queue-assistant-cards">
                <div class="queue-assistant-card"><strong id="queue-pending">0</strong><span>{{ __('Pending') }}</span></div>
                <div class="queue-assistant-card failed"><strong id="queue-failed">0</strong><span>{{ __('Failed') }}</span></div>
            </div>
            <div class="queue-assistant-ready" id="queue-ready"><i class="fa-solid fa-circle-check"></i>{{ __('Queue database is ready') }}</div>
            <button type="button" class="queue-assistant-run" id="queue-run"><i class="fa-solid fa-play"></i> {{ __('Run Pending Jobs Now') }}</button>
            <div class="queue-assistant-feedback" id="queue-feedback" aria-live="polite"></div>
            <div class="queue-assistant-command-label">{{ __('Run this command in a Herd terminal') }}</div>
            <div class="queue-assistant-command"><code id="queue-command">php artisan queue:work --queue=default --tries=3 --timeout=120</code><button type="button" id="queue-copy-command" title="{{ __('Copy command') }}"><i class="fa-regular fa-copy"></i></button></div>
            <p class="queue-assistant-note">{{ __('Keep the worker terminal running while emails or other background tasks are being processed.') }}</p>
            <div class="queue-assistant-footer"><small id="queue-checked-at">{{ __('Not checked yet') }}</small><button type="button" class="queue-assistant-refresh" id="queue-refresh"><i class="fa-solid fa-rotate"></i> {{ __('Refresh') }}</button></div>
        </div>
    </section>
</div>

<style>
.queue-assistant { position: fixed; right: 24px; bottom: 82px; z-index: 1049; font-family: inherit; }
.queue-assistant-launcher { border: 0; border-radius: 24px; background: #4778c7; color: #fff; padding: 11px 16px; box-shadow: 0 8px 24px rgba(38,47,77,.2); cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; }
.queue-assistant-launcher:hover { background: #315fa8; }
.queue-assistant-panel { position: absolute; right: 0; bottom: 56px; width: min(360px, calc(100vw - 32px)); background: #fff; border: 1px solid #e8eaf0; border-radius: 14px; box-shadow: 0 16px 45px rgba(38,47,77,.2); overflow: hidden; }
.queue-assistant-header { padding: 14px 16px; color: #fff; background: linear-gradient(135deg,#4778c7,#6e9de0); display: flex; align-items: center; justify-content: space-between; }
.queue-assistant-header strong,.queue-assistant-header small { display: block; }.queue-assistant-header small { margin-top: 2px; opacity: .85; font-size: 11px; }.queue-assistant-close { border: 0; background: transparent; color: #fff; font-size: 25px; line-height: 1; cursor: pointer; }
.queue-assistant-body { padding: 16px; background: #f8f9fb; }.queue-assistant-status-row { display: flex; justify-content: space-between; align-items: center; color: #64748b; font-size: 13px; margin-bottom: 12px; }.queue-assistant-status-row strong { color: #0f172a; text-transform: uppercase; font-size: 12px; }
.queue-assistant-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }.queue-assistant-card { padding: 14px; border-radius: 10px; background: #eaf2ff; color: #2455a4; }.queue-assistant-card.failed { background: #fff0ee; color: #b42318; }.queue-assistant-card strong,.queue-assistant-card span { display: block; }.queue-assistant-card strong { font-size: 24px; line-height: 1.1; }.queue-assistant-card span { font-size: 12px; margin-top: 4px; }
.queue-assistant-ready { margin: 13px 0 10px; color: #16734a; font-size: 12px; }.queue-assistant-ready.is-not-ready { color: #b42318; }.queue-assistant-ready i { margin-right: 5px; }.queue-assistant-run { width: 100%; border: 0; border-radius: 7px; background: #4778c7; color: #fff; padding: 9px 12px; font-size: 12px; font-weight: 600; cursor: pointer; }.queue-assistant-run:hover { background: #315fa8; }.queue-assistant-run:disabled { opacity: .55; cursor: wait; }.queue-assistant-feedback { min-height: 18px; color: #16734a; font-size: 11px; margin-top: 7px; }.queue-assistant-feedback.is-error { color: #b42318; }.queue-assistant-command-label { color: #64748b; font-size: 11px; margin-bottom: 5px; }.queue-assistant-command { display: flex; align-items: center; gap: 6px; background: #1e293b; border-radius: 7px; padding: 8px; }.queue-assistant-command code { flex: 1; color: #dbeafe; font-size: 10px; white-space: normal; word-break: break-word; }.queue-assistant-command button { border: 0; background: transparent; color: #fff; cursor: pointer; }.queue-assistant-note { color: #8991a3; font-size: 11px; line-height: 1.45; margin: 10px 0; }.queue-assistant-footer { display: flex; align-items: center; justify-content: space-between; color: #8991a3; font-size: 10px; }.queue-assistant-refresh { border: 0; background: transparent; color: #4778c7; font-size: 12px; cursor: pointer; }.queue-assistant-refresh:disabled { opacity: .5; cursor: wait; }.queue-assistant-refresh.is-loading i { animation: queue-spin .8s linear infinite; } @keyframes queue-spin { to { transform: rotate(360deg); } }
@media (max-width:575px) { .queue-assistant { right: 16px; bottom: 74px; }.queue-assistant-launcher span { display: none; }.queue-assistant-launcher { width: 46px; height: 46px; justify-content: center; border-radius: 50%; padding: 0; } }
</style>
