(function ($) {
    "use strict";

    var currentStatus = 'all';
    var ticketDataTable;

    function renderTicketPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';

        html += '<a class="paginate_button previous ' + (currentPage === 1 ? "disabled" : "ajax-page") + '" data-page="' + (currentPage > 1 ? currentPage - 1 : "") + '" role="link">';
        html += '<i class="fa-solid fa-angles-left"></i></a>';

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
            if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
            html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + "</a>";
        }

        html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link">';
        html += '<i class="fa-solid fa-angles-right"></i></a>';
        html += "</div>";
        return html;
    }

    window.updateClientId = function () {
        var select = document.getElementById('selectPackage');
        var clientIdInput = document.getElementById('clientId');
        if (select && clientIdInput) {
            var selectedOption = select.options[select.selectedIndex];
            var userId = selectedOption ? selectedOption.getAttribute('data-user-id') : '';
            clientIdInput.value = userId || '';
        }
    };

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
            pageLength: 6,
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
            drawCallback: function () {
                var info = ticketDataTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#ticketPaginationWrap");
                $wrap.html(renderTicketPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) ticketDataTable.page(page - 1).draw("page");
                });
            },
            columns: [
                { "data": "ticket_id", responsivePriority: 1, searchable: false, orderable: false },
                { "data": "client_name", orderable: false },
                { "data": "order_id", orderable: false },
                { "data": "priority", searchable: false, orderable: false },
                { "data": "status", orderable: false },
                { "data": "action", responsivePriority: 2, searchable: false, orderable: false }
            ],
            stateSave: false,
            bDestroy: true,
            length: 8
        });

        $('#searchData').off('keyup.ticket').on('keyup.ticket', function () {
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

    $(document).on('click', '.assign-member', function (e) {
        var checkedStatus = $(this).prop('checked') ? 1 : 0;
        var assignRoute = $('#assignMemberRoute').val();
        if (!assignRoute) return;
        $.ajax({
            type: 'GET',
            url: assignRoute,
            data: {
                'member_id': $(this).val(),
                'checked_status': checkedStatus,
                'ticket_id': $(this).data('ticket'),
                'data_table': $(this).data('table')
            },
            dataType: 'json',
            success: function (response) {
                if (response.status === true) {
                    toastr.success(response.message);
                    if (ticketDataTable) {
                        ticketDataTable.ajax.reload();
                    }
                } else {
                    if (typeof commonHandler === 'function') commonHandler(response);
                }
            },
            error: function (xhr) {
                toastr.error(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong');
            }
        });
    });
})(jQuery);
