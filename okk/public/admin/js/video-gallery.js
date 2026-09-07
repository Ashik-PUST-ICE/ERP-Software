(function ($) {
    "use strict";

    $(document).ready(function () {
        // Client-side search by title/file name (no page reload)
        $('#searchData').off('keyup.videoGallery').on('keyup.videoGallery', function (e) {
            var value = $(this).val().toLowerCase();
            $('.single-card-image').closest('.col-xl-4, .col-lg-6, .col-md-6, .col-sm-6').filter(function () {
                var text = $(this).text().toLowerCase();
                $(this).toggle(text.indexOf(value) > -1);
            });
        });

        // Delete video item
        $(document).on('click', '.delete-item', function (e) {
            e.preventDefault();
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
                if (result.value) {
                    $.ajax({
                        type: 'DELETE',
                        url: route,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        success: function (data) {
                            Swal.fire({
                                title: 'Deleted',
                                html: '<span style="color:green">Video has been deleted</span>',
                                timer: 2000,
                                icon: 'success'
                            });
                            toastr.success(data.message);
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        },
                        error: function (error) {
                            toastr.error(error.responseJSON.message);
                        }
                    });
                }
            });
        });
    });
})(jQuery);
