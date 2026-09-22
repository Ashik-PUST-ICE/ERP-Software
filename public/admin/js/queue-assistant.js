(function ($) {
    "use strict";

    $(document).ready(function () {
        var $root = $('#queue-assistant');
        if (!$root.length) return;

        var $panel = $root.find('.queue-assistant-panel');
        var $launcher = $root.find('.queue-assistant-launcher');
        var $refresh = $('#queue-refresh');
        var $run = $('#queue-run');

        function escapeHtml(value) { return $('<div>').text(value || '').html(); }

        function renderFailedJobs(jobs) {
            var $section = $('#queue-failed-section');
            var $list = $('#queue-failed-list');
            if (!jobs || !jobs.length) { $section.prop('hidden', true); $list.empty(); return; }
            $section.prop('hidden', false);
            $list.html(jobs.map(function (job) {
                return '<div class="queue-failed-item" data-job-id="' + job.id + '">' +
                    '<div class="queue-failed-meta">#' + job.id + ' · ' + escapeHtml(job.queue) + ' · ' + escapeHtml(job.failed_at) + '</div>' +
                    '<div class="queue-failed-error" title="' + escapeHtml(job.exception) + '">' + escapeHtml(job.exception) + '</div>' +
                    '<div class="queue-failed-actions"><button type="button" class="queue-retry">Retry</button><button type="button" class="queue-forget">Remove</button></div></div>';
            }).join(''));
        }

        function loadStatus() {
            $refresh.prop('disabled', true).addClass('is-loading');
            $.get($root.data('status-url'))
                .done(function (data) {
                    $('#queue-connection').text(data.connection || '—');
                    $('#queue-pending').text(data.pending || 0);
                    $('#queue-failed').text(data.failed || 0);
                    renderFailedJobs(data.failed_jobs || []);
                    $('#queue-command').text(data.command || 'php artisan queue:work');
                    $('#queue-checked-at').text(data.checked_at || '');
                    $run.prop('disabled', !!data.worker_running);
                    $run.html(data.worker_running ? '<i class="fa-solid fa-spinner fa-spin"></i> Worker is running...' : '<i class="fa-solid fa-play"></i> Run Pending Jobs Now');
                    $('#queue-ready').toggleClass('is-not-ready', !data.ready).html(data.ready
                        ? '<i class="fa-solid fa-circle-check"></i> Queue database is ready'
                        : '<i class="fa-solid fa-triangle-exclamation"></i> Queue database is not ready');
                })
                .always(function () { $refresh.prop('disabled', false).removeClass('is-loading'); });
        }

        $launcher.on('click', function () {
            var show = $panel.prop('hidden');
            $panel.prop('hidden', !show);
            $launcher.attr('aria-expanded', show ? 'true' : 'false');
            if (show) loadStatus();
        });
        $root.find('.queue-assistant-close').on('click', function () {
            $panel.prop('hidden', true);
            $launcher.attr('aria-expanded', 'false');
        });
        $refresh.on('click', loadStatus);
        $run.on('click', function () {
            $run.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Starting worker...');
            $('#queue-feedback').removeClass('is-error').text('');
            $.ajax({
                url: $root.data('start-url'),
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $root.data('csrf'), 'Accept': 'application/json' }
            }).done(function (response) {
                $('#queue-feedback').text(response.message || 'Worker started.');
                loadStatus();
            }).fail(function (xhr) {
                $('#queue-feedback').addClass('is-error').text(xhr.responseJSON?.message || 'Unable to start worker.');
                loadStatus();
            });
        });
        $('#queue-failed-list').on('click', 'button', function () {
            var $button = $(this), id = $button.closest('.queue-failed-item').data('job-id');
            var retry = $button.hasClass('queue-retry');
            $button.prop('disabled', true);
            $.ajax({
                url: (retry ? $root.data('retry-url') : $root.data('forget-url')).replace('__ID__', id),
                type: retry ? 'POST' : 'DELETE',
                headers: { 'X-CSRF-TOKEN': $root.data('csrf'), 'Accept': 'application/json' }
            }).done(function (response) {
                $('#queue-feedback').removeClass('is-error').text(response.message || 'Done.');
                loadStatus();
            }).fail(function (xhr) {
                $('#queue-feedback').addClass('is-error').text(xhr.responseJSON?.message || 'Unable to update failed job.');
                $button.prop('disabled', false);
            });
        });
        $('#queue-copy-command').on('click', function () {
            navigator.clipboard.writeText($('#queue-command').text()).then(function () {
                var $button = $('#queue-copy-command');
                $button.html('<i class="fa-solid fa-check"></i>');
                setTimeout(function () { $button.html('<i class="fa-regular fa-copy"></i>'); }, 1200);
            });
        });
    });
})(jQuery);
