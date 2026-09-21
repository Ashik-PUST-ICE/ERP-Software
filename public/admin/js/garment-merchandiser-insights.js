(function ($) {
    "use strict";

    var dataUrl = $('#garment-merchandiser-insights-data-url').val() || '';

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function number(value) {
        return Number(value || 0).toLocaleString();
    }

    function renderOptions(selector, items, placeholder, labelKey) {
        var html = '<option value="">' + placeholder + '</option>';
        var options = items || [];
        $.each(options, function (_, item) {
            html += '<option value="' + escapeHtml(item.id) + '">' + escapeHtml(labelKey ? item[labelKey] : item.label) + '</option>';
        });
        if (!options.length) {
            html = '<option value="">No active orders found</option>';
        }
        $(selector).html(html).prop('disabled', !options.length);
    }

    function renderHistory(items) {
        if (!items || !items.length) {
            $('#merchandiserHandoverHistory').html('<div class="merchandiser-empty"><i class="fa-solid fa-clock"></i><span>No handover records yet</span></div>');
            return;
        }
        var html = '';
        $.each(items, function (_, item) {
            html += '<div class="merchandiser-handover-item"><strong>' + escapeHtml(item.order) + '</strong>';
            html += '<p>' + escapeHtml(item.from) + ' <span>→</span> ' + escapeHtml(item.to) + '</p>';
            html += '<small>' + escapeHtml(item.date) + '</small></div>';
        });
        $('#merchandiserHandoverHistory').html(html);
    }

    function loadInsightsData() {
        if (!dataUrl) return;
        $.ajax({
            url: dataUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function (response) {
                var cards = response.cards || {};
                $('#insightOrders').text(number(cards.orders ?? cards.orderCount));
                $('#insightOverdueTasks').text(number(cards.overdue_tasks ?? cards.overdueTasks));
                $('#insightDeliveryRisk').text(number(cards.delivery_risk ?? cards.deliveryRisk));
                $('#insightBuyers').text(number(cards.buyers ?? cards.buyerCount));
                renderOptions('#insightOrderSelect', response.orders, 'Select Order', null);
                renderOptions('#insightUserSelect', response.users, 'New Merchandiser', 'name');
                renderHistory(response.handovers || []);
            },
            error: function () {
                $('.merchandiser-insights-kpis h2').text('--');
                $('#insightOrderSelect, #insightUserSelect').html('<option value="">Failed to load options</option>').prop('disabled', true);
                $('#merchandiserHandoverHistory').html('<div class="text-danger py-4">Failed to load dashboard data</div>');
            }
        });
    }

    $(function () {
        loadInsightsData();
        $('#orderHandoverModal').on('shown.bs.modal', loadInsightsData);
    });
}(jQuery));
