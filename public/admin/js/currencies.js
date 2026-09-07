(function ($) {
    "use strict";

    // Table search functionality (pagination handled by common-pagination.js)
    $(document).ready(function () {
        $('#searchData').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });

    // Initialize select2 for add modal
    $("#sf-select-currency-add").select2({
        dropdownCssClass: "sf-select-dropdown",
        selectionCssClass: "sf-select-section",
        dropdownParent: $("#add-modal"),
    });

    // Initialize select2 for edit modal
    $("#sf-select-currency-edit").select2({
        dropdownCssClass: "sf-select-dropdown",
        selectionCssClass: "sf-select-section",
        dropdownParent: $("#edit-modal"),
    });

    // Open edit modal and populate with data
    window.openEditModal = function (url, currencyId) {
        $.ajax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status === true && response.data) {
                    var currency = response.data.currency;

                    // Set form action - update ID
                    var currentAction = $('#edit-currency-form').attr('action');
                    var updateUrl = currentAction.replace(/\/\d+$/, '/' + currencyId);
                    $('#edit-currency-form').attr('action', updateUrl);

                    // Populate form fields
                    $('#sf-select-currency-edit').val(currency.currency_code).trigger('change');
                    $('#symbol-edit').val(currency.symbol);
                    $('#currency_placement-edit').val(currency.currency_placement).trigger('change');

                    // Set current_currency checkbox
                    if (currency.current_currency == 1) { // Assuming 1 is STATUS_ACTIVE
                        $('#flexCheckChecked-edit').prop('checked', true);
                    } else {
                        $('#flexCheckChecked-edit').prop('checked', false);
                    }

                    // Show the modal
                    $('#edit-modal').modal('show');
                } else {
                    toastr.error(response.message || 'Failed to load currency data');
                }
            },
            error: function (xhr, status, error) {
                toastr.error('Failed to load currency data');
            }
        });
    };

    window.currencyHandler = function (response) {
        var output = '';
        var type = 'error';
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] === true) {
            toastr.success(response['message'])

            setTimeout(() => {
                location.reload()
            }, 1000);


        } else {
            commonHandler(response)
        }
    }

})(jQuery);
