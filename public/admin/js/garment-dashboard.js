(function ($) {
    "use strict";

    var dashboardDataUrl = $('#garment-dashboard-data-url').val() || '';

    function escapeHtml(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function renderNotifications(notifications) {
        var html = '';
        if (!notifications || !notifications.length) {
            html = '<p class="text-muted mb-0">No notifications</p>';
        } else {
            $.each(notifications, function (_, notification) {
                html += '<div class="border-bottom py-2"><strong>' + escapeHtml(notification.title) + '</strong>';
                html += '<p class="small text-muted mb-0">' + escapeHtml(notification.body) + '</p></div>';
            });
        }
        $('#garmentNotifications').html(html);
    }

    function loadDashboardData() {
        if (!dashboardDataUrl) return;

        $.ajax({
            url: dashboardDataUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function (response) {
                if (response.cards) {
                    $('#kpiOverdueOrders').text(response.cards.overdueOrders);
                    $('#kpiLowStock').text(response.cards.lowStock);
                    $('#kpiPendingFinishing').text(response.cards.pendingFinishing);
                    $('#kpiReadyShipments').text(response.cards.readyShipments);
                }
                renderNotifications(response.notifications || []);
            },
            error: function () {
                $('#garmentNotifications').html('<p class="text-danger mb-0">Failed to load notifications</p>');
            }
        });
    }

    function initRecentOrdersTable() {
        var $table = $('#garmentRecentOrdersTable');
        if (!$table.length || !$.fn.DataTable) return;

        $table.DataTable({
            pageLength: 5,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: false,
            paging: true,
            info: false,
            ajax: {
                url: $table.data('url'),
                type: 'GET'
            },
            language: {
                processing: '<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></div>',
                emptyTable: '<div class="text-center py-3 text-muted">No orders found</div>'
            },
            dom: 't',
            columnDefs: [
                { targets: 'keep-show', className: 'all' }
            ],
            columns: [
                {
                    data: 'order_number',
                    name: 'order_number',
                    orderable: false,
                    searchable: false,
                    render: function (data) {
                        return '<strong>' + escapeHtml(data) + '</strong>';
                    }
                },
                { data: 'buyer_name', name: 'buyer_name', orderable: false, searchable: false },
                { data: 'quantity_display', name: 'quantity', orderable: false, searchable: false },
                { data: 'delivery_date_display', name: 'delivery_date', orderable: false, searchable: false }
            ]
        });
    }

    $(document).ready(function () {
        initRecentOrdersTable();
        loadDashboardData();
    });
}(jQuery));
