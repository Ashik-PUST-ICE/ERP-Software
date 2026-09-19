(function ($) {
    "use strict";

    var dataUrl = $('#garment-analytics-data-url').val() || '';

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function number(value) {
        return Number(value || 0).toLocaleString();
    }

    function renderDailyOutput(days) {
        var $target = $('#garmentAnalyticsDailyOutput');
        if (!days || !days.length) {
            $target.html('<div class="analytics-empty"><i class="fa-solid fa-chart-line"></i><span>No production data available yet</span></div>');
            return;
        }

        var html = '';
        $.each(days, function (_, day) {
            html += '<div class="analytics-row"><span>' + escapeHtml(day.date) + '</span>';
            html += '<div class="analytics-track"><div class="analytics-target" style="width:' + Number(day.target_width || 0) + '%"></div>';
            html += '<div class="analytics-output" style="width:' + Number(day.output_width || 0) + '%"></div></div>';
            html += '<strong>' + number(day.output) + '</strong></div>';
        });
        $target.html(html);
    }

    function renderOrderStatus(items) {
        var $target = $('#garmentAnalyticsOrderStatus');
        if (!items || !items.length) {
            $target.html('<div class="analytics-empty"><i class="fa-solid fa-box-open"></i><span>No order data available yet</span></div>');
            return;
        }

        var html = '';
        $.each(items, function (_, item) {
            html += '<div class="analytics-status-row"><span>Status ' + escapeHtml(item.status) + '</span>';
            html += '<strong>' + number(item.total) + '</strong></div>';
        });
        $target.html(html);
    }

    function loadAnalytics() {
        if (!dataUrl) {
            return;
        }

        $.ajax({
            url: dataUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function (response) {
                var kpis = response.cards || response.kpis || {};
                $('#kpiAnalyticsOrders').text(Number(kpis.orders || 0).toLocaleString());
                $('#kpiAnalyticsPlanned').text(Number(kpis.planned_quantity || 0).toLocaleString());
                $('#kpiAnalyticsProduced').text(Number(kpis.produced_quantity || 0).toLocaleString());
                $('#kpiAnalyticsAchievement').text(Number(kpis.achievement || 0).toFixed(1) + '%');
                renderDailyOutput(response.daily_output || []);
                renderOrderStatus(response.order_status || []);
                if (response.kpis && response.kpis.rejection_rate !== undefined) {
                    $('#analyticsKpiRejectionRate').text(Number(response.kpis.rejection_rate || 0).toFixed(1) + '%');
                }
            },
            error: function () {
                $('#garmentAnalyticsDailyOutput, #garmentAnalyticsOrderStatus').html('<p class="text-danger mb-0">Failed to load analytics data</p>');
                $('.garment-analytics-kpis h2, #analyticsKpiRejectionRate').text('--');
            }
        });
    }

    $(document).ready(loadAnalytics);
}(jQuery));
