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

    function updateAcceptedQuantity() {
        var received = parseFloat($('#grn-received-quantity').val()) || 0;
        var rejected = parseFloat($('#grn-rejected-quantity').val()) || 0;
        $('#grn-accepted-display').text(Math.max(0, received - rejected).toFixed(4));
    }

    $(document).ready(function () {
        var table = $('#garmentGrnDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true, responsive: true, searching: true, paging: true, info: false, stateSave: false, dom: 't',
            ajax: { url: $('#garment-grn-data-route').val(), type: 'GET' },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', searchable: false, orderable: false }, { data: 'grn_number', name: 'grn_number' }, { data: 'material_name', name: 'material.item_name' }, { data: 'supplier_name', name: 'supplier_name' }, { data: 'accepted_display', name: 'accepted_quantity', searchable: false }, { data: 'received_date_display', name: 'received_date', searchable: false }, { data: 'status', name: 'status', searchable: false, orderable: false }, { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var $wrap = $('#garment-grn-pagination-wrap');
                if (!$wrap.length) { $('#garmentGrnDataTable').after('<div id="garment-grn-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>'); $wrap = $('#garment-grn-pagination-wrap'); }
                $wrap.html(renderGlobalPagination(info.pages, info.page + 1));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) { event.preventDefault(); table.page($(this).data('page') - 1).draw('page'); });
            }
        });
        $('#grnSearchData').off('keyup.garmentGrn').on('keyup.garmentGrn', function () { table.search(this.value).draw(); });
        $(document).on('input', '#grn-received-quantity, #grn-rejected-quantity', updateAcceptedQuantity);
        $(document).on('shown.bs.modal', '#add-grn-modal, #edit-grn-modal', updateAcceptedQuantity);
    });
})(jQuery);
