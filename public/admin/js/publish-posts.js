(function() {
    var publishForm = document.getElementById('publish-form');
    if (!publishForm) return;

    var publishBtn = document.getElementById('publish-btn');
    var resultsSection = document.getElementById('publish-results');
    var alertArea = document.getElementById('publish-alert-area');
    var publishingLabel = publishForm.getAttribute('data-publishing-label') || 'Publishing…';
    var buttonLabel = publishForm.getAttribute('data-button-label') || 'Publish All Pending Posts Now';
    var errorMessage = publishForm.getAttribute('data-error-message') || 'An error occurred while publishing posts.';

    var buttonSvg = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="9" cy="9" r="8" stroke="currentColor" stroke-width="1.5"/><path d="M7 6L12 9L7 12V6Z" fill="currentColor"/></svg>';
    var spinnerSvg = '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg" style="animation:spin 1s linear infinite"><circle cx="9" cy="9" r="7" stroke="currentColor" stroke-width="1.5" stroke-dasharray="25 15" stroke-linecap="round"/></svg>';

    function showAlert(type, message) {
        if (!alertArea) return;
        alertArea.innerHTML = '';
        var wrapper = document.createElement('div');
        wrapper.style.cssText = 'border-radius:8px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:10px;font-size:13px;';
        if (type === 'success') {
            wrapper.style.background = '#f0faf5';
            wrapper.style.border = '1px solid #10A958';
            wrapper.style.color = '#10A958';
            wrapper.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="#10A958" stroke-width="1.3"/><path d="M5 8l2 2 4-4" stroke="#10A958" stroke-width="1.3" stroke-linecap="round"/></svg>' + message;
        } else {
            wrapper.style.background = '#fff5f5';
            wrapper.style.border = '1px solid #e53535';
            wrapper.style.color = '#e53535';
            wrapper.innerHTML = '<svg width="16" height="16" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="7" stroke="#e53535" stroke-width="1.3"/><path d="M8 5v3.5M8 10.5v.5" stroke="#e53535" stroke-width="1.3" stroke-linecap="round"/></svg>' + message;
        }
        alertArea.appendChild(wrapper);
        setTimeout(function() {
            if (wrapper.parentNode) wrapper.remove();
        }, 6000);
    }

    publishForm.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!publishBtn) return;

        publishBtn.disabled = true;
        publishBtn.innerHTML = spinnerSvg + ' ' + publishingLabel;

        fetch(publishForm.action, {
            method: 'POST',
            body: new FormData(publishForm),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) {
            return response.json().then(function(data) {
                if (!response.ok) throw data;
                return data;
            });
        })
        .then(function(data) {
            if (data.success) {
                var d = data.data;
                var rp = document.getElementById('result-processed');
                var rs = document.getElementById('result-successful');
                var rf = document.getElementById('result-failed');
                if (rp) rp.textContent = d.processed;
                if (rs) rs.textContent = d.successful;
                if (rf) rf.textContent = d.failed;
                if (resultsSection) resultsSection.style.display = 'block';
                var pendingEl = document.getElementById('stat-pending');
                if (pendingEl) pendingEl.textContent = '0';
                var postedEl = document.getElementById('stat-posted-today');
                if (postedEl) {
                    postedEl.textContent = parseInt(postedEl.textContent.replace(/,/g, ''), 10) + d.successful;
                }
                showAlert('success', data.message);
            } else {
                showAlert('danger', data.message || errorMessage);
            }
        })
        .catch(function(err) {
            var msg = (err && err.message) ? err.message : errorMessage;
            showAlert('danger', msg);
        })
        .finally(function() {
            if (publishBtn) {
                publishBtn.disabled = false;
                publishBtn.innerHTML = buttonSvg + ' ' + buttonLabel;
            }
        });
    });
})();
