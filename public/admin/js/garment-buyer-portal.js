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

    function loadBuyerSummary() {
        var buyerId = $('#buyerPortalBuyerSelect').val();
        if (!buyerId) {
            var firstOption = $('#buyerPortalBuyerSelect option').first();
            if (firstOption.length) {
                buyerId = firstOption.val();
                $('#buyerPortalBuyerSelect').val(buyerId);
            }
        }

        if (!buyerId) {
            $('#buyerPortalName').text('--');
            $('#buyerPortalEmail').text('');
            $('#buyerPortalTotalOrders').text('--');
            $('#buyerPortalTotalQuantity').text('--');
            return;
        }

        $.ajax({
            url: $('#buyer-data-route').val(),
            type: 'GET',
            data: { section: 'summary', buyer_id: buyerId },
            dataType: 'json',
            cache: false,
            success: function (response) {
                $('#buyerPortalName').text(response.buyer?.company_name || '--');
                $('#buyerPortalEmail').text(response.buyer?.email || '');
                $('#buyerPortalTotalOrders').text(Number(response.total_orders || 0).toLocaleString());
                $('#buyerPortalTotalQuantity').text(Number(response.total_quantity || 0).toLocaleString());
            },
            error: function () {
                $('#buyerPortalName').text('--');
                $('#buyerPortalEmail').text('');
                $('#buyerPortalTotalOrders').text('--');
                $('#buyerPortalTotalQuantity').text('--');
            }
        });
    }

    $(document).ready(function () {
        var route = $('#buyer-data-route').val();
        console.log('buyer portal init', route);
        if (!route) return;

        loadBuyerSummary();

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
                    console.error('buyer portal ajax error', xhr.status, thrown);
                }
            },
            dom: 't',
            columnDefs: [
                {
                    targets: 'keep-show',
                    className: 'all'
                }
            ],
            columns: [
                { data: 'order_number', name: 'order_number', responsivePriority: 1 },
                { data: 'description', name: 'description', searchable: false },
                { data: 'quantity', name: 'quantity', searchable: false, orderable: false },
                { data: 'delivery_date', name: 'delivery_date', searchable: false, orderable: false },
                { data: 'status', name: 'status', searchable: false, orderable: false }
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

        $('#buyerPortalBuyerSelect').change(function () {
            var buyerId = $(this).val();
            if (buyerId) {
                $.ajax({
                    url: route,
                    type: 'GET',
                    data: { section: 'summary', buyer_id: buyerId },
                    dataType: 'json',
                    cache: false,
                    success: function (response) {
                        $('#buyerPortalName').text(response.buyer?.company_name || '--');
                        $('#buyerPortalEmail').text(response.buyer?.email || '');
                        $('#buyerPortalTotalOrders').text(Number(response.total_orders || 0).toLocaleString());
                        $('#buyerPortalTotalQuantity').text(Number(response.total_quantity || 0).toLocaleString());
                    },
                    error: function () {
                        $('#buyerPortalName').text('--');
                        $('#buyerPortalEmail').text('');
                        $('#buyerPortalTotalOrders').text('--');
                        $('#buyerPortalTotalQuantity').text('--');
                    }
                });
            } else {
                $('#buyerPortalName').text('--');
                $('#buyerPortalEmail').text('');
                $('#buyerPortalTotalOrders').text('--');
                $('#buyerPortalTotalQuantity').text('--');
            }
            table.ajax.reload();
        });
    });

})(jQuery);
