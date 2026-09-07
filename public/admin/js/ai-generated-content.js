(function ($) {
    "use strict";

    $(document).ready(function () {
        // Initialize DataTable with server-side processing
        if ($.fn.DataTable.isDataTable('#aiGeneratedTable')) {
            $('#aiGeneratedTable').DataTable().destroy();
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

        var $table = $('#aiGeneratedTable');
        if (!$table.length) return;

        var aiGeneratedTable;
        try {
            aiGeneratedTable = $table.DataTable({
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
                    url: $table.data('url'),
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
                        targets: '_all',
                        defaultContent: '-'
                    }
                ],
                columns: [
                    { "data": "DT_RowIndex", "name": "DT_RowIndex", orderable: false, searchable: false, responsivePriority: 1 },
                    { "data": "prompt", "name": "prompt", orderable: false, searchable: true, responsivePriority: 2 },
                    { "data": "content_type", "name": "content_type", orderable: false, searchable: true, responsivePriority: 4 },
                    { "data": "generated_text", "name": "generated_text", orderable: false, searchable: true, responsivePriority: 3 },
                    { "data": "model", "name": "model", orderable: false, searchable: false },
                    { "data": "created_at", "name": "created_at", orderable: false, searchable: false },
                    { "data": "action", "name": "action", orderable: false, searchable: false, responsivePriority: 5 },
                ],
                drawCallback: function () {
                    var info = aiGeneratedTable.page.info();
                    var totalPages = info.pages;
                    var currentPage = info.page + 1;
                    var $wrap = $("#ai-generated-pagination-wrap");
                    if (!$wrap.length) {
                        $table.after('<div id="ai-generated-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                        $wrap = $("#ai-generated-pagination-wrap");
                    }
                    $wrap.html(renderGlobalPagination(totalPages, currentPage));
                    $wrap.off("click", ".ajax-page").on("click", ".ajax-page", function (e) {
                        e.preventDefault();
                        var page = $(this).data("page");
                        if (page) aiGeneratedTable.page(page - 1).draw("page");
                    });
                }
            });
        } catch (e) {
            console.error("DataTable initialization failed:", e);
        }

        // Search functionality
        $('#searchData').off('keyup.aiGenerated').on('keyup.aiGenerated', function () {
            aiGeneratedTable.search($(this).val()).draw();
        });
    });
})(jQuery);
