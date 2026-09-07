function postManagementAddMore() {
    var wrapper = document.getElementById('post-management-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.post-management-card-block');
    var visible = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            visible++;
        }
    }

    if (visible >= 4) return;

    var nextNum = visible + 1;
    var block = wrapper.querySelector('.post-management-card-block[data-card-num="' + nextNum + '"]');

    if (block) {
        block.style.display = '';
        var countInput = document.getElementById('landing_post_card_count');
        if (countInput) {
            countInput.value = nextNum;
        }
    }

    var addBtn = document.getElementById('post-management-add-more-btn');
    if (addBtn) {
        addBtn.style.display = nextNum >= 4 ? 'none' : '';
    }

    postManagementUpdateRemoveButtons();
}

function postManagementRemoveCard(num) {
    var wrapper = document.getElementById('post-management-cards-wrapper');
    if (!wrapper || num <= 1) return;

    var block = wrapper.querySelector('.post-management-card-block[data-card-num="' + num + '"]');
    if (block) {
        block.style.display = 'none';
    }

    var lastVisible = 0;
    var blocks = wrapper.querySelectorAll('.post-management-card-block');

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisible = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var countInput = document.getElementById('landing_post_card_count');
    if (countInput) {
        countInput.value = lastVisible || 1;
    }

    var addBtn = document.getElementById('post-management-add-more-btn');
    if (addBtn) {
        addBtn.style.display = (lastVisible >= 4) ? 'none' : '';
    }

    postManagementUpdateRemoveButtons();
}

function postManagementUpdateRemoveButtons() {
    var wrapper = document.getElementById('post-management-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.post-management-card-block');
    var lastVisibleNum = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisibleNum = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var removeBtns = wrapper.querySelectorAll('.post-management-remove-btn');

    for (var j = 0; j < removeBtns.length; j++) {
        var btn = removeBtns[j];
        var match = btn.getAttribute('onclick') && btn.getAttribute('onclick').match(/\d+/);
        var cardNum = match ? parseInt(match[0], 10) : NaN;
        btn.style.display = (cardNum === lastVisibleNum && lastVisibleNum > 1) ? '' : 'none';
    }
}

(function () {
    var countInput = document.getElementById('landing_post_card_count');
    if (!countInput) return;

    var count = parseInt(countInput.value, 10) || 1;
    var addBtn = document.getElementById('post-management-add-more-btn');

    if (addBtn) {
        addBtn.style.display = count >= 4 ? 'none' : '';
    }

    postManagementUpdateRemoveButtons();
})();

