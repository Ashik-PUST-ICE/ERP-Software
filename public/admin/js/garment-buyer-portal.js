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

    function updateBuyerDisplay(response) {
        var buyer = response && response.buyer ? response.buyer : null;

        if (!buyer) {
            $('#buyerPortalName').text('--');
            $('#buyerPortalCode').text('N/A');
            $('#buyerPortalContact').text('--');
            $('#buyerPortalEmail').text('--').removeAttr('href');
            $('#buyerPortalPhone').text('--');
            $('#buyerPortalCountryCurrency').text('--');
            $('#buyerPortalPaymentTerms').text('--');
            $('#buyerPortalAddress').text('--');

            $('#buyerPortalTotalOrders').text('0');
            $('#buyerPortalTotalQuantity').text('0');
            $('#buyerPortalInProduction').text('0');
            $('#buyerPortalCompleted').text('0');
            return;
        }

        $('#buyerPortalName').text(buyer.company_name || '--');
        $('#buyerPortalCode').text(buyer.buyer_code || 'N/A');
        $('#buyerPortalContact').text(buyer.contact_person || '--');

        if (buyer.email) {
            $('#buyerPortalEmail').text(buyer.email).attr('href', 'mailto:' + buyer.email);
        } else {
            $('#buyerPortalEmail').text('--').removeAttr('href');
        }

        $('#buyerPortalPhone').text(buyer.phone || '--');
        var countryCurr = (buyer.country || '--') + ' (' + (buyer.currency || 'USD') + ')';
        $('#buyerPortalCountryCurrency').text(countryCurr);
        $('#buyerPortalPaymentTerms').text(buyer.payment_terms || '--');
        $('#buyerPortalAddress').text(buyer.office_address || '--');

        $('#buyerPortalTotalOrders').text(Number(response.total_orders || 0).toLocaleString());
        $('#buyerPortalTotalQuantity').text(Number(response.total_quantity || 0).toLocaleString());
        $('#buyerPortalInProduction').text(Number(response.in_production_orders || 0).toLocaleString());
        $('#buyerPortalCompleted').text(Number(response.completed_orders || 0).toLocaleString());
    }

    function loadBuyerSummary() {
        var route = $('#buyer-data-route').val();
        var buyerId = $('#buyerPortalBuyerSelect').val();

        if (!buyerId) {
            var firstOption = $('#buyerPortalBuyerSelect option').first();
            if (firstOption.length) {
                buyerId = firstOption.val();
                $('#buyerPortalBuyerSelect').val(buyerId);
            }
        }

        if (!buyerId || !route) return;

        $.ajax({
            url: route,
            type: 'GET',
            data: { section: 'summary', buyer_id: buyerId },
            dataType: 'json',
            cache: false,
            success: function (response) {
                updateBuyerDisplay(response);
            },
            error: function () {
                // If error, keep server-rendered defaults
            }
        });
    }

    $(document).ready(function () {
        var route = $('#buyer-data-route').val();
        if (!route) return;

        // Initialize DataTable
        var table = $('#buyerPortalOrdersTable').DataTable({
            pageLength: 10,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            ajax: {
                url: route,
                type: 'GET',
                data: function (d) {
                    d.buyer_id = $('#buyerPortalBuyerSelect').val();
                },
                error: function (xhr, error, thrown) {
                    console.error('Buyer portal datatable error', xhr.status, thrown);
                }
            },
            language: {
                processing: '<div class="text-center py-4 text-primary"><i class="fa fa-spinner fa-spin fa-2x"></i></div>',
                emptyTable: '<div class="text-center py-4 text-muted"><i class="fa-regular fa-folder-open fa-2x mb-2 d-block"></i>No orders found for this buyer</div>'
            },
            dom: 't',
            columnDefs: [
                {
                    targets: 'keep-show',
                    className: 'all'
                }
            ],
            columns: [
                { data: 'order_number_html', name: 'order_number', responsivePriority: 1 },
                { data: 'style', name: 'style', orderable: false },
                { data: 'quantity', name: 'quantity', orderable: false },
                { data: 'order_date', name: 'order_date' },
                { data: 'delivery_date', name: 'delivery_date' },
                { data: 'status', name: 'status', orderable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-end' }
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;

                var $wrap = $("#buyer-portal-pagination-wrap");
                if (!$wrap.length) {
                    $('#buyerPortalOrdersTable').after('<div id="buyer-portal-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#buyer-portal-pagination-wrap");
                }

                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) table.page(page - 1).draw("page");
                });
            }
        });

        // Buyer Selection Change Listener
        $('#buyerPortalBuyerSelect').on('change', function () {
            var buyerId = $(this).val();
            if (buyerId) {
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: { section: 'summary', buyer_id: buyerId },
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        updateBuyerDisplay(response);
                    },
                    error: function () {
                        updateBuyerDisplay(null);
                    }
                });
            } else {
                updateBuyerDisplay(null);
            }
            table.ajax.reload();
        });
    });

})(jQuery);
