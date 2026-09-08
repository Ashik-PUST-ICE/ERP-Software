(function ($) {
    "use strict";

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var html = '<div class="dataTables_paginate paging_simple_numbers">';
        html += '<a class="paginate_button previous ' + (currentPage === 1 ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage > 1 ? currentPage - 1 : '') + '"><i class="fa-solid fa-angles-left"></i></a>';
        for (var page = Math.max(1, currentPage - 2); page <= Math.min(totalPages, currentPage + 2); page++) html += currentPage === page ? '<span><a class="paginate_button current">' + page + '</a></span>' : '<a class="paginate_button ajax-page" data-page="' + page + '">' + page + '</a>';
        html += '<a class="paginate_button next ' + (currentPage === totalPages ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : '') + '"><i class="fa-solid fa-angles-right"></i></a></div>';
        return html;
    }

    $(document).ready(function () {
        var table = $('#garmentTnaDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true, responsive: true, searching: true, paging: true, info: false, stateSave: false, dom: 't',
            ajax: { url: $('#garment-tna-data-route').val(), type: 'GET' },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', searchable: false, orderable: false }, { data: 'order_number', name: 'order.order_number' }, { data: 'style_code', name: 'order.style.style_code' }, { data: 'task_name', name: 'task_name' }, { data: 'planned_date_display', name: 'planned_date', searchable: false }, { data: 'employee_name', name: 'employee.first_name', searchable: false }, { data: 'status', name: 'status', searchable: false, orderable: false }, { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var $wrap = $('#garment-tna-pagination-wrap');
                if (!$wrap.length) { $('#garmentTnaDataTable').after('<div id="garment-tna-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>'); $wrap = $('#garment-tna-pagination-wrap'); }
                $wrap.html(renderGlobalPagination(info.pages, info.page + 1));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) { event.preventDefault(); table.page($(this).data('page') - 1).draw('page'); });
            }
        });
        $('#tnaSearchData').off('keyup.garmentTna').on('keyup.garmentTna', function () { table.search(this.value).draw(); });
    });
})(jQuery);
