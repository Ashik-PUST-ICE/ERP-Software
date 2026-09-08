(function ($) {
    "use strict";

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';
        html += '<a class="paginate_button previous ' + (currentPage === 1 ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage > 1 ? currentPage - 1 : '') + '" role="link"><i class="fa-solid fa-angles-left"></i></a>';
        if (start > 1) { html += '<a class="paginate_button ajax-page" data-page="1" role="link">1</a>'; if (start > 2) html += '<span><a class="paginate_button disabled" role="link">...</a></span>'; }
        for (var page = start; page <= end; page++) html += currentPage === page ? '<span><a class="paginate_button current" data-page="' + page + '">' + page + '</a></span>' : '<a class="paginate_button ajax-page" data-page="' + page + '">' + page + '</a>';
        if (end < totalPages) { if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link">...</a></span>'; html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '">' + totalPages + '</a>'; }
        html += '<a class="paginate_button next ' + (currentPage === totalPages ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : '') + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
        return html;
    }

    $(document).ready(function () {
        var table = $('#garmentOrderDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true, responsive: true,
            searching: true, paging: true, info: false, stateSave: false, dom: 't',
            ajax: { url: $('#garment-order-data-route').val(), type: 'GET' },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', responsivePriority: 1, searchable: false, orderable: false },
                { data: 'style_name', name: 'style.style_code' },
                { data: 'order_number', name: 'order_number' },
                { data: 'buyer_name', name: 'buyer.company_name' },
                { data: 'quantity_display', name: 'quantity', searchable: false },
                { data: 'delivery_date_display', name: 'delivery_date', searchable: false },
                { data: 'status', name: 'status', searchable: false, orderable: false },
                { data: 'action', name: 'action', searchable: false, responsivePriority: 2, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var $wrap = $('#garment-order-pagination-wrap');
                if (!$wrap.length) { $('#garmentOrderDataTable').after('<div id="garment-order-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>'); $wrap = $('#garment-order-pagination-wrap'); }
                $wrap.html(renderGlobalPagination(info.pages, info.page + 1));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) { event.preventDefault(); table.page($(this).data('page') - 1).draw('page'); });
            }
        });

        $('#orderSearchData').off('keyup.garmentOrder').on('keyup.garmentOrder', function () { table.search(this.value).draw(); });
    });
})(jQuery);
