(function ($) {
    "use strict";

    $(document).ready(function () {
        // Initialize DataTable with server-side processing
        if ($.fn.DataTable.isDataTable('#categoryTable')) {
            $('#categoryTable').DataTable().destroy();
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
            html += '<a class="paginate_button next ' + (currentPage === totalPages ? "disabled" : "ajax-page") +
                '" data-page="' + (currentPage < totalPages ? currentPage + 1 : "") + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
            return html;
        }

        var categoryTable = $('#categoryTable').DataTable({
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
                url: $('#category-route').val(),
                data: function (d) {
                    d.search = $('#searchData').val();
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
                { "data": "title", "name": "title", responsivePriority: 1 },
                { "data": "type", "name": "type" },
                { "data": "status", searchable: false, responsivePriority: 2 },
                { "data": "action", searchable: false, responsivePriority: 3 },
            ],
            drawCallback: function () {
                var info = categoryTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#category-pagination-wrap");
                if (!$wrap.length) {
                    $('#categoryTable').after('<div id="category-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#category-pagination-wrap");
                }
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) categoryTable.page(page - 1).draw("page");
                });
            }
        });

        // Search functionality
        $('#searchData').off('keyup.adminCategory').on('keyup.adminCategory', function () {
            categoryTable.search($(this).val()).draw();
        });

        // Delete category - handle click on dropdown delete item
        $(document).on('click', '.delete-item', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var route = $(this).data('route');

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
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted',
                                    html: '<span style="color:green">Category has been deleted</span>',
                                    timer: 2000,
                                    icon: 'success'
                                });
                                toastr.success(response.message);
                                categoryTable.ajax.reload();
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
