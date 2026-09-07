/**
 * Hashtag modal & tag-wrapper UI — single place for HashtagController usage.
 * Load this on any admin page that has #HashtagModal (scheduled post, template, campaign).
 */
(function ($) {
  "use strict";

  $(function () {
    let suggestions = [];

    function fetchAndFillHashtags(doneCallback) {
      $.ajax({
        url: '/autopost/admin/hashtags',
        type: 'GET',
        success: function (response) {
          if (response.status && response.data) {
            suggestions = response.data.map(h => h.name.replace('#', ''));
            let $bottomTags = $('#HashtagModal .bottom-tags');
            $bottomTags.empty();
            suggestions.forEach(tag => {
              $bottomTags.append(`<div class="bottom-tag" data-tag="${tag}">#${tag}</div>`);
            });
          }
          if (typeof doneCallback === 'function') doneCallback();
        },
        error: function () {
          suggestions = [];
          if (typeof doneCallback === 'function') doneCallback();
        }
      });
    }

    // Load when Hashtag modal is opened
    $(document).on('shown.bs.modal', '#HashtagModal', function () {
      fetchAndFillHashtags();
    });

    // Reload after store (create-post, template, campaign trigger this)
    $(document).on('hashtagsReload', function () {
      fetchAndFillHashtags(function () {
        $(document).trigger('hashtagsReloadDone');
      });
    });

    // Tag-wrapper UI (input, suggestions, bottom-tags, create tag)
    $('.tag-wrapper').each(function () {
      let wrapper = $(this);
      let tagBox = wrapper.find('.tag-box');
      let input = wrapper.find('.tag-input');
      let suggestionBox = wrapper.find('.suggestion-box');
      let bottomTags = wrapper.find('.bottom-tags');
      let tags = [];

      input.on('input', function () {
        let val = $(this).val().toLowerCase().replace('#', '');
        suggestionBox.empty();
        if (val === '') {
          suggestionBox.hide();
          return;
        }
        let matched = suggestions.filter(t => t.startsWith(val));
        if (matched.length) {
          matched.forEach(item => {
            suggestionBox.append(`<div class="suggestion">${item}</div>`);
          });
          suggestionBox.show();
        } else {
          suggestionBox.hide();
        }
      });

      input.on('keydown', function (e) {
        if (e.key === "Enter" || e.key === " ") {
          e.preventDefault();
          createTags($(this).val());
        }
        if (e.key === "Backspace" && $(this).val() === '' && tags.length) {
          let last = tags.pop();
          tagBox.find('.tag[data-tag="' + last + '"]').remove();
        }
      });

      input.on('paste', function () {
        setTimeout(() => {
          createTags($(this).val());
        }, 50);
      });

      suggestionBox.on('click', '.suggestion', function () {
        createTags($(this).text());
      });

      bottomTags.on('click', '.bottom-tag', function () {
        createTags($(this).data('tag'));
      });

      tagBox.on('click', '.remove', function () {
        let tag = $(this).parent().data('tag');
        tags = tags.filter(t => t !== tag);
        $(this).parent().remove();
      });

      function createTags(text) {
        if (!text) return;
        let parts = text.split(' ');
        parts.forEach(part => {
          let tag = part.trim();
          if (tag === '') return;
          if (!tag.startsWith('#')) tag = '#' + tag;
          tag = tag.toLowerCase();
          if (tags.includes(tag)) return;
          tags.push(tag);
          input.before(`
            <div class="tag" data-tag="${tag}">
              ${tag} <span class="remove">&times;</span>
            </div>
          `);
        });
        input.val('');
        suggestionBox.hide();
      }
    });
  });
})(jQuery);
