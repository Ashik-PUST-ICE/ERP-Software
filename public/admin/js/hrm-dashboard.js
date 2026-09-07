(function ($) {
    "use strict";

    var dashboardDataUrl = $('#dashboard-data-url').val() || '';

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

    var recentEmployeesTable = null;
    var pendingLeavesTable = null;

    function initRecentEmployeesTable() {
        var $table = $('#recentEmployeesTable');
        if (!$table.length) return;

        if ($.fn.DataTable.isDataTable('#recentEmployeesTable')) {
            $table.DataTable().destroy();
        }

        recentEmployeesTable = $table.DataTable({
            pageLength: 5,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: false,
            paging: true,
            info: false,
            ajax: {
                url: $table.data('url'),
                data: function (d) {
                }
            },
            language: {
                processing: '<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></div>',
                emptyTable: '<div class="text-center py-3 text-muted">No employees found</div>'
            },
            dom: 't',
            columnDefs: [
                { targets: 'keep-show', className: 'all' }
            ],
            columns: [
                { data: 'full_name', name: 'first_name', orderable: false, searchable: false },
                { data: 'employee_code', name: 'employee_code', orderable: false, searchable: false },
                { data: 'department', name: 'department.name', orderable: false, searchable: false },
                { data: 'designation', name: 'designation.name', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false },
            ],
            drawCallback: function () {
                var info = recentEmployeesTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#recentEmployeesPaginationWrap");
                if (!$wrap.length) {
                    $table.after('<div id="recentEmployeesPaginationWrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#recentEmployeesPaginationWrap");
                }
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) recentEmployeesTable.page(page - 1).draw("page");
                });
            }
        });
    }

    function initPendingLeavesTable() {
        var $table = $('#pendingLeavesTable');
        if (!$table.length) return;

        if ($.fn.DataTable.isDataTable('#pendingLeavesTable')) {
            $table.DataTable().destroy();
        }

        pendingLeavesTable = $table.DataTable({
            pageLength: 5,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: false,
            paging: true,
            info: false,
            ajax: {
                url: $table.data('url'),
                data: function (d) {
                    d.status = 0;
                }
            },
            language: {
                processing: '<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></div>',
                emptyTable: '<div class="text-center py-3 text-muted">No pending leaves</div>'
            },
            dom: 't',
            columnDefs: [
                { targets: 'keep-show', className: 'all' }
            ],
            columns: [
                { data: 'employee', name: 'employee', orderable: false, searchable: false },
                { data: 'leave_type', name: 'leave_type', orderable: false, searchable: false },
                { data: 'duration', name: 'duration', orderable: false, searchable: false },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    render: function (data, type, row) {
                        var match = data.match(/href="([^"]*approve[^"]*)"/);
                        if (match) {
                            return '<a href="' + match[1] + '" class="zBadge zBadge-complete">Approve</a>';
                        }
                        return data;
                    }
                },
            ],
            drawCallback: function () {
                var info = pendingLeavesTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#pendingLeavesPaginationWrap");
                if (!$wrap.length) {
                    $table.after('<div id="pendingLeavesPaginationWrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#pendingLeavesPaginationWrap");
                }
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) pendingLeavesTable.page(page - 1).draw("page");
                });
            }
        });
    }

    function loadDashboardData() {
        if (!dashboardDataUrl) return;

        $.ajax({
            url: dashboardDataUrl,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                if (response.totalEmployees !== undefined) {
                    $('#kpiTotalEmployees').text(response.totalEmployees);
                }
                if (response.todayPresent !== undefined) {
                    $('#kpiTodayPresent').text(response.todayPresent);
                }
                if (response.todayLate !== undefined) {
                    $('#kpiTodayLate').text(response.todayLate);
                }
                if (response.todayAbsent !== undefined) {
                    $('#kpiTodayAbsent').text(response.todayAbsent);
                }
                if (response.pendingLeaves !== undefined) {
                    $('#kpiPendingLeaves').text(response.pendingLeaves);
                }
                if (response.totalDepartments !== undefined) {
                    $('#kpiTotalDepartments').text(response.totalDepartments);
                }
                if (response.monthlyPayroll !== undefined) {
                    $('#kpiMonthlyPayroll').text(response.monthlyPayroll);
                }
            if (response.notCheckedIn !== undefined) {
                $('#kpiNotCheckedIn').text(response.notCheckedIn);
            }
            if (response.departmentStats && response.departmentStats.length) {
                var deptHtml = '';
                $.each(response.departmentStats, function (i, dept) {
                    deptHtml += '<div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6">';
                    deptHtml += '<div class="card-box">';
                    deptHtml += '<div class="card-info">';
                    deptHtml += '<h2>' + dept.employees_count + '</h2>';
                    deptHtml += '<h3>' + dept.name + '</h3>';
                    deptHtml += '</div>';
                    deptHtml += '<span class="card-status up">';
                    deptHtml += '<span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>';
                    deptHtml += '</span>';
                    deptHtml += '</div></div>';
                });
                $('#departmentStatsContainer').html(deptHtml);
            }
            },
            error: function () {
                console.error('Failed to load dashboard data');
            }
        });
    }

    $(document).ready(function () {
        initRecentEmployeesTable();
        initPendingLeavesTable();
        loadDashboardData();
    });

})(jQuery);
