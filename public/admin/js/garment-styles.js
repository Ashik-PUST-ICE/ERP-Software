(function ($) {
    "use strict";

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';
        html += '<a class="paginate_button previous ' + (currentPage === 1 ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage > 1 ? currentPage - 1 : '') + '"><i class="fa-solid fa-angles-left"></i></a>';
        for (var page = start; page <= end; page++) html += currentPage === page ? '<span><a class="paginate_button current">' + page + '</a></span>' : '<a class="paginate_button ajax-page" data-page="' + page + '">' + page + '</a>';
        html += '<a class="paginate_button next ' + (currentPage === totalPages ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : '') + '"><i class="fa-solid fa-angles-right"></i></a></div>';
        return html;
    }

    $(document).ready(function () {
        var table = $('#garmentStyleDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true, responsive: true, searching: true, paging: true, info: false, stateSave: false, dom: 't',
            ajax: { url: $('#garment-style-data-route').val(), type: 'GET' },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', searchable: false, orderable: false }, { data: 'style_code', name: 'style_code' }, { data: 'style_name', name: 'style_name' }, { data: 'product_type', name: 'product_type' }, { data: 'season', name: 'season' }, { data: 'orders_count', name: 'orders_count', searchable: false }, { data: 'status', name: 'status', searchable: false, orderable: false }, { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var $wrap = $('#garment-style-pagination-wrap');
                if (!$wrap.length) { $('#garmentStyleDataTable').after('<div id="garment-style-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>'); $wrap = $('#garment-style-pagination-wrap'); }
                $wrap.html(renderGlobalPagination(info.pages, info.page + 1));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) { event.preventDefault(); table.page($(this).data('page') - 1).draw('page'); });
            }
        });
        $('#styleSearchData').off('keyup.garmentStyle').on('keyup.garmentStyle', function () { table.search(this.value).draw(); });
    });
})(jQuery);
