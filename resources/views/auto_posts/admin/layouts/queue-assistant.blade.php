<div id="queue-assistant" class="queue-assistant" data-status-url="{{ route('admin.queue.status') }}" data-start-url="{{ route('admin.queue.start') }}" data-retry-url="{{ route('admin.queue.failed.retry', ['id' => '__ID__']) }}" data-forget-url="{{ route('admin.queue.failed.forget', ['id' => '__ID__']) }}" data-csrf="{{ csrf_token() }}">
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
            <div class="queue-failed-section" id="queue-failed-section" hidden>
                <div class="queue-failed-title">{{ __('Recent failed jobs') }}</div>
                <div id="queue-failed-list"></div>
            </div>
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
.queue-failed-section { margin-top: 12px; padding-top: 11px; border-top: 1px solid #e5e7eb; }.queue-failed-title { color: #475569; font-size: 11px; font-weight: 700; margin-bottom: 7px; }.queue-failed-item { padding: 8px 0; border-bottom: 1px solid #edf0f4; }.queue-failed-item:last-child { border-bottom: 0; }.queue-failed-meta { color: #64748b; font-size: 10px; }.queue-failed-error { color: #b42318; font-size: 10px; margin: 3px 0 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }.queue-failed-actions { display: flex; gap: 5px; }.queue-failed-actions button { border: 1px solid #d9e0ea; border-radius: 4px; background: #fff; color: #475569; padding: 3px 7px; font-size: 10px; cursor: pointer; }.queue-failed-actions button:hover { color: #2455a4; border-color: #9db9e5; }.queue-failed-actions .queue-forget { color: #b42318; }
@media (max-width:575px) { .queue-assistant { right: 16px; bottom: 74px; }.queue-assistant-launcher span { display: none; }.queue-assistant-launcher { width: 46px; height: 46px; justify-content: center; border-radius: 50%; padding: 0; } }
.ai-assistant, .queue-assistant { touch-action: none; }
.ai-assistant-launcher, .queue-assistant-launcher { cursor: grab; user-select: none; }
.ai-assistant.is-dragging, .queue-assistant.is-dragging { transition: none; }
.ai-assistant.is-dragging .ai-assistant-launcher, .queue-assistant.is-dragging .queue-assistant-launcher { cursor: grabbing; }
</style>

<script>
(function () {
    const storageKey = 'admin-assistant-floating-position';
    const draggableAssistants = [
        { id: 'ai-assistant', launcher: '.ai-assistant-launcher' },
        { id: 'queue-assistant', launcher: '.queue-assistant-launcher' }
    ];
    let savedPositions = {};
    try { savedPositions = JSON.parse(localStorage.getItem(storageKey) || '{}'); } catch (error) { savedPositions = {}; }

    const clamp = (value, min, max) => Math.min(Math.max(value, min), Math.max(min, max));
    const applySavedPosition = (root, position) => {
        if (!position || !Number.isFinite(position.left) || !Number.isFinite(position.top)) return;
        const maxLeft = Math.max(8, window.innerWidth - root.offsetWidth - 8);
        const maxTop = Math.max(8, window.innerHeight - root.offsetHeight - 8);
        root.style.left = clamp(position.left, 8, maxLeft) + 'px';
        root.style.top = clamp(position.top, 8, maxTop) + 'px';
        root.style.right = 'auto';
        root.style.bottom = 'auto';
    };

    draggableAssistants.forEach(function (config) {
        const root = document.getElementById(config.id);
        const launcher = root && root.querySelector(config.launcher);
        if (!root || !launcher) return;
        applySavedPosition(root, savedPositions[config.id]);

        let dragging = false;
        let moved = false;
        let startX = 0;
        let startY = 0;
        let originLeft = 0;
        let originTop = 0;

        launcher.addEventListener('pointerdown', function (event) {
            if (event.button !== 0) return;
            const rect = root.getBoundingClientRect();
            dragging = true;
            moved = false;
            startX = event.clientX;
            startY = event.clientY;
            originLeft = rect.left;
            originTop = rect.top;
            root.classList.add('is-dragging');
            launcher.setPointerCapture?.(event.pointerId);
        });

        launcher.addEventListener('pointermove', function (event) {
            if (!dragging) return;
            const deltaX = event.clientX - startX;
            const deltaY = event.clientY - startY;
            if (Math.abs(deltaX) > 4 || Math.abs(deltaY) > 4) moved = true;
            if (!moved) return;
            const maxLeft = Math.max(8, window.innerWidth - root.offsetWidth - 8);
            const maxTop = Math.max(8, window.innerHeight - root.offsetHeight - 8);
            root.style.left = clamp(originLeft + deltaX, 8, maxLeft) + 'px';
            root.style.top = clamp(originTop + deltaY, 8, maxTop) + 'px';
            root.style.right = 'auto';
            root.style.bottom = 'auto';
        });

        const finishDrag = function () {
            if (!dragging) return;
            dragging = false;
            root.classList.remove('is-dragging');
            if (!moved) return;
            savedPositions[config.id] = { left: root.getBoundingClientRect().left, top: root.getBoundingClientRect().top };
            localStorage.setItem(storageKey, JSON.stringify(savedPositions));
            launcher.dataset.dragged = 'true';
        };
        launcher.addEventListener('pointerup', finishDrag);
        launcher.addEventListener('pointercancel', finishDrag);
        launcher.addEventListener('click', function (event) {
            if (launcher.dataset.dragged === 'true') {
                event.preventDefault();
                event.stopImmediatePropagation();
                delete launcher.dataset.dragged;
            }
        }, true);
    });

    window.addEventListener('resize', function () {
        draggableAssistants.forEach(function (config) {
            const root = document.getElementById(config.id);
            if (root && root.style.left) applySavedPosition(root, { left: root.getBoundingClientRect().left, top: root.getBoundingClientRect().top });
        });
    });
}());
</script>
