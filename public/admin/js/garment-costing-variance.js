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
        var route = $('#costing-variance-route').val();
        var table = $('#costingVarianceTable').DataTable({
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
            ajax: {
                url: route,
                type: 'GET',
                data: function (d) {
                    d.order_id = $('#costingVarianceOrderFilter').val();
                }
            },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'order_number', name: 'order_number', responsivePriority: 1 },
                { data: 'buyer', name: 'buyer', searchable: false },
                { data: 'estimated_cost', name: 'estimated_cost', searchable: false, orderable: false },
                { data: 'actual_cost', name: 'actual_cost', searchable: false, orderable: false },
                { data: 'variance', name: 'variance', searchable: false, orderable: false },
                { data: 'margin', name: 'margin', searchable: false, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;

                var $wrap = $("#costing-variance-pagination-wrap");
                if (!$wrap.length) {
                    $('#costingVarianceTable').after('<div id="costing-variance-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#costing-variance-pagination-wrap");
                }

                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) table.page(page - 1).draw("page");
                });
            }
        });

        $('#searchData').off('keyup.garmentCostingVariance').on('keyup.garmentCostingVariance', function () {
            table.search(this.value).draw();
        });

        $('#costingVarianceOrderFilter').change(function () {
            table.ajax.reload();
        });
    });
})(jQuery);
