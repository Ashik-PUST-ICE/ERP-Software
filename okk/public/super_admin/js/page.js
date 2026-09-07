(function ($) {
    "use strict";

    function renderGlobalPagination(totalPages, currentPage) {
        if (totalPages <= 1) return "";
        var start = Math.max(1, currentPage - 2);
        var end = Math.min(totalPages, currentPage + 2);
        var html = '<div class="dataTables_paginate paging_simple_numbers">';

        html +=
            '<a class="paginate_button previous ' +
            (currentPage === 1 ? "disabled" : "ajax-page") +
            '" data-page="' +
            (currentPage > 1 ? currentPage - 1 : "") +
            '" role="link">';
        html += '<i class="fa-solid fa-angles-left"></i></a>';

        if (start > 1) {
            html += '<a class="paginate_button ajax-page" data-page="1" role="link">1</a>';
            if (start > 2)
                html +=
                    '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
        }

        for (var p = start; p <= end; p++) {
            if (currentPage === p) {
                html +=
                    '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' +
                    p +
                    '">' +
                    p +
                    "</a></span>";
            } else {
                html +=
                    '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + "</a>";
            }
        }

        if (end < totalPages) {
            if (end < totalPages - 1)
                html +=
                    '<span><a class="paginate_button disabled" role="link" aria-disabled="true">...</a></span>';
            html +=
                '<a class="paginate_button ajax-page" data-page="' +
                totalPages +
                '" role="link">' +
                totalPages +
                "</a>";
        }

        html +=
            '<a class="paginate_button next ' +
            (currentPage === totalPages ? "disabled" : "ajax-page") +
            '" data-page="' +
            (currentPage < totalPages ? currentPage + 1 : "") +
            '" role="link">';
        html += '<i class="fa-solid fa-angles-right"></i></a>';
        html += "</div>";
        return html;
    }

    $(document).ready(function () {
        var table = $("#pageDataTable").DataTable({
            pageLength: 8,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            dom: "t",
            ajax: {
                url: $("#page-data-route").val(),
            },
            language: {
                paginate: {
                    previous: "<i class='fa fa-chevron-left'></i>",
                    next: "<i class='fa fa-chevron-right'></i>",
                },
            },
            columnDefs: [{ targets: "keep-show", className: "all" }],
            columns: [
                { data: "sl", name: "sl", searchable: false, orderable: false },
                { data: "en_title", name: "en_title" },
                { data: "slug", name: "slug" },
                { data: "action", name: "action", searchable: false, orderable: false },
            ],
            drawCallback: function () {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#page-pagination-wrap");
                if ($wrap.length) {
                    $wrap.html(renderGlobalPagination(totalPages, currentPage));
                    $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                        e.preventDefault();
                        var page = $(this).data("page");
                        if (page) table.page(page - 1).draw("page");
                    });
                }
            },
        });

        $("#searchPages").on("keyup", function () {
            table.search(this.value).draw();
        });

        // Delete button click handler
        $(document).on('click', '.delete-item', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var route = $(this).data('url');
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
                                    html: '<span style="color:green">Page has been deleted</span>',
                                    timer: 2000,
                                    icon: 'success'
                                });
                                toastr.success(response.message || 'Deleted successfully');
                                if (redirectUrl) {
                                    window.location.href = redirectUrl;
                                } else if (typeof table !== 'undefined' && table) {
                                    table.ajax.reload();
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
    });
})(jQuery);
