(function ($) {
    "use strict";

    $(function () {
        var $input = $('#searchDataHeader');
        var $results = $('#admin-global-search-results');
        if (!$input.length || !$results.length) return;
        var timer;

        function icon(type) {
            return type === 'Buyer' ? 'fa-building' : (type === 'Order' ? 'fa-boxes-stacked' : 'fa-file-invoice-dollar');
        }
        function escapeHtml(value) { return $('<div>').text(value || '').html(); }
        function render(items) {
            if (!items.length) { $results.html('<div class="admin-search-empty">No matching record found</div>').prop('hidden', false); return; }
            $results.html(items.map(function (item) {
                return '<a class="admin-search-result" href="' + item.url + '"><i class="fa-solid ' + icon(item.type) + '"></i><span><strong>' + escapeHtml(item.label) + '</strong><small>' + escapeHtml(item.type + ' · ' + (item.meta || '')) + '</small></span></a>';
            }).join('')).prop('hidden', false);
        }
        $input.on('input', function () {
            var query = $.trim($input.val());
            clearTimeout(timer);
            if (query.length < 2) { $results.prop('hidden', true); return; }
            timer = setTimeout(function () {
                $.get($input.data('search-url'), { q: query }).done(function (response) { render(response.data || []); });
            }, 250);
        });
        $(document).on('click', function (event) {
            if (!$(event.target).closest('.search-area-wrap').length) $results.prop('hidden', true);
        });
    });
})(jQuery);
