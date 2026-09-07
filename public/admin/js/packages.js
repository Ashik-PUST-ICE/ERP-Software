(function ($) {
    "use strict";

    // Regular packages datatable
    $("#packagesDataTable").DataTable({
        ordering: false,
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search Here",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"tableSearch float-start"f>><"col-sm-6"<"tableLengthInput float-end"l>>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
    });

    // User packages datatable
    $('#commonDataTable').DataTable({
        pageLength: 25,
        ordering: false,
        serverSide: true,
        processing: true,
        searching: true,
        responsive: true,
        ajax: $('#packagesUserRoute').val(),
        language: {
            paginate: {
                previous: "<i class='fa-solid fa-angles-left'></i>",
                next: "<i class='fa-solid fa-angles-right'></i>",
            },
            searchPlaceholder: "Search Here",
            search: "<span class='searchIcon'><i class='fa-solid fa-magnifying-glass'></i></span>",
        },
        dom: '<"tableTop"<"row align-items-center"<"col-sm-6"<"tableSearch float-start"f>><"col-sm-6"<"tableLengthInput float-end"l>>>>tr<"tableBottom"<"row align-items-center"<"col-sm-6"<"tableInfo"i>><"col-sm-6"<"tablePagi"p>>>><"clear">',
        columns: [
            { "data": 'DT_RowIndex', "name": 'DT_RowIndex', searchable: false },
            { "data": "user_name", "name": "user_name" },
            { "data": "email", "name": "email" },
            { "data": "package_name", "name": "package_name" },
            { "data": "gateway_name", "name": "gateway_name" },
            { "data": "start_date", "name": "start_date" },
            { "data": "end_date", "name": "end_date" },
            { "data": "status", "name": "status", orderable: false },
            { "data": "action", searchable: false, orderable: false }
        ]
    });

    // Handle package filtering tabs
    $(document).on('click', '.packageId', function () {
        $('.packageId').removeClass('bg-primary').addClass('bg-white');
        $(this).removeClass('bg-white').addClass('bg-primary');

        var packageId = $(this).val();
        var table = $('#commonDataTable').DataTable();

        if (packageId && packageId !== 'All') {
            table.ajax.url($("#packagesUserRoute").val() + '?packageable_id=' + packageId).load();
        } else {
            table.ajax.url($("#packagesUserRoute").val()).load();
        }
    });

    // Handle revoke package
    $(document).on('click', '.revoke-package', function (e) {
        e.preventDefault();
        var url = $(this).data('url');

        Swal.fire({
            title: 'Sure! You want to revoke this package?',
            text: "This will cancel the user's package subscription",
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
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        Swal.fire({
                            title: 'Revoked',
                            html: ' <span style="color:red">Package has been revoked</span> ',
                            timer: 2000,
                            icon: 'success'
                        });
                        toastr.success(data.message);
                        $('#commonDataTable').DataTable().ajax.reload();
                    },
                    error: function (error) {
                        toastr.error(error.responseJSON.message);
                    }
                });
            }
        });
    });

})(jQuery)
