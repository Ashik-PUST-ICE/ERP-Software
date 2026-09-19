(function ($) {
    "use strict";
    var url = $('#garment-merchandiser-management-data-url').val() || '';
    function esc(value) { return $('<div>').text(value == null ? '' : value).html(); }
    function options(selector, items, label, placeholder) {
        var html = '<option value="">' + placeholder + '</option>';
        var $select = $(selector);
        if ($select.hasClass('select2-hidden-accessible')) {
            $select.select2('destroy');
        }
        $.each(items || [], function (_, item) { html += '<option value="' + esc(item.id) + '">' + esc(label ? item[label] : item.label) + '</option>'; });
        $select.html(html).prop('disabled', !(items && items.length)).val('');
    }
    function initMultipleSelects() {
        if (!$.fn.select2) {
            return;
        }
        $('.multipleSelect2').each(function () {
            var $select = $(this);
            if ($select.hasClass('select2-hidden-accessible')) {
                return;
            }
            $select.select2({
                placeholder: 'Select Merchandisers',
                allowClear: true,
                width: '100%'
            });
        });
    }
    function refreshSelects() {
        $('.merchandiser-management-modal .sf-select-without-search').each(function () {
            if ($(this).next('.nice-select').length) {
                $(this).niceSelect('update');
            } else if ($.fn.niceSelect) {
                $(this).niceSelect();
            }
        });
    }
    function renderActivity(data) {
        var html = '<div class="management-activity-grid"><div><h4>Tasks</h4>';
        if (!data.tasks || !data.tasks.length) html += '<p class="text-muted">No tasks found</p>';
        $.each(data.tasks || [], function (_, item) { html += '<div class="management-activity-item"><strong>' + esc(item.title) + '</strong><span>' + esc(item.order) + ' · ' + esc(item.due_date) + '</span></div>'; });
        html += '</div><div><h4>Communications</h4>';
        if (!data.communications || !data.communications.length) html += '<p class="text-muted">No communications found</p>';
        $.each(data.communications || [], function (_, item) { html += '<div class="management-activity-item"><strong>' + esc(item.channel) + ' · ' + esc(item.order) + '</strong><span>' + esc(item.subject) + ' · ' + esc(item.date) + '</span></div>'; });
        $('#merchandiserManagementActivity').html(html + '</div></div>');
    }
    function load() {
        if (!url) return;
        $.ajax({ url: url, type: 'GET', dataType: 'json', cache: false, success: function (data) {
            $('.management-order-select').each(function () { options(this, data.orders, null, 'Select order'); });
            $('.management-user-multiple').each(function () { options(this, data.users, 'name', 'Select merchandisers'); });
            $('.management-user-primary').each(function () { options(this, data.users, 'name', 'No primary selected'); });
            initMultipleSelects();
            refreshSelects();
            renderActivity(data);
        }, error: function () { $('#merchandiserManagementActivity').html('<p class="text-danger">Failed to load activities</p>'); } });
    }
    $(function () { initMultipleSelects(); load(); $('.modal').on('shown.bs.modal', function () { initMultipleSelects(); load(); }); });
}(jQuery));
