(function($) {
    "use strict";

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

    $(document).ready(function() {
        const table = $('#commonDataTable').DataTable({
            pageLength: 8,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            ajax: $('#packagesUserRoute').val(),
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
            drawCallback: function() {
                var info = table.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#user-packages-pagination-wrap");
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function(e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) table.page(page - 1).draw("page");
                });
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    responsivePriority: 1
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'email',
                    name: 'users.email'
                },
                {
                    data: 'package_name',
                    name: 'package_name'
                },
                {
                    data: 'gateway_name',
                    name: 'gateway_name'
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: false,
                    responsivePriority: 2
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false,
                    responsivePriority: 3
                }
            ]
        });

        window.userPackagesTable = table;

        $('#searchData').off('keyup.userpack').on('keyup.userpack', function() {
            table.search(this.value).draw();
        });

        $(document).on('click', '.packageId', function() {
            $('.packageId').removeClass('active');
            $(this).addClass('active');

            const packageId = $(this).val();
            const baseUrl = $('#packagesUserRoute').val();

            if (packageId && packageId !== 'All') {
                table.ajax.url(baseUrl + '?packageable_id=' + packageId).load();
            } else {
                table.ajax.url(baseUrl).load();
            }
        });

        $('#assign-package-form').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = $(this).attr('action');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        const assignModal = bootstrap.Modal.getInstance($('#assignPackageModal')[0]);
                        if (assignModal) {
                            assignModal.hide();
                        }
                        $('#assign-package-form')[0].reset();
                        if (window.userPackagesTable) {
                            window.userPackagesTable.ajax.reload(null, false);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = (xhr.responseJSON && xhr.responseJSON.errors) || {};
                        let errorMsg = 'Validation errors:\n';
                        for (let field in errors) {
                            if (Array.isArray(errors[field]) && errors[field].length > 0) {
                                errorMsg += errors[field][0] + '\n';
                            }
                        }
                        alert(errorMsg);
                    } else {
                        const message = (xhr.responseJSON && xhr.responseJSON.message) || 'An error occurred';
                        alert(message);
                    }
                }
            });
        });

        $(document).on('click', '.revoke-package', function(e) {
            e.preventDefault();
            const url = $(this).data('url');

            Swal.fire({
                title: 'Sure! You want to revoke?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Revoke It!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function(data) {
                            Swal.fire({
                                title: 'Revoked',
                                html: '<span style="color:red">Package has been revoked</span>',
                                timer: 2000,
                                icon: 'success'
                            });
                            toastr.success(data.message);
                            if (window.userPackagesTable) {
                                window.userPackagesTable.ajax.reload(null, false);
                            }
                        },
                        error: function(error) {
                            const message = (error.responseJSON && error.responseJSON.message) || 'An error occurred';
                            toastr.error(message);
                        }
                    });
                }
            });
        });

        $(document).on('click', '.edit-user-package', function(e) {
            e.preventDefault();
            const url = $(this).data('url') || $(this).attr('href');

            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    $('#edit-modal .modal-body').html(response);
                    const editModal = new bootstrap.Modal($('#edit-modal')[0]);
                    editModal.show();
                },
                error: function() {
                    toastr.error('Failed to load edit form');
                }
            });
        });
    });

})(jQuery);

