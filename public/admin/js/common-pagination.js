/**
 * Global AJAX pagination for common-pagination.blade.php
 * Works with any page that wraps table + pagination in a container with:
 *   - id="your-container-id"
 *   - data-pagination-route="{{ route('your.index.route') }}"
 * Optional: data-pagination-extra='{"search":"#searchInput"}' for extra query params
 */
(function ($) {
    "use strict";

    $(document).on('click', '.ajax-page', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        if (!page) return;

        var $wrapper = $(this).closest('[data-pagination-route]');
        var url = $wrapper.data('pagination-route');
        var containerId = $wrapper.attr('id');
        if (!containerId || !url) return;

        var params = { page: page };
        var extra = $wrapper.data('pagination-extra');
        if (extra && typeof extra === 'object') {
            $.each(extra, function (key, selector) {
                var $el = $(selector);
                if ($el.length && $el.val) params[key] = $el.val();
            });
        }

        $.ajax({
            url: url,
            type: 'GET',
            data: params,
            success: function (html) {
                $('#' + containerId).html(html);
            },
            error: function () {
                if (typeof toastr !== 'undefined') {
                    toastr.error('Failed to load page');
                }
            }
        });
    });
})(jQuery);
