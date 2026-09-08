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

    function updateCostingSummary() {
        var total = 0;
        $('.costing-component').each(function () { total += parseFloat($(this).val()) || 0; });
        var fob = parseFloat($('#costing-fob-input').val()) || 0;
        var profit = fob - total;
        var margin = fob > 0 ? (profit / fob) * 100 : 0;
        $('#costing-total-display').text(total.toFixed(4));
        $('#costing-fob-display').text(fob.toFixed(4));
        $('#costing-profit-display').text(profit.toFixed(4));
        $('#costing-margin-display').text(margin.toFixed(2) + '%');
    }

    $(document).ready(function () {
        var table = $('#garmentCostingDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true, responsive: true, searching: true, paging: true, info: false, stateSave: false, dom: 't',
            ajax: { url: $('#garment-costing-data-route').val(), type: 'GET' },
            columnDefs: [{ targets: 'keep-show', className: 'all' }],
            columns: [
                { data: 'sl', name: 'sl', searchable: false, orderable: false }, { data: 'order_number', name: 'order.order_number' }, { data: 'style_name', name: 'order.style.style_code' }, { data: 'buyer_name', name: 'order.buyer.company_name' }, { data: 'total_cost_display', name: 'total_cost', searchable: false }, { data: 'fob_price_display', name: 'fob_price', searchable: false }, { data: 'status', name: 'status', searchable: false, orderable: false }, { data: 'action', name: 'action', searchable: false, orderable: false }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var $wrap = $('#garment-costing-pagination-wrap');
                if (!$wrap.length) { $('#garmentCostingDataTable').after('<div id="garment-costing-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>'); $wrap = $('#garment-costing-pagination-wrap'); }
                $wrap.html(renderGlobalPagination(info.pages, info.page + 1));
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (event) { event.preventDefault(); table.page($(this).data('page') - 1).draw('page'); });
            }
        });
        $('#costingSearchData').off('keyup.garmentCosting').on('keyup.garmentCosting', function () { table.search(this.value).draw(); });
        $(document).on('input', '.costing-component, #costing-fob-input', updateCostingSummary);
        $(document).on('shown.bs.modal', '#add-costing-modal, #edit-costing-modal', updateCostingSummary);
    });
})(jQuery);
