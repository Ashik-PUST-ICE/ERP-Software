(function ($) {
    "use strict";

    $(document).ready(function () {
        if ($.fn.DataTable.isDataTable('#scheduledPostTable')) {
            $('#scheduledPostTable').DataTable().destroy();
        }

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
                if (currentPage === p) html += '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + "</a></span>";
                else html += '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + "</a>";
            }
            if (end < totalPages) {
                if (end < totalPages - 1) html += '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
                html += '<a class="paginate_button ajax-page" data-page="' + totalPages + '" role="link">' + totalPages + "</a>";
            }
            html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") +
                '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
            return html;
        }

        var scheduledPostTable = $('#scheduledPostTable').DataTable({
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
                url: $('#scheduledPostTable').data('url'),
                data: function (d) {
                    d.search = $('#searchData').val();
                    d.status = $('#statusFilter').val();
                    d.platform = $('#platformFilter').val();
                }
            },
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'></i>",
                    next: "<i class='fa fa-chevron-right'></i>"
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
                { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false, responsivePriority: 1 },
                { "data": "platform", "name": "socialMediaAccount.platform", responsivePriority: 2 },
                { "data": "account_name", "name": "socialMediaAccount.username", responsivePriority: 3 },
                { "data": "user", "name": "user.name", responsivePriority: 4 },
                { "data": "scheduled_time", "name": "scheduled_time" },
                { "data": "created_at", "name": "created_at" },
                { "data": "post_type", "name": "post_type" },
                { "data": "status", "name": "status", searchable: false },
                { "data": "action", "name": "action", orderable: false, searchable: false, responsivePriority: 5 },
            ],
            drawCallback: function () {
                var info = scheduledPostTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#scheduled-posts-pagination-wrap");
                if (!$wrap.length) {
                    $('#scheduledPostTable').after('<div id="scheduled-posts-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#scheduled-posts-pagination-wrap");
                }
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) scheduledPostTable.page(page - 1).draw("page");
                });
            }
        });

        $('#searchData').off('keyup.adminAllPosts').on('keyup.adminAllPosts', function () {
            scheduledPostTable.search($(this).val()).draw();
        });

        $('#statusFilter, #platformFilter').on('change', function () {
            scheduledPostTable.draw();
        });

        $(document).on('click', '.cancel-action', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('route');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to cancel this post?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Cancelled',
                                    html: '<span style="color:green">' + response.message + '</span>',
                                    timer: 2000,
                                    icon: 'success'
                                });
                                toastr.success(response.message);
                                scheduledPostTable.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong!',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.retry-action', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('route');

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to retry this post?",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, retry it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Success',
                                    html: '<span style="color:green">' + response.message + '</span>',
                                    timer: 2000,
                                    icon: 'success'
                                });
                                toastr.success(response.message);
                                scheduledPostTable.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong!',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.delete-item', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('route');

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
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.status) {
                                Swal.fire({
                                    title: 'Deleted',
                                    html: '<span style="color:green">Post has been deleted</span>',
                                    timer: 2000,
                                    icon: 'success'
                                });
                                toastr.success(response.message);
                                scheduledPostTable.ajax.reload();
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: response.message,
                                    icon: 'error'
                                });
                            }
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Something went wrong!',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

    });
})(jQuery);
