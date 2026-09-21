(function ($) {
    "use strict";

    $(document).on('click', '.hrm-report-link', function (event) {
        event.preventDefault();
        var $link = $(this);
        var url = new URL($link.data('base-url'), window.location.origin);
        ($link.data('filters') || '').split(',').forEach(function (filter) {
            var parts = filter.trim().split(':');
            var id = parts[0];
            var parameter = parts[1] || id.replace('filter', '').toLowerCase();
            if (!id) return;
            var value = $('#' + id).val();
            if (value) url.searchParams.set(parameter, value);
        });
        window.open(url.toString(), '_blank');
    });
}(jQuery));
