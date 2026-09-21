(function ($) {
    "use strict";

    var channelStyles = {
        email: { icon: 'fa-envelope', color: '#4778c7' },
        phone: { icon: 'fa-phone', color: '#10b981' },
        meeting: { icon: 'fa-handshake', color: '#f59e0b' },
        whatsapp: { icon: 'fa-whatsapp', color: '#25d366' }
    };

    function esc(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }

    function setCount(selector, value) {
        $(selector).text(Number(value || 0).toLocaleString());
    }

    function emptyState(message) {
        return '<div class="text-center text-muted py-4"><i class="fa-regular fa-folder-open fa-2x mb-2 d-block"></i>' + esc(message) + '</div>';
    }

    function renderOrders(orders) {
        var $body = $('#merchandiserOrderAssignmentsBody');
        if (!$body.length) {
            return;
        }
        if (!orders || !orders.length) {
            $body.html('<tr><td colspan="6">' + emptyState('No order assignments found.') + '</td></tr>');
            return;
        }

        var html = '';
        $.each(orders, function (_, order) {
            var primary = order.primary
                ? '<span class="d-inline-flex align-items-center gap-1 fw-600 text-dark"><i class="fa-solid fa-star text-warning me-1"></i>' + esc(order.primary) + '</span>'
                : '<span class="text-muted small">Not designated</span>';

            var team = '<span class="text-muted small">No merchandisers assigned</span>';
            if (order.team && order.team.length) {
                team = '<div class="d-flex flex-wrap gap-1">';
                $.each(order.team, function (_, member) {
                    team += '<span class="zBadge ' + (member.is_primary ? 'zBadge-primary' : 'zBadge-outline') + '" style="font-size:11px;">' + esc(member.name) + '</span>';
                });
                team += '</div>';
            }

            html += '<tr>'
                + '<td><strong class="text-primary">' + esc(order.order_number) + '</strong></td>'
                + '<td>' + esc(order.buyer || 'N/A') + '</td>'
                + '<td>' + esc(order.style || 'N/A') + '</td>'
                + '<td>' + primary + '</td>'
                + '<td>' + team + '</td>'
                + '<td class="text-end"><a href="' + esc(order.manage_url) + '" class="primary-btn-outline py-1 px-3 d-inline-flex align-items-center gap-1" style="font-size:12px;border-radius:6px;"><i class="fa-solid fa-pen-to-square"></i>Manage</a></td>'
                + '</tr>';
        });

        $body.html(html);
    }
    function renderTasks(tasks) {
        var $target = $('#merchandiserRecentTasks');
        if (!$target.length) {
            return;
        }
        if (!tasks || !tasks.length) {
            $target.html(emptyState('No recent tasks'));
            return;
        }

        var html = '';
        $.each(tasks, function (_, task) {
            html += '<div class="d-flex align-items-start gap-3 py-3" style="border-bottom:1px solid #f1f5f9;">'
                + '<div style="width:36px;height:36px;border-radius:10px;background:rgba(245,158,11,.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-list-check"></i></div>'
                + '<div style="flex:1;">'
                + '<div class="fw-600" style="color:#0f172a;font-size:13.5px;">' + esc(task.title) + '</div>'
                + '<div class="d-flex gap-3 flex-wrap mt-1" style="font-size:12px;color:#64748b;">'
                + '<span><i class="fa-solid fa-hashtag me-1"></i>' + esc(task.order || 'N/A') + '</span>'
                + (task.due_date ? '<span><i class="fa-regular fa-clock me-1"></i>' + esc(task.due_date) + '</span>' : '')
                + '</div></div>'
                + '<div><span class="zBadge ' + esc(task.status_class) + '">' + esc(task.status_label) + '</span></div>'
                + '</div>';
        });

        $target.html(html);
    }

    function renderCommunications(communications) {
        var $target = $('#merchandiserRecentCommunications');
        if (!$target.length) {
            return;
        }
        if (!communications || !communications.length) {
            $target.html(emptyState('No recent communications'));
            return;
        }

        var html = '';
        $.each(communications, function (_, item) {
            var style = channelStyles[String(item.channel || '').toLowerCase()] || { icon: 'fa-comments', color: '#64748b' };
            html += '<div class="d-flex align-items-start gap-3 py-3" style="border-bottom:1px solid #f1f5f9;">'
                + '<div style="width:36px;height:36px;border-radius:10px;background:' + style.color + '1a;color:' + style.color + ';display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid ' + style.icon + '"></i></div>'
                + '<div style="flex:1;">'
                + '<div class="fw-600" style="color:#0f172a;font-size:13.5px;">' + esc(item.channel) + (item.subject ? ' &mdash; ' + esc(item.subject) : '') + '</div>'
                + '<div class="d-flex gap-3 flex-wrap mt-1" style="font-size:12px;color:#64748b;">'
                + '<span><i class="fa-solid fa-hashtag me-1"></i>' + esc(item.order || 'N/A') + '</span>'
                + (item.date ? '<span><i class="fa-regular fa-clock me-1"></i>' + esc(item.date) + '</span>' : '')
                + '</div></div></div>';
        });

        $target.html(html);
    }

    function setErrorState() {
        $('#managementOrderCount, #managementTaskCount, #managementCommunicationCount').text('--');
        $('#merchandiserOrderAssignmentsBody').html('<tr><td colspan="6" class="text-center text-danger py-4">Failed to load order assignments</td></tr>');
        $('#merchandiserRecentTasks').html('<div class="text-center text-danger py-4">Failed to load tasks</div>');
        $('#merchandiserRecentCommunications').html('<div class="text-center text-danger py-4">Failed to load communications</div>');
    }

    $(document).ready(function () {
        var dataUrl = $('#garment-merchandiser-management-data-url').val() || '';
        console.log('merchandiser management init', dataUrl);
        if (!dataUrl) return;

        $.ajax({
            url: dataUrl,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function (response) {
                var cards = response.cards || {};
                setCount('#managementOrderCount', cards.orders);
                setCount('#managementTaskCount', cards.tasks);
                setCount('#managementCommunicationCount', cards.communications);
                renderOrders(response.orders);
                renderTasks(response.tasks);
                renderCommunications(response.communications);
            },
            error: function (xhr, error, thrown) {
                console.error('merchandiser management ajax error', xhr.status, thrown);
                setErrorState();
            }
        });
    });

})(jQuery);
