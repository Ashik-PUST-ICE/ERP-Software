(function ($) {
    "use strict";

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';

        html += '<a class="paginate_button previous ' + (currentPage === 1 ? "disabled" : "ajax-page") +
            '" data-page="' + (currentPage > 1 ? currentPage - 1 : "") + '" role="link"><i class="fa-solid fa-angles-left"></i></a>';

        if (start > 1) {
            html += '<a class="paginate_button ajax-page" data-page="1" role="link">1</a>';
            if (start > 2) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
        }

        for (var p = start; p <= end; p++) {
            if (currentPage === p) {
                html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + "</a></span>";
            } else {
                html += '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + "</a>";
            }
        }

        if (end < totalPages) {
            if (end < totalPages - 1) {
                html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
            }
            html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + "</a>";
        }

        html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") +
            '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a>';
        html += "</div>";
        return html;
    }

    $(document).ready(function () {
        var dataUrl = $('#merchandiser-data-url').val() || '';
        console.log('merchandiser init', dataUrl);
        if (!dataUrl) return;

        var ordersTable = $('#merchandiserOrdersTable').DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            ajax: {
                url: dataUrl,
                type: 'GET',
                data: function (d) {
                    d.section = 'orders';
                },
                error: function (xhr, error, thrown) {
                    console.error('merchandiser orders ajax error', xhr.status, thrown);
                }
            },
            dom: 't',
            columnDefs: [
                {
                    targets: 'keep-show',
                    className: 'all'
                }
            ],
            columns: [
                { data: 'order_number', name: 'order_number', responsivePriority: 1 },
                { data: 'buyer', name: 'buyer', searchable: false },
                { data: 'delivery_date', name: 'delivery_date', searchable: false, orderable: false },
                { data: 'quantity', name: 'quantity', searchable: false, orderable: false }
            ],
            drawCallback: function () {
                var info = ordersTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;

                var $wrap = $("#merchandiser-orders-pagination-wrap");
                if (!$wrap.length) {
                    $('#merchandiserOrdersTable').after('<div id="merchandiser-orders-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#merchandiser-orders-pagination-wrap");
                }

                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) ordersTable.page(page - 1).draw("page");
                });
            }
        });

        $.ajax({
            url: dataUrl,
            type: 'GET',
            data: { section: 'overdue_tasks' },
            dataType: 'json',
            cache: false,
            success: function (response) {
                var tasks = response.overdue_tasks || [];
                var $target = $('#merchandiserOverdueTasks');
                if (!tasks.length) {
                    $target.html('<p class="text-muted mb-0">No overdue tasks</p>');
                    return;
                }

                var html = '';
                $.each(tasks, function (_, task) {
                    html += '<div class="border-bottom py-2"><strong>' + task.task_name + '</strong>';
                    html += '<p class="small text-muted mb-0">' + task.order_number + ' · ' + task.planned_date + '</p></div>';
                });
                $target.html(html);
            },
            error: function (xhr, error, thrown) {
                console.error('merchandiser overdue tasks ajax error', xhr.status, thrown);
                $('#merchandiserOverdueTasks').html('<p class="text-danger mb-0">Failed to load data</p>');
            }
        });

        $.ajax({
            url: dataUrl,
            type: 'GET',
            data: { section: 'shipments' },
            dataType: 'json',
            cache: false,
            success: function (response) {
                var shipments = response.shipments || [];
                var $target = $('#merchandiserShipments');
                if (!shipments.length) {
                    $target.html('<p class="text-muted mb-0">No pending shipments</p>');
                    return;
                }

                var html = '<div class="row g-3">';
                $.each(shipments, function (_, shipment) {
                    html += '<div class="col-md-4"><div class="border rounded p-3">';
                    html += '<strong>' + shipment.document_number + '</strong>';
                    html += '<p class="mb-1">' + shipment.order_number + '</p>';
                    html += '<span class="text-muted">' + (shipment.carrier || 'Carrier not assigned') + '</span>';
                    html += '</div></div>';
                });
                html += '</div>';
                $target.html(html);
            },
            error: function (xhr, error, thrown) {
                console.error('merchandiser shipments ajax error', xhr.status, thrown);
                $('#merchandiserShipments').html('<p class="text-danger mb-0">Failed to load data</p>');
            }
        });
    });

})(jQuery);
