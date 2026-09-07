(function ($) {
    "use strict";

    var platforms = {};

    function openSettingsModal(platformKey) {
        var modal = document.getElementById('APISettingsModal');
        var modalLabel = document.getElementById('APISettingsModalLabel');
        var modalFooter = document.getElementById('APISettingsModalFooter');
        var updateBtn = document.getElementById('APISettingsModalUpdateBtn');
        var platformName = platforms[platformKey] ? platforms[platformKey].name : platformKey;

        document.querySelectorAll('.settings-form-container').forEach(function (container) {
            container.style.display = 'none';
        });

        var targetForm = document.getElementById('settings-form-' + platformKey);
        if (targetForm) {
            targetForm.style.display = 'block';
            if (modalLabel) modalLabel.textContent = platformName + ' API Settings';
            if (modalFooter) modalFooter.style.display = 'flex';

            var form = targetForm.querySelector('#settings-form');
            var isNewConfig = targetForm.dataset.isNew === '1';

            if (updateBtn && form) {
                // Update button text based on whether it's new or existing config
                updateBtn.textContent = isNewConfig ? 'Save' : 'Update';
                updateBtn.onclick = function () {
                    form.submit();
                };
            }

            bootstrap.Modal.getOrCreateInstance(modal).show();
        } else {
            if (typeof toastr !== 'undefined') {
                toastr.error('Configuration not found for this platform.');
            }
        }
    }

    function openCreateModal(platformKey) {
        if (platformKey) {
            var radioBtn = document.querySelector(
                '#ConnectAccountModal input[type="radio"][value="' + platformKey + '"]'
            );
            if (radioBtn) radioBtn.checked = true;
        }
        bootstrap.Modal.getOrCreateInstance(document.getElementById('ConnectAccountModal')).show();
    }

    $(document).ready(function () {
        var $cfg = $('#page-config');
        if ($cfg.length) {
            try {
                platforms = JSON.parse($cfg.attr('data-platforms') || '{}');
            } catch (e) {
                console.warn('social-media-config: could not parse platforms data', e);
            }
        }

        $(document).on('click', '.open-settings-btn', function (e) {
            e.preventDefault();
            openSettingsModal($(this).data('platform'));
        });

        $(document).on('click', '.open-create-btn', function (e) {
            e.preventDefault();
            openCreateModal($(this).data('platform'));
        });

        $(document).on('click', '.copy-redirect-uri-btn', function (e) {
            e.preventDefault();
            var uriValue = $(this).closest('.copy-wrap').find('input[name="redirect_uri"]').val();
            if (!uriValue) return;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(uriValue).then(function () {
                    if (typeof toastr !== 'undefined') toastr.success('Redirect URI copied to clipboard!');
                }).catch(function () {
                    if (typeof toastr !== 'undefined') toastr.error('Failed to copy to clipboard');
                });
            }
        });
    });

})(jQuery);
