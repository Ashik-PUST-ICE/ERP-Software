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
        var table = $('#buyerDataTable').DataTable({
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
            ajax: { url: $('#buyer-data-route').val() },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', responsivePriority: 1, searchable: false, orderable: false },
                { data: 'buyer_code', name: 'buyer_code' },
                { data: 'company_name', name: 'company_name' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'email', name: 'email' },
                { data: 'phone', name: 'phone' },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'action', name: 'action', searchable: false, responsivePriority: 2, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $('#buyer-pagination-wrap');

                if (!$wrap.length) {
                    $('#buyerDataTable').after('<div id="buyer-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $('#buyer-pagination-wrap');
                }

                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) {
                    event.preventDefault();
                    var page = $(this).data('page');
                    if (page) table.page(page - 1).draw('page');
                });
            }
        });

        $('#searchData').off('keyup.garmentBuyer').on('keyup.garmentBuyer', function () {
            table.search(this.value).draw();
        });
    });
})(jQuery);
