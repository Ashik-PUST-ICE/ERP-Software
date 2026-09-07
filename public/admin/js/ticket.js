(function ($) {
    "use strict";

    var currentStatus = 'all';
    var ticketDataTable;

    function renderTicketPagination(totalPages, currentPage) {
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
            '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
        return html;
    }

    $(document).ready(function () {
        if ($('#ticketDataTable').length && $('#ticketListRoute').length) {
            initializeTicketDataTable('all');
        }
    });

    $(document).on('click', '.ticketStatusTab', function (e) {
        $('.ticketStatusTab').removeClass('active');
        $(this).addClass('active');

        var status = $(this).data('status');
        currentStatus = status;

        if (ticketDataTable) {
            ticketDataTable.ajax.reload();
        }
    });

    function initializeTicketDataTable(status) {
        currentStatus = status;
        var route = $('#ticketListRoute').val();
        if (!route) return;

        ticketDataTable = $("#ticketDataTable").DataTable({
            pageLength: 8,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            ajax: {
                url: route,
                data: function (data) {
                    data.status = currentStatus;
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'></i>",
                    next: "<i class='fa fa-chevron-right'></i>",
                }
            },
            dom: 't',
            columnDefs: [{
                targets: 'keep-show',
                className: 'all'
            }],
            columns: [
                { "data": "ticket_id", responsivePriority: 1, searchable: false, orderable: false },
                { "data": "order_id", orderable: false },
                { "data": "priority", searchable: false, orderable: false },
                { "data": "status", orderable: false },
                { "data": "action", responsivePriority: 2, searchable: false, orderable: false }
            ],
            stateSave: false,
            bDestroy: true,
            drawCallback: function () {
                var info = ticketDataTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#ticketPaginationWrap");
                if (!$wrap.length) {
                    $('#ticketDataTable').after('<div id="ticketPaginationWrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#ticketPaginationWrap");
                }
                $wrap.html(renderTicketPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) ticketDataTable.page(page - 1).draw("page");
                });
            }
        });

        $('#searchData').off('keyup.adminTicket').on('keyup.adminTicket', function () {
            ticketDataTable.search(this.value).draw();
        });
    }

    $(document).on('click', '.delete-item, .ticket-delete-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var route = $(this).data('route');
        var redirectUrl = $(this).data('redirect');
        if (!route) return;
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.status === true || response.success) {
                            Swal.fire({
                                title: 'Deleted',
                                html: '<span style="color:green">Ticket has been deleted</span>',
                                timer: 2000,
                                icon: 'success'
                            });
                            toastr.success(response.message || 'Deleted successfully');
                            if (redirectUrl) {
                                window.location.href = redirectUrl;
                            } else if (typeof ticketDataTable !== 'undefined' && ticketDataTable) {
                                ticketDataTable.ajax.reload();
                            }
                        } else {
                            toastr.error(response.message || 'Something went wrong');
                        }
                    },
                    error: function (xhr) {
                        toastr.error(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong');
                    }
                });
            }
        });
    });

})(jQuery);
