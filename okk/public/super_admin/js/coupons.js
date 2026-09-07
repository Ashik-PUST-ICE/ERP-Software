(function ($) {
    "use strict";

    // Define routes - these can be overridden by data attributes on the form
    window.superAdminCouponRoutes = window.superAdminCouponRoutes || {
        store: '/super-admin/coupons',
        update: '/super-admin/coupons/:id'
    };

    let couponId = null;
    let couponsTable;

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

    $(document).ready(function () {
        couponsTable = $('#couponsDataTable').DataTable({
            pageLength: 8,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: true,
            paging: true,
            info: false,
            stateSave: false,
            ajax: $('#coupons-data-route').val(),
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
                var info = couponsTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#coupons-pagination-wrap");
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) couponsTable.page(page - 1).draw("page");
                });
            },
            columns: [
                { data: "sl", name: "sl", responsivePriority: 1 },
                { data: "name", name: "name" },
                { data: "code", name: "code" },
                { data: "type", name: "type", searchable: false },
                { data: "amount", name: "amount" },
                { data: "start_date", name: "start_date" },
                { data: "end_date", name: "end_date" },
                { data: "usage_limit", name: "usage_limit" },
                { data: "used", name: "used" },
                { data: "status", name: "status", searchable: false, responsivePriority: 2 },
                { data: "action", name: "action", searchable: false, responsivePriority: 3 }
            ]
        });

        $('#searchData').off('keyup.coupons').on('keyup.coupons', function () {
            couponsTable.search(this.value).draw();
        });

        $('#coupon-form').on('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = $(this).attr('action');
            const method = $('#form-method').val();

            if (method === 'PATCH') {
                formData.append('_method', 'PATCH');
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        const couponModal = bootstrap.Modal.getInstance($('#coupon-modal')[0]);
                        if (couponModal) {
                            couponModal.hide();
                        }
                        if (couponsTable) {
                            couponsTable.ajax.reload(null, false);
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
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
    });

    function openCouponModal(id = null, couponData = null) {
        couponId = id;
        const $form = $('#coupon-form');
        const $modalTitle = $('#couponModalLabel');
        const $submitBtn = $('#submit-btn');

        if (!$form.length) {
            return;
        }

        $form[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        if (couponData && typeof couponData === 'string') {
            try {
                couponData = JSON.parse(couponData);
            } catch (e) {
                console.error("Error parsing coupon data:", e);
                couponData = null;
            }
        }

        if (id && couponData) {
            $modalTitle.text("Edit Coupon");
            $submitBtn.text("Update Coupon");
            // Use data attribute if available, otherwise fall back to window object
            const updateRoute = $form.data('update');
            if (updateRoute) {
                $form.attr('action', updateRoute.replace(':id', id));
            } else if (window.superAdminCouponRoutes && window.superAdminCouponRoutes.update) {
                $form.attr('action', window.superAdminCouponRoutes.update.replace(':id', id));
            }
            $('#form-method').val('PATCH');

            $('#name').val(couponData.name || '');
            $('#code').val(couponData.code || '');
            $('#discount_type').val(couponData.discount_type || 'fixed');
            $('#amount').val(couponData.amount || '');
            $('#start_date').val(couponData.start_date || '');
            $('#end_date').val(couponData.end_date || '');
            $('#minimum_spend').val(couponData.minimum_spend || '');
            $('#usage_limit_per_coupon').val(couponData.usage_limit_per_coupon || '');
            $('#usage_limit_per_customer').val(couponData.usage_limit_per_customer || '');
            $('#status').val(couponData.status ? '1' : '0');
        } else {
            $modalTitle.text("Add New Coupon");
            $submitBtn.text("Create Coupon");
            // Use data attribute if available, otherwise fall back to window object
            const storeRoute = $form.data('store');
            if (storeRoute) {
                $form.attr('action', storeRoute);
            } else if (window.superAdminCouponRoutes && window.superAdminCouponRoutes.store) {
                $form.attr('action', window.superAdminCouponRoutes.store);
            }
            $('#form-method').val('POST');
        }

        const couponModal = new bootstrap.Modal($('#coupon-modal')[0]);
        couponModal.show();
    }

    window.openCouponModal = openCouponModal;

    window.deleteItem = function (url, id) {
        Swal.fire({
            title: 'Sure! You want to delete?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete It!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        _method: 'DELETE'
                    },
                    success: function (data) {
                        Swal.fire({
                            title: 'Deleted',
                            html: '<span style="color:red">Item has been deleted</span>',
                            timer: 2000,
                            icon: 'success'
                        });
                        toastr.success(data.message);
                        if (typeof id !== 'undefined') {
                            $('#' + id).DataTable().ajax.reload();
                        } else if (couponsTable) {
                            couponsTable.ajax.reload(null, false);
                        } else {
                            location.reload();
                        }
                    },
                    error: function (error) {
                        const message = (error.responseJSON && error.responseJSON.message) || 'An error occurred';
                        toastr.error(message);
                    }
                });
            }
        });
    };

})(jQuery);

