(function ($) {
    "use strict";

    var latestPostsTable = null;

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

    function initLatestPostsTable(platform) {
        var $table = $('#latestPostsTable');
        if (!$table.length) return;

        var url = $table.data('url');

        if ($.fn.DataTable.isDataTable('#latestPostsTable')) {
            latestPostsTable.destroy();
        }

        latestPostsTable = $table.DataTable({
            pageLength: 6,
            ordering: false,
            serverSide: true,
            processing: true,
            responsive: true,
            searching: false,
            paging: true,
            info: false,
            ajax: {
                url: url,
                data: function (d) {
                    d.platform = platform || 'all';
                }
            },
            language: {

                processing: '<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i></div>',
                emptyTable: '<div class="text-center py-3 text-muted">No posts found</div>'
            },
            dom: 't',
            columnDefs: [
                { targets: 'keep-show', className: 'all' }
            ],
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, responsivePriority: 1 },
                { data: 'platform_name', name: 'platform', orderable: false, responsivePriority: 2 },
                { data: 'account_name', name: 'account_name', orderable: false, responsivePriority: 3 },
                { data: 'schedule_time', name: 'schedule_time', responsivePriority: 4 },
                { data: 'post_type', name: 'post_type', orderable: false, searchable: false, responsivePriority: 5 },
            ],
            drawCallback: function () {
                var info = latestPostsTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $("#latestPostsPaginationWrap");
                if (!$wrap.length) {
                    $table.after('<div id="latestPostsPaginationWrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $("#latestPostsPaginationWrap");
                }
                $wrap.html(renderGlobalPagination(totalPages, currentPage));
                $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                    e.preventDefault();
                    var page = $(this).data("page");
                    if (page) latestPostsTable.page(page - 1).draw("page");
                });
            }
        });
    }

    $(document).ready(function () {

        // Init with "all platforms" on page load
        initLatestPostsTable('all');

        // Re-init when platform tab changes
        $('button[data-bs-toggle="tab"]', '#nav-tab').on('shown.bs.tab', function () {
            var target = $(this).data('bs-target') || $(this).attr('data-bs-target');
            var platform = 'all';
            if (target && target !== '#nav-AllPlatforms') {
                platform = target.replace('#nav-', '').toLowerCase();
            }
            initLatestPostsTable(platform);
        });

    });

})(jQuery);
