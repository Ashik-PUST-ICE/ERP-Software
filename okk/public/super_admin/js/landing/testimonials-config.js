function testimonialsAddMore() {
    var wrapper = document.getElementById('testimonials-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.testimonial-card-block');
    var visible = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            visible++;
        }
    }

    if (visible >= 10) return;

    var nextNum = visible + 1;
    var block = wrapper.querySelector('.testimonial-card-block[data-card-num="' + nextNum + '"]');

    if (block) {
        block.style.display = '';
        var countInput = document.getElementById('landing_testimonial_card_count');
        if (countInput) {
            countInput.value = nextNum;
        }
    }

    var addBtn = document.getElementById('testimonials-add-more-btn');
    if (addBtn) {
        addBtn.style.display = nextNum >= 10 ? 'none' : '';
    }

    testimonialsUpdateRemoveButtons();
}

function testimonialsRemoveCard(num) {
    var wrapper = document.getElementById('testimonials-cards-wrapper');
    if (!wrapper || num <= 1) return;

    var block = wrapper.querySelector('.testimonial-card-block[data-card-num="' + num + '"]');
    if (block) {
        block.style.display = 'none';
    }

    var lastVisible = 0;
    var blocks = wrapper.querySelectorAll('.testimonial-card-block');

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisible = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var countInput = document.getElementById('landing_testimonial_card_count');
    if (countInput) {
        countInput.value = lastVisible || 1;
    }

    var addBtn = document.getElementById('testimonials-add-more-btn');
    if (addBtn) {
        addBtn.style.display = lastVisible >= 10 ? 'none' : '';
    }

    testimonialsUpdateRemoveButtons();
}

function testimonialsUpdateRemoveButtons() {
    var wrapper = document.getElementById('testimonials-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.testimonial-card-block');
    var visibleCount = 0;
    var visibleNums = [];

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            visibleCount++;
            visibleNums.push(parseInt(blocks[i].getAttribute('data-card-num'), 10));
        }
    }

    var removeBtns = wrapper.querySelectorAll('.testimonials-remove-btn');

    for (var j = 0; j < removeBtns.length; j++) {
        var btn = removeBtns[j];
        var match = btn.getAttribute('onclick') && btn.getAttribute('onclick').match(/\d+/);
        var cardNum = match ? parseInt(match[0], 10) : NaN;
        var isVisible = visibleNums.indexOf(cardNum) !== -1;

        btn.style.display = (visibleCount > 1 && cardNum > 1 && isVisible) ? '' : 'none';
    }
}

(function () {
    var countInput = document.getElementById('landing_testimonial_card_count');
    if (!countInput) return;

    var count = parseInt(countInput.value, 10) || 2;
    var addBtn = document.getElementById('testimonials-add-more-btn');

    if (addBtn) {
        addBtn.style.display = count >= 10 ? 'none' : '';
    }

    testimonialsUpdateRemoveButtons();
})();

