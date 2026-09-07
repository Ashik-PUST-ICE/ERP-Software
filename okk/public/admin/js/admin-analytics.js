(function ($) {
    "use strict";

    window.initSocialPostChart = function (months, total, pending, success, scheduled, failed) {
        var options = {
            series: [
                { name: 'Total Post',  data: total     },
                { name: 'Pending',     data: pending    },
                { name: 'Success',     data: success    },
                { name: 'Scheduled',   data: scheduled  },
                { name: 'Failed',      data: failed     }
            ],
            chart: {
                type: 'area',
                height: 250,
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            stroke: {
                curve: 'smooth',
                width: 1
            },
            fill: {
                type: 'gradient',
                gradient: {
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            colors: ['#FF4F02', '#f7c948', '#2ecc71', '#5dade2', '#cfcfcf'],
            markers: { size: 0 },
            dataLabels: { enabled: false },
            xaxis: {
                categories: months,
                axisBorder: { show: false },
                axisTicks:  { show: false },
                labels: {
                    style: {
                        colors: '#0D0D0D',
                        fontSize: '10px',
                        fontWeight: 500,
                        fontFamily: 'Poppins, sans-serif'
                    }
                },
                tooltip: { enabled: false }
            },
            yaxis: { show: false },
            grid: {
                show: true,
                borderColor: '#f1f1f1',
                strokeDashArray: 5,
                position: 'back',
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            tooltip: { shared: true, intersect: false },
            legend: {
                position: 'bottom',
                fontSize: '10px',
                fontFamily: 'Poppins, sans-serif',
                fontWeight: 500,
                markers: { width: 8, height: 8, radius: 50 }
            }
        };

        new ApexCharts(document.querySelector('#SocialPostChart'), options).render();
    };

    window.initSocialAccountsChart = function (labels, series, colors) {
        if (!labels.length) {
            labels  = ['No Data'];
            series  = [1];
            colors  = ['#e0e0e0'];
        }

        var options = {
            series: series,
            labels: labels,
            chart: {
                type: 'donut',
                width: '480'
            },
            colors: colors,
            plotOptions: {
                pie: {
                    donut: { size: '65%' },
                    expandOnClick: false
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function (val) { return Math.round(val) + '%'; },
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: ['#ffffff']
                },
                background: {
                    enabled: true,
                    foreColor: '#000',
                    padding: 8,
                    borderRadius: 50,
                    borderWidth: 0,
                    opacity: 1
                },
                dropShadow: { enabled: false }
            },
            legend: {
                show: true,
                position: 'left',
                fontSize: '10px',
                fontFamily: 'Poppins, sans-serif',
                fontWeight: 500,
                markers: { width: 8, height: 8, radius: 50 }
            },
            stroke: { width: 0 },
            states: {
                hover:  { filter: { type: 'none' } },
                active: { filter: { type: 'none' } }
            },
            tooltip: { enabled: true },
            responsive: [
                {
                    breakpoint: 1367,
                    options: { chart: { width: '100%' } }
                },
                {
                    breakpoint: 1024,
                    options: { chart: { width: '100%' }, legend: { position: 'bottom' } }
                },
                {
                    breakpoint: 768,
                    options: { chart: { width: '100%' }, legend: { position: 'bottom' } }
                },
                {
                    breakpoint: 480,
                    options: {
                        chart: { width: '100%' },
                        dataLabels: { style: { fontSize: '10px' } },
                        legend: { position: 'bottom', fontSize: '9px' }
                    }
                }
            ]
        };

        new ApexCharts(document.querySelector('#SocialAccountsChart'), options).render();
    };

    // Latest Post table (same as dashboard) - used on analytics index & platform pages
    window.initAnalyticsLatestPostsTable = function (platform) {
        var $table = $('#latestPostsTable');
        if (!$table.length) return;
        var url = $table.data('url');
        if (!url) return;

        if ($.fn.DataTable.isDataTable('#latestPostsTable')) {
            $table.DataTable().destroy();
        }

        var analyticsLatestPostsTable = $table.DataTable({
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
                var info = analyticsLatestPostsTable.page.info();
                var totalPages = info.pages;
                var currentPage = info.page + 1;
                var $wrap = $('#latestPostsPaginationWrap');
                if (!$wrap.length) {
                    $table.after('<div id="latestPostsPaginationWrap" class="d-flex justify-content-center mt-20 tablePagi"></div>');
                    $wrap = $('#latestPostsPaginationWrap');
                }
                var start = Math.max(1, currentPage - 2);
                var end = Math.min(totalPages, currentPage + 2);
                var html = '<div class="dataTables_paginate paging_simple_numbers">';
                html += '<a class="paginate_button previous ' + (currentPage === 1 ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage > 1 ? currentPage - 1 : '') + '" role="link"><i class="fa-solid fa-angles-left"></i></a>';
                for (var p = start; p <= end; p++) {
                    html += (currentPage === p)
                        ? '<span><a class="paginate_button current" aria-current="page" role="link" data-page="' + p + '">' + p + '</a></span>'
                        : '<a class="paginate_button ajax-page" data-page="' + p + '" role="link">' + p + '</a>';
                }
                html += '<a class="paginate_button next ' + (currentPage === totalPages ? 'disabled' : 'ajax-page') + '" data-page="' + (currentPage < totalPages ? currentPage + 1 : '') + '" role="link"><i class="fa-solid fa-angles-right"></i></a></div>';
                $wrap.html(html);
                $wrap.off('click', '.ajax-page').on('click', '.ajax-page', function (e) {
                    e.preventDefault();
                    var page = $(this).data('page');
                    if (page) analyticsLatestPostsTable.page(page - 1).draw('page');
                });
            }
        });
    };

})(jQuery);
