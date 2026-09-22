(function ($) {
    "use strict";

    $(document).ready(function () {
        var $root = $('#queue-assistant');
        if (!$root.length) return;

        var $panel = $root.find('.queue-assistant-panel');
        var $launcher = $root.find('.queue-assistant-launcher');
        var $refresh = $('#queue-refresh');
        var $run = $('#queue-run');

        function loadStatus() {
            $refresh.prop('disabled', true).addClass('is-loading');
            $.get($root.data('status-url'))
                .done(function (data) {
                    $('#queue-connection').text(data.connection || '—');
                    $('#queue-pending').text(data.pending || 0);
                    $('#queue-failed').text(data.failed || 0);
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
        $('#queue-copy-command').on('click', function () {
            navigator.clipboard.writeText($('#queue-command').text()).then(function () {
                var $button = $('#queue-copy-command');
                $button.html('<i class="fa-solid fa-check"></i>');
                setTimeout(function () { $button.html('<i class="fa-regular fa-copy"></i>'); }, 1200);
            });
        });
    });
})(jQuery);
