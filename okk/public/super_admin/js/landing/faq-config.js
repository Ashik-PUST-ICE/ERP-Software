function addFaqItemRow() {
    var wrapper = document.getElementById('faq-items-wrapper');
    if (!wrapper) return;

    var index = wrapper.querySelectorAll('.faq-item').length + 1;

    var labelFaq = wrapper.getAttribute('data-label-faq') || 'FAQ';
    var labelQuestion = wrapper.getAttribute('data-label-question') || 'Question';
    var labelAnswer = wrapper.getAttribute('data-label-answer') || 'Answer';
    var placeholderQuestion = wrapper.getAttribute('data-placeholder-question') || 'Enter question';
    var placeholderAnswer = wrapper.getAttribute('data-placeholder-answer') || 'Enter answer';

    var div = document.createElement('div');
    div.className = 'faq-item border rounded p-3 mb-3';
    div.innerHTML =
        '<div class="d-flex justify-content-between align-items-center mb-2">' +
        '<h6 class="mb-0">' + labelFaq + ' #' + index + '</h6>' +
        '<button type="button" class="btn btn-sm btn-danger" onclick="removeFaqItemRow(this)">&times;</button>' +
        '</div>' +
        '<div class="form-group mb-2">' +
        '<label class="form-label">' + labelQuestion + ' (' + labelFaq + ' ' + index + ')</label>' +
        '<input type="text" name="landing_faq_questions[]" class="form-control" placeholder="' + placeholderQuestion + '">' +
        '</div>' +
        '<div class="form-group mb-0">' +
        '<label class="form-label">' + labelAnswer + ' (' + labelFaq + ' ' + index + ')</label>' +
        '<textarea name="landing_faq_answers[]" class="form-control" rows="2" placeholder="' + placeholderAnswer + '"></textarea>' +
        '</div>';

    wrapper.appendChild(div);
}

function removeFaqItemRow(button) {
    var item = button.closest('.faq-item');
    if (item) {
        item.remove();
    }
}

(function () {
    var wrapper = document.getElementById('faq-items-wrapper');
    if (!wrapper) return;

    var items = wrapper.querySelectorAll('.faq-item');
    if (items.length === 0) {
        addFaqItemRow();
    }
})();

