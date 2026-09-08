(function ($) {
    "use strict";
    $(function () {
        $('#supplierDataTable').DataTable({
            pageLength: 10, ordering: false, serverSide: true, processing: true,
            responsive: true, dom: 't', ajax: { url: $('#supplier-data-route').val() },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'supplier_code', name: 'supplier_code' },
                { data: 'company_name', name: 'company_name' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'category', name: 'category' },
                { data: 'status_label', name: 'status_label', searchable: false, orderable: false },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ]
        });
    });
})(jQuery);
