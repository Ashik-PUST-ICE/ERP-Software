document.addEventListener("DOMContentLoaded", function() {
    var container = document.getElementById('virtualPaymentForm');
    if (!container) return;
    var virtualForm = container.querySelector('form');
    if (virtualForm) {
        virtualForm.submit();
    }
});
