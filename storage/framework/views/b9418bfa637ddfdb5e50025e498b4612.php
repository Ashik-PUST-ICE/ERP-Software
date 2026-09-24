<div id="ai-assistant" class="ai-assistant" data-history-url="<?php echo e(route('admin.ai.chat.history')); ?>"
    data-message-url="<?php echo e(route('admin.ai.chat.message')); ?>" data-csrf="<?php echo e(csrf_token()); ?>">
    <button type="button" class="ai-assistant-launcher" aria-label="<?php echo e(__('Open AI Assistant')); ?>" aria-expanded="false">
        <i class="fa-solid fa-sparkles"></i>
        <span class="ai-assistant-launcher-label"><?php echo e(__('AI Assistant')); ?></span>
    </button>
    <section class="ai-assistant-panel" aria-label="<?php echo e(__('AI Assistant')); ?>" hidden>
        <header class="ai-assistant-header">
            <div>
                <strong><?php echo e(__('AI Assistant')); ?></strong>
                <small><?php echo e(__('Ask about using your ERP')); ?></small>
            </div>
            <button type="button" class="ai-assistant-close" aria-label="<?php echo e(__('Close')); ?>">&times;</button>
        </header>
        <div class="ai-assistant-messages" aria-live="polite">
            <div class="ai-assistant-empty"><?php echo e(__('Hi! How can I help you today?')); ?></div>
        </div>
        <form class="ai-assistant-form">
            <textarea name="message" rows="1" maxlength="6000" placeholder="<?php echo e(__('Type your question...')); ?>" required></textarea>
            <button type="submit" aria-label="<?php echo e(__('Send')); ?>"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
        <div class="ai-assistant-note"><?php echo e(__('AI can make mistakes. Check important information.')); ?></div>
    </section>
</div>

