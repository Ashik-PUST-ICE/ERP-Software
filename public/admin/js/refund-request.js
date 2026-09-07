(function ($) {
    "use strict";

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';
        html += '<a class="paginate_button previous ' + (currentPage === 1 ? "disabled" : "ajax-page") + '" data-page="' + (currentPage > 1 ? currentPage - 1 : "") + '" role="link"><i class="fa-solid fa-angles-left"></i></a>';
        if (start > 1) {
            html += '<a class="paginate_button ajax-page" data-page="1" role="link">1</a>';
            if (start > 2) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
        }
        for (var p = start; p <= end; p++) {
            if (currentPage === p) html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + "</a></span>";
            else html += '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + "</a>";
        }
        if (end < totalPages) {
            if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
            html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + "</a>";
        }
        html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
        return html;
    }

    $(document).ready(function () {
        var table = $('#refundRequestDatatable').DataTable({
            pageLength: 8,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            ajax: $('#refund-request-route').val(),
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'></i>",
                    next: "<i class='fa fa-chevron-right'></i>"
                }
            },
            dom: 't',
            columnDefs: [
                {
                    targets: 'keep-show',
                    className: 'all'
                }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#refund-pagination-wrap");
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) table.page(page - 1).draw("page");
                });
            },
            columns: [
                { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false, responsivePriority: 1 },
                { "data": "subscription_type", "name": "subscription_type", searchable: false, responsivePriority: 2 },
                { "data": "customer_name", "name": "users.name" },
                { "data": "customer_email", "name": "users.email" },
                { "data": "package_name", "name": "package_name" },
                { "data": "refunds_reasons", "name": "subscription_refunds.reasons" },
                { "data": "request_time", "name": "subscription_refunds.created_at" },
                { "data": "status", "name": "status" },
                { "data": "action", "name": "action", searchable: false, responsivePriority: 3 }
            ]
        });

        // External search input
        $('#searchData').off('keyup.refund').on('keyup.refund', function () {
            table.search(this.value).draw();
        });
    });

})(jQuery);
