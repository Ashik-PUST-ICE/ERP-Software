(function ($) {
    "use strict";

    function getConfig() {
        var $wrap = $('#ai-generate-wrap');
        return {
            submitUrl: $wrap.data('submit-url') || '',
            csrfToken: $wrap.data('csrf-token') || '',
            toggleSaveUrlPattern: $wrap.data('toggle-save-url-pattern') || '',
            msgGeneratingVideo: $wrap.data('msg-generating-video') || 'Generating video (this may take a few minutes)...',
            msgGenerating: $wrap.data('msg-generating') || 'Generating...',
            msgDone: $wrap.data('msg-done') || 'Done.',
            msgSuccess: $wrap.data('msg-success') || 'Content generated successfully.',
            msgGenerationFailed: $wrap.data('msg-generation-failed') || 'Generation failed.',
            msgRequestFailed: $wrap.data('msg-request-failed') || 'Request failed. Try again.'
        };
    }

    function showError(msg) {
        if (typeof toastr !== 'undefined') {
            toastr.error(msg);
        } else {
            alert(msg);
        }
    }

    function showResult(data) {
        var contentType = (data.content_type || 'text').toLowerCase();
        var resultWrap = document.getElementById('result-wrap');
        var textWrap = document.getElementById('result-text-wrap');
        var mediaWrap = document.getElementById('result-media-wrap');
        var imageWrap = document.getElementById('result-image-wrap');
        var videoWrap = document.getElementById('result-video-wrap');

        // Store ID for saving later
        var recordId = data.id || '';
        $(resultWrap).attr('data-id', recordId);
        console.log('Stored Record ID:', recordId);

        // Reset and show save button
        $('#btn-save-content').html('<i class="fa-solid fa-bookmark me-1"></i> Save to List')
            .addClass('primary-btn').removeClass('btn-success btn-info text-white').prop('disabled', false).show();

        resultWrap.style.display = 'block';
        textWrap.style.display = contentType === 'text' ? 'block' : 'none';
        mediaWrap.style.display = (contentType === 'image' || contentType === 'video') ? 'block' : 'none';
        imageWrap.style.display = contentType === 'image' ? 'block' : 'none';
        videoWrap.style.display = contentType === 'video' ? 'block' : 'none';

        if (contentType === 'text') {
            $('#generated-text').val(data.text || '');
        }
        if (contentType === 'image' && data.media_url) {
            document.getElementById('result-generated-image').src = data.media_url;
            document.getElementById('result-image-link').href = data.media_url;
        }
        if (contentType === 'video' && data.media_url) {
            document.getElementById('result-generated-video').src = data.media_url;
            document.getElementById('result-video-link').href = data.media_url;
        }
    }

    function toggleManualSelect() {
        var type = (document.getElementById('content_type').value || '').toLowerCase();
        document.getElementById('wrap-manual-image').style.display = type === 'image' ? 'block' : 'none';
        document.getElementById('wrap-manual-video').style.display = type === 'video' ? 'block' : 'none';
    }

    function init() {
        if (!$('#ai-generate-wrap').length) return;
        var config = getConfig();
        if (!config.submitUrl || !config.csrfToken) return;

        document.getElementById('content_type').addEventListener('change', toggleManualSelect);
        toggleManualSelect();

        // Handle Save to List
        $(document).on('click', '#btn-save-content', function (e) {
            e.preventDefault();
            var $btn = $(this);
            var recordId = $('#result-wrap').attr('data-id');
            console.log('Button clicked, ID to save:', recordId);

            if (!recordId) {
                showError('Record ID not found. Regenerate content.');
                return;
            }

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

            var saveUrl = config.toggleSaveUrlPattern ? config.toggleSaveUrlPattern.replace(':id', recordId) : '';
            console.log('Constructed Save URL:', saveUrl);

            fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ is_saved: 1 })
            })
                .then(res => res.json())
                .then(data => {
                    console.log('Save response:', data);
                    if (data.success) {
                        $btn.html('<i class="fa-solid fa-check me-1"></i> Saved').addClass('btn-success').removeClass('primary-btn').prop('disabled', true);
                        if (typeof toastr !== 'undefined') toastr.success(data.message);
                    } else {
                        $btn.prop('disabled', false).html('<i class="fa-solid fa-bookmark me-1"></i> Save to List');
                        showError(data.message || 'Error saving.');
                    }
                })
                .catch(err => {
                    console.error('Save error:', err);
                    $btn.prop('disabled', false).html('<i class="fa-solid fa-bookmark me-1"></i> Save to List');
                    showError(config.msgRequestFailed);
                });
        });

        document.getElementById('btn-generate').addEventListener('click', function () {
            var btn = document.getElementById('btn-generate');
            var statusEl = document.getElementById('generate-status');
            var contentType = (document.getElementById('content_type').value || 'text').toLowerCase();
            var maxTokVal = document.getElementById('max_tokens').value;

            btn.disabled = true;
            statusEl.textContent = contentType === 'video' ? config.msgGeneratingVideo : config.msgGenerating;

            var payload = {
                prompt: ($('#prompt').val() || '').trim(),
                content_type: contentType,
                tone: document.getElementById('tone').value || null,
                language: document.getElementById('language').value || null,
                max_tokens: maxTokVal ? parseInt(maxTokVal, 10) : null
            };

            fetch(config.submitUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            })
                .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
                .then(function (result) {
                    var data = result.data;
                    if (result.ok && data && data.success) {
                        showResult(data);
                        statusEl.textContent = config.msgDone;
                        if (typeof toastr !== 'undefined') toastr.success(config.msgSuccess);
                    } else {
                        statusEl.textContent = '';
                        showError((data && data.message) || config.msgGenerationFailed);
                    }
                })
                .catch(function () {
                    statusEl.textContent = '';
                    showError(config.msgRequestFailed);
                })
                .finally(function () { btn.disabled = false; });
        });
    }

    $(document).ready(init);
})(jQuery);
