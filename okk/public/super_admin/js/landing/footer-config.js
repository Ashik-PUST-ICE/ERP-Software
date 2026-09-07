function socialMediaAddMore() {
    var wrapper = document.getElementById('social-media-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.social-media-card-block');
    var visible = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            visible++;
        }
    }

    if (visible >= 4) return;

    var nextNum = visible + 1;
    var block = wrapper.querySelector('.social-media-card-block[data-card-num="' + nextNum + '"]');

    if (block) {
        block.style.display = '';
        var countInput = document.getElementById('social_media_count');
        if (countInput) {
            countInput.value = nextNum;
        }
    }

    var addBtn = document.getElementById('social-media-add-more-btn');
    if (addBtn) {
        addBtn.style.display = nextNum >= 4 ? 'none' : '';
    }

    socialMediaUpdateRemoveButtons();
}

function socialMediaRemoveCard(num) {
    var wrapper = document.getElementById('social-media-cards-wrapper');
    if (!wrapper || num <= 1) return;

    var block = wrapper.querySelector('.social-media-card-block[data-card-num="' + num + '"]');
    if (block) {
        block.style.display = 'none';
        
        // Clear the input values when removing
        var inputs = block.querySelectorAll('input');
        for (var i = 0; i < inputs.length; i++) {
            inputs[i].value = '';
        }
    }

    var lastVisible = 0;
    var blocks = wrapper.querySelectorAll('.social-media-card-block');

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisible = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var countInput = document.getElementById('social_media_count');
    if (countInput) {
        countInput.value = lastVisible || 1;
    }

    var addBtn = document.getElementById('social-media-add-more-btn');
    if (addBtn) {
        addBtn.style.display = (lastVisible >= 4) ? 'none' : '';
    }

    socialMediaUpdateRemoveButtons();
}

function socialMediaUpdateRemoveButtons() {
    var wrapper = document.getElementById('social-media-cards-wrapper');
    if (!wrapper) return;

    var blocks = wrapper.querySelectorAll('.social-media-card-block');
    var lastVisibleNum = 0;

    for (var i = 0; i < blocks.length; i++) {
        if (blocks[i].style.display !== 'none') {
            lastVisibleNum = parseInt(blocks[i].getAttribute('data-card-num'), 10);
        }
    }

    var removeBtns = wrapper.querySelectorAll('.social-media-remove-btn');

    for (var j = 0; j < removeBtns.length; j++) {
        var btn = removeBtns[j];
        var match = btn.getAttribute('onclick') && btn.getAttribute('onclick').match(/\d+/);
        var cardNum = match ? parseInt(match[0], 10) : NaN;
        btn.style.display = (cardNum === lastVisibleNum && lastVisibleNum > 1) ? '' : 'none';
    }
}

(function () {
    var countInput = document.getElementById('social_media_count');
    if (!countInput) return;

    var count = parseInt(countInput.value, 10) || 1;
    var addBtn = document.getElementById('social-media-add-more-btn');

    if (addBtn) {
        addBtn.style.display = count >= 4 ? 'none' : '';
    }

    socialMediaUpdateRemoveButtons();
})();
