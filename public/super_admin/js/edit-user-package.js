(function($) {
    "use strict";

    $(document).ready(function() {
        $('#edit-user-package-form').on('submit', function(e) {
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
                        const editModal = bootstrap.Modal.getInstance($('#edit-modal')[0]);
                        if (editModal) {
                            editModal.hide();
                        }
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
    });

})(jQuery);

