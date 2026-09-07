(function ($) {
    "use strict";

    let currentStatus = 'All';
    let orderDataTable;

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';
        html += '<a class="paginate_button previous ' + (currentPage === 1 ? "disabled" : "ajax-page") + '" data-page="' + (currentPage > 1 ? currentPage - 1 : "") + '" role="link"><i class="fa-solid fa-angles-left"></i></a>';
        if (start > 1) {
            html += '<a class="paginate_button ajax-page" data-page="1" role="link">1</a>';
            if (start > 2) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
        }
        for (var p = start; p <= end; p++) {
            if (currentPage === p) html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + "</a></span>";
            else html += '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + "</a>";
        }
        if (end < totalPages) {
            if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
            html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + "</a>";
        }
        html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
        return html;
    }

    function getInfoRes(response) {
        // Handle error case (response might be jqXHR object when AJAX fails)
        if (!response || typeof response.status === 'undefined') {
            console.error('Invalid response:', response);
            return;
        }
        if (!response.status) {
            toastr.error(response.message);
            return;
        }
        const selector = $('#payStatusChangeModal');
        selector.find('input[name=id]').val(response.data.id);
        selector.find('select[name=payment_status]').val(response.data.payment_status);
        selector.modal('show');
    }

    function initializeDataTable(status) {
        currentStatus = status;

        orderDataTable = $("#orderDataTable").DataTable({
            pageLength: 8,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            ajax: {
                url: $("#ordersDataRoute").val(),
                data: function (data) {
                    data.status = currentStatus;
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'></i>",
                    next: "<i class='fa fa-chevron-right'></i>"
                }
            },
            dom: 't',
            columnDefs: [{
                targets: 'keep-show',
                className: 'all'
            }],
            drawCallback: function () {
                var info = orderDataTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#orders-pagination-wrap");
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) orderDataTable.page(page - 1).draw("page");
                });
            },
            columns: [
                {
                    data: "sl",
                    name: "sl",
                    responsivePriority: 1,
                    searchable: false,
                    orderable: false
                },
                {
                    data: "date",
                    name: "created_at"
                },
                {
                    data: "tnxId",
                    name: "payments.tnxId",
                    orderable: false
                },
                {
                    data: "userName",
                    name: "users.name"
                },
                {
                    data: "userEmail",
                    name: "users.email"
                },
                {
                    data: "package",
                    name: "paymentable.name",
                    orderable: false
                },
                {
                    data: "gateway",
                    orderable: false
                },
                {
                    data: "amount",
                    orderable: false
                },

                {
                    data: "status",
                    orderable: false
                },
                {
                    data: "action",
                    responsivePriority: 3,
                    searchable: false,
                    orderable: false
                }
            ],
            bDestroy: true
        });

        $('#searchData').off('keyup.orders').on('keyup.orders', function () {
            if (orderDataTable) {
                orderDataTable.search(this.value).draw();
            }
        });
    }

    $(document).ready(function () {
        initializeDataTable('All');

        $(document).on('click', '.orderPayStatus', function () {
            commonAjax(
                'GET',
                window.superAdminOrderRoutes && window.superAdminOrderRoutes.info
                    ? window.superAdminOrderRoutes.info
                    : $("#orderInfoRoute").val(),
                getInfoRes,
                getInfoRes,
                { id: $(this).data('id') }
            );
        });

        $(document).on('click', '.orderStatusTab', function () {
            $('.orderStatusTab').removeClass('active');
            $(this).addClass('active');

            const status = $(this).data('status');
            currentStatus = status;

            if (orderDataTable) {
                orderDataTable.ajax.reload();
            }
        });
    });

    window.superAdminOrderTable = {
        reload: function () {
            if (orderDataTable) {
                orderDataTable.ajax.reload();
            }
        }
    };

})(jQuery);

