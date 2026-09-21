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

        for (var page = start; page <= end; page++) {
            if (currentPage === page) {
                html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + page + '">' + page + '</a></span>';
            } else {
                html += '<a class="paginate_button ajax-page" data-page="' + page + '" role="link">' + page + '</a>';
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
        var table = $('#apArDataTable').DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            dom: 't',
            ajax: { url: $('#ap-ar-data-route').val() },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', responsivePriority: 1, searchable: false, orderable: false },
                { data: 'invoice_number', name: 'invoice_number' },
                { data: 'buyer_name', name: 'order.buyer.company_name' },
                { data: 'due_date_display', name: 'due_date', searchable: false },
                { data: 'total_display', name: 'total_amount', searchable: false },
                { data: 'outstanding_display', name: 'paid_amount', searchable: false },
                { data: 'status', name: 'status', searchable: false, responsivePriority: 2, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $('#ap-ar-pagination-wrap');

                if (!$wrap.length) {
                    $('#apArDataTable').after('<div id="ap-ar-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $('#ap-ar-pagination-wrap');
                }

                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) {
                    event.preventDefault();
                    var page = $(this).data('page');
                    if (page) table.page(page - 1).draw('page');
                });
            }
        });

        table.on('xhr.dt', function (e, settings, json, xhr) {
            if (json && json.stats) {
                $('#kpiInvoiceTotal').text(json.stats.invoiceTotal);
                $('#kpiCollectedTotal').text(json.stats.paidTotal);
                $('#kpiReceivableTotal').text(json.stats.outstandingTotal);
                $('#kpiPayablesTotal').text(json.stats.payables);
            }
        });

        $('#searchData').off('keyup.garmentApAr').on('keyup.garmentApAr', function () {
            table.search(this.value).draw();
        });
    });
})(jQuery);
