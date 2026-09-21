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
                html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + '</a></span>';
            } else {
                html += '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + '</a>';
            }
        }

        if (end < totalPages) {
            if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
            html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + '</a>';
        }

        html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") +
            '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a>';
        return html + '</div>';
    }

    $(document).ready(function () {
        var route = $('#audit-logs-route').val();
        var table = $('#auditLogsTable').DataTable({
            pageLength: 25,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            dom: 't',
            ajax: {
                url: route,
                type: 'GET',
                data: function (d) {
                    d.module = $('#auditLogModuleFilter').val();
                    d.action = $('#auditLogActionFilter').val();
                }
            },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'created_at', name: 'created_at', responsivePriority: 1 },
                { data: 'action', name: 'action', searchable: false },
                { data: 'module', name: 'module', searchable: false },
                { data: 'description', name: 'description' },
                { data: 'ip_address', name: 'ip_address', searchable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $('#audit-logs-pagination-wrap');

                if (!$wrap.length) {
                    $('#auditLogsTable').after('<div id="audit-logs-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $('#audit-logs-pagination-wrap');
                }

                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) {
                    event.preventDefault();
                    var page = $(this).data('page');
                    if (page) table.page(page - 1).draw('page');
                });
            }
        });

        $('#searchData').off('keyup.garmentAuditLogs').on('keyup.garmentAuditLogs', function () {
            table.search(this.value).draw();
        });

        $('#auditLogModuleFilter, #auditLogActionFilter').on('change', function () {
            table.ajax.reload();
        });
    });
})(jQuery);
