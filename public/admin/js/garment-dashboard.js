(function ($) {
    "use strict";

    $(document).ready(function () {
        var $root = $("[data-dashboard-url]");
        if (!$root.length) return;

        var url = $root.data("dashboard-url");
        var $cards = $root.find("[data-card-key]");
        var $refreshButton = $root.find("[data-dashboard-refresh]");
        var $retryButton = $root.find("[data-dashboard-retry]");
        var $errorPanel = $root.find("[data-dashboard-error]");

        function escapeHtml(value) {
            return $("<div>").text(value == null ? "" : value).html();
        }

        function renderOrders(orders) {
            var $body = $root.find("[data-orders-body]");
            if (!orders.length) {
                $body.html('<tr><td colspan="4" class="text-center text-muted">No orders found</td></tr>');
                return;
            }
            $body.html(orders.map(function (order) {
                return "<tr><td><strong>" + escapeHtml(order.number) + "</strong></td><td>" + escapeHtml(order.buyer) + "</td><td>" + escapeHtml(order.quantity) + "</td><td>" + escapeHtml(order.delivery) + "</td></tr>";
            }).join(""));
        }

        function renderNotifications(notifications) {
            var $body = $root.find("[data-notifications-body]");
            if (!notifications.length) {
                $body.html('<p class="text-muted mb-0">No notifications</p>');
                return;
            }
            $body.html(notifications.map(function (notification) {
                return '<div class="garment-notification-item"><span class="garment-notification-icon"><i class="fa-regular fa-bell"></i></span><div><strong>' + escapeHtml(notification.title) + "</strong><p>" + escapeHtml(notification.body) + "</p></div></div>";
            }).join(""));
        }

        function loadDashboard() {
            $root.addClass("is-loading");
            $errorPanel.prop("hidden", true);
            $refreshButton.prop("disabled", true);

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                cache: false,
                headers: { "X-Requested-With": "XMLHttpRequest" }
            }).done(function (payload) {
                if (!payload || !payload.cards || !Array.isArray(payload.orders) || !Array.isArray(payload.notifications)) {
                    $errorPanel.prop("hidden", false);
                    return;
                }
                $cards.each(function () {
                    var key = $(this).data("card-key");
                    $(this).find("[data-card-value]").text(Number(payload.cards[key] || 0).toLocaleString());
                });
                renderOrders(payload.orders);
                renderNotifications(payload.notifications);
            }).fail(function () {
                $errorPanel.prop("hidden", false);
            }).always(function () {
                $root.removeClass("is-loading");
                $refreshButton.prop("disabled", false);
            })
        }

        $refreshButton.on("click", loadDashboard);
        $retryButton.on("click", loadDashboard);
        loadDashboard();
    });
}(jQuery));
