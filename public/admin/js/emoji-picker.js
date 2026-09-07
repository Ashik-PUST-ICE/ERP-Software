import { createPicker } from 'https://cdn.jsdelivr.net/npm/picmo@latest/dist/index.js';

document.addEventListener('DOMContentLoaded', () => {
    const trigger = document.querySelector('#emoji-trigger');
    const textarea = document.querySelector('#content');
    const container = document.querySelector('#emoji-picker-container');

    if (!trigger || !textarea || !container) return;

    const picker = createPicker({
        rootElement: container,
        showPreview: false,
        emojisPerRow: 8,
    });

    picker.addEventListener('emoji:select', (event) => {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        textarea.value = text.slice(0, start) + event.emoji + text.slice(end);
        textarea.focus();
        textarea.selectionEnd = start + event.emoji.length;
        if (typeof window.syncContentToPreview === 'function') {
            window.syncContentToPreview();
        }
    });

    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        const isHidden = container.style.display === 'none' || !container.style.display;
        if (isHidden) {
            container.style.display = 'block';
            const rect = trigger.getBoundingClientRect();
            container.style.top = `${rect.bottom + window.scrollY + 5}px`;
            container.style.left = `${rect.left + window.scrollX}px`;
        } else {
            container.style.display = 'none';
        }
    });

    document.addEventListener('click', (e) => {
        if (!container.contains(e.target) && !trigger.contains(e.target)) {
            container.style.display = 'none';
        }
    });
});
