(function ($) {
    "use strict";
    $(function () {
        $('#purchaseOrderDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true, responsive: true, dom: 't',
            ajax: { url: $('#purchase-order-data-route').val() },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'po_number', name: 'po_number' }, { data: 'supplier_name', name: 'supplier_name' },
                { data: 'order_date', name: 'order_date' }, { data: 'expected_date', name: 'expected_date' },
                { data: 'total_amount', name: 'total_amount' }, { data: 'status_label', name: 'status_label', orderable: false },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ]
        });
    });
})(jQuery);
