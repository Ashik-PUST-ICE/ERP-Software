'use strict';

document.addEventListener('DOMContentLoaded', function () {
    let getUrl = window.location.origin;

    // Get elements
    const secretKeyInput = document.getElementById('maintenance_secret_key');
    const maintenanceUrlInput = document.getElementById('maintenance_mode_url');
    const copyButton = document.getElementById('copy-maintenance-url');

    // Update maintenance URL when secret key changes
    function updateMaintenanceUrl() {
        if (secretKeyInput && maintenanceUrlInput) {
            var secretKey = secretKeyInput.value;
            var maintenanceUrl = getUrl + '/' + secretKey;
            maintenanceUrlInput.value = maintenanceUrl;
        }
    }

    // Initial URL update
    if (secretKeyInput && maintenanceUrlInput) {
        updateMaintenanceUrl();

        // Update URL on secret key input
        secretKeyInput.addEventListener('input', function () {
            updateMaintenanceUrl();
        });

        // Copy maintenance URL to clipboard
        if (copyButton) {
            copyButton.addEventListener('click', function () {
                var urlInput = document.getElementById('maintenance_mode_url');
                var url = urlInput.value;

                if (!url) {
                    return;
                }

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function () {
                        var btn = copyButton;
                        var originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                        btn.classList.add('btn-success');
                        btn.classList.remove('btn-outline-secondary');
                        setTimeout(function () {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    });
                } else {
                    // Fallback for older browsers
                    urlInput.select();
                    try {
                        document.execCommand('copy');
                        var btn = copyButton;
                        var originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                        btn.classList.add('btn-success');
                        btn.classList.remove('btn-outline-secondary');
                        setTimeout(function () {
                            btn.innerHTML = originalHtml;
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-outline-secondary');
                        }, 2000);
                    } catch (err) {
                        console.error('Failed to copy:', err);
                    }
                }
            });
        }
    }
});