<style>
.ai-assistant { position: fixed; right: 24px; bottom: 24px; z-index: 1050; font-family: inherit; }
.ai-assistant-launcher { border: 0; border-radius: 24px; background: #ff5a1f; color: #fff; padding: 12px 17px; box-shadow: 0 8px 24px rgba(38, 47, 77, .22); cursor: pointer; display: flex; align-items: center; gap: 8px; font-weight: 600; }
.ai-assistant-launcher:hover { background: #e94d15; }
.ai-assistant-panel { position: absolute; right: 0; bottom: 58px; width: min(370px, calc(100vw - 32px)); height: min(520px, calc(100vh - 110px)); background: #fff; border: 1px solid #e8eaf0; border-radius: 14px; box-shadow: 0 16px 45px rgba(38, 47, 77, .2); overflow: hidden; display: flex; flex-direction: column; }
.ai-assistant-header { padding: 14px 16px; color: #fff; background: linear-gradient(135deg, #ff5a1f, #f27b43); display: flex; align-items: center; justify-content: space-between; }
.ai-assistant-header strong, .ai-assistant-header small { display: block; }
.ai-assistant-header small { margin-top: 2px; opacity: .85; font-size: 11px; }
.ai-assistant-close { border: 0; background: transparent; color: #fff; font-size: 25px; line-height: 1; cursor: pointer; }
.ai-assistant-messages { flex: 1; overflow-y: auto; padding: 14px; background: #f8f9fb; }
.ai-assistant-empty { color: #8991a3; text-align: center; padding: 35px 15px; font-size: 13px; }
.ai-assistant-message { max-width: 88%; margin-bottom: 10px; padding: 9px 11px; border-radius: 11px; white-space: pre-wrap; word-break: break-word; font-size: 13px; line-height: 1.45; }
.ai-assistant-message.user { margin-left: auto; background: #ff5a1f; color: #fff; border-bottom-right-radius: 3px; }
.ai-assistant-message.assistant { background: #fff; color: #343a40; border: 1px solid #eceef2; border-bottom-left-radius: 3px; }
.ai-assistant-typing { display: inline-flex; gap: 3px; align-items: center; min-width: 35px; }
.ai-assistant-typing span { width: 5px; height: 5px; border-radius: 50%; background: #8d95a4; animation: ai-assistant-dot 1.2s infinite ease-in-out; }
.ai-assistant-typing span:nth-child(2) { animation-delay: .15s; }
.ai-assistant-typing span:nth-child(3) { animation-delay: .3s; }
@keyframes ai-assistant-dot { 0%, 60%, 100% { opacity: .3; transform: translateY(0); } 30% { opacity: 1; transform: translateY(-3px); } }
.ai-assistant-form { display: flex; gap: 8px; padding: 10px; border-top: 1px solid #e8eaf0; background: #fff; }
.ai-assistant-form textarea { resize: none; flex: 1; border: 1px solid #e0e3ea; border-radius: 9px; padding: 9px 10px; max-height: 80px; font-size: 13px; outline: none; }
.ai-assistant-form textarea:focus { border-color: #ff5a1f; }
.ai-assistant-form button { width: 38px; border: 0; border-radius: 9px; background: #ff5a1f; color: #fff; cursor: pointer; }
.ai-assistant-form button:disabled { opacity: .55; cursor: wait; }
.ai-assistant-note { padding: 0 12px 9px; color: #9aa1af; font-size: 10px; text-align: center; }
@media (max-width: 575px) { .ai-assistant { right: 16px; bottom: 16px; } .ai-assistant-launcher-label { display: none; } .ai-assistant-launcher { width: 46px; height: 46px; justify-content: center; border-radius: 50%; padding: 0; } }
</style>

<script>
(function () {
    const root = document.getElementById('ai-assistant');
    if (!root) return;
    const launcher = root.querySelector('.ai-assistant-launcher');
    const panel = root.querySelector('.ai-assistant-panel');
    const close = root.querySelector('.ai-assistant-close');
    const messages = root.querySelector('.ai-assistant-messages');
    const form = root.querySelector('.ai-assistant-form');
    const input = form.querySelector('textarea');
    const submit = form.querySelector('button');
    let conversationId = null;
    let loaded = false;

    const addMessage = (role, text) => {
        const empty = messages.querySelector('.ai-assistant-empty');
        if (empty) empty.remove();
        const item = document.createElement('div');
        item.className = 'ai-assistant-message ' + role;
        item.textContent = text;
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
    };

    const loadHistory = async () => {
        if (loaded) return;
        loaded = true;
        try {
            const response = await fetch(root.dataset.historyUrl, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            conversationId = data.conversation_id || null;
            (data.messages || []).forEach(message => addMessage(message.role, message.content));
        } catch (error) { /* The assistant can still start a new conversation. */ }
    };

    const toggle = (show) => {
        panel.hidden = !show;
        launcher.setAttribute('aria-expanded', show ? 'true' : 'false');
        if (show) { loadHistory(); input.focus(); }
    };
    launcher.addEventListener('click', () => toggle(panel.hidden));
    close.addEventListener('click', () => toggle(false));
    input.addEventListener('keydown', event => {
        if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); form.requestSubmit(); }
    });
    form.addEventListener('submit', async event => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message || submit.disabled) return;
        addMessage('user', message);
        input.value = '';
        submit.disabled = true;
        const waiting = document.createElement('div');
        waiting.className = 'ai-assistant-message assistant';
        waiting.innerHTML = '<span class="ai-assistant-typing" aria-label="<?php echo e(__('Thinking...')); ?>"><span></span><span></span><span></span></span>';
        messages.appendChild(waiting);
        messages.scrollTop = messages.scrollHeight;
        try {
            const response = await fetch(root.dataset.messageUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': root.dataset.csrf },
                body: JSON.stringify({ message, conversation_id: conversationId })
            });
            const raw = await response.text();
            let data = {};
            try { data = raw ? JSON.parse(raw) : {}; } catch (parseError) { /* handled below */ }
            if (!response.ok || !data.success) {
                throw new Error(data.message || '<?php echo e(__('The server could not process your message. Please try again.')); ?>');
            }
            waiting.remove();
            conversationId = data.conversation_id || conversationId;
            addMessage('assistant', data.message);
        } catch (error) {
            waiting.textContent = error.message || '<?php echo e(__('Unable to get a response. Please try again.')); ?>';
        } finally { submit.disabled = false; input.focus(); }
    });
}());
</script>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\layouts\ai-assistant.blade.php ENDPATH**/ ?>