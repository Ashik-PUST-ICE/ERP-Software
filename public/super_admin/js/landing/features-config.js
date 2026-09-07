function featuresAddMore() {
    var wrapper = document.getElementById('features-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.feature-card-block');
    var visible = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            visible++;
        }
    }

    if (visible >= 6) return;

    var nextNum = visible + 1;
    var block = wrapper.querySelector('.feature-card-block[data-card-num="' + nextNum + '"]');

    if (block) {
        block.style.display = '';
        var countInput = document.getElementById('landing_feature_card_count');
        if (countInput) {
            countInput.value = nextNum;
        }
    }

    var addBtn = document.getElementById('features-add-more-btn');
    if (addBtn) {
        addBtn.style.display = nextNum >= 6 ? 'none' : '';
    }

    featuresUpdateRemoveButtons();
}

function featuresRemoveCard(num) {
    var wrapper = document.getElementById('features-cards-wrapper');
    if (!wrapper || num <= 1) return;

    var block = wrapper.querySelector('.feature-card-block[data-card-num="' + num + '"]');
    if (block) {
        block.style.display = 'none';
    }

    var lastVisible = 0;
    var blocks = wrapper.querySelectorAll('.feature-card-block');

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisible = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var countInput = document.getElementById('landing_feature_card_count');
    if (countInput) {
        countInput.value = lastVisible;
    }

    var addBtn = document.getElementById('features-add-more-btn');
    if (addBtn) {
        addBtn.style.display = lastVisible >= 6 ? 'none' : '';
    }

    featuresUpdateRemoveButtons();
}

function featuresUpdateRemoveButtons() {
    var wrapper = document.getElementById('features-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.feature-card-block');
    var lastVisibleNum = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisibleNum = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var removeBtns = wrapper.querySelectorAll('.features-remove-btn');

    for (var j = 0; j < removeBtns.length; j++) {
        var btn = removeBtns[j];
        var match = btn.getAttribute('onclick') && btn.getAttribute('onclick').match(/\d+/);
        var cardNum = match ? parseInt(match[0], 10) : NaN;
        btn.style.display = (cardNum === lastVisibleNum && lastVisibleNum > 1) ? '' : 'none';
    }
}

(function () {
    var countInput = document.getElementById('landing_feature_card_count');
    if (!countInput) return;

    var count = parseInt(countInput.value, 10) || 1;
    var addBtn = document.getElementById('features-add-more-btn');

    if (addBtn) {
        addBtn.style.display = count >= 6 ? 'none' : '';
    }

    featuresUpdateRemoveButtons();
})();

