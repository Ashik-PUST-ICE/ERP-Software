var getCurrencySymbol = $('#getCurrencySymbol').val();
var allCurrency = JSON.parse($('#allCurrency').val());
var supportedCurrency = JSON.parse($('#supportedCurrency').val());

(function ($) {
    "use strict";
    $(document).on('click', '.edit', function (e) {
        commonAjax('GET', $('#getInfoRoute').val(), getDataEditRes, getDataEditRes, { 'id': $(this).data('id') });
    });
    $('.add-currency').on('click', function (e) {
        var html = '';
        html += '<div class="input-group mb-3 currency-conversation-rate">' +
            '<select name="currency[]" class="form-control currency" required>';
        Object.entries(allCurrency).forEach((currency) => {
            html += '<option value="' + currency[0] + '">' + currency[1] + '</option>';
        });
        html += '</select>' +
            '<span class="input-group-text">1  ' + getCurrencySymbol + ' = </span>' +
            '<input type="number" step="any" min="0" name="conversion_rate[]" value="" class="form-control" required>' +
            '<input type="hidden" step="any" min="0" name="currency_id[]" value="" class="form-control" required>' +
            '<span class="input-group-text append_currency"></span>' +
            '<button type="button" class="bg-white border-0 font-24 mr-5 ms-3 removedItem text-danger bg-fafafa border-0" title="Remove"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"/></svg></button>' +
            '</div>';
        $('#currencyConversionRateSection').append(html);
        $('.currency').trigger("change");
    })

    $(document).on('click', '.removedItem', function () {
        $(this).closest('.currency-conversation-rate').remove();
    });

    $(document).on('change', '.currency', function () {
        // Get the selected currency from the dropdown
        let selectedCurrency = $(this).val();

        // Find the closest currency conversion rate container and update the appended currency text
        $(this).closest('.currency-conversation-rate').find('.append_currency').text(selectedCurrency);

        // Fetch the current gateway slug from an element in the #editModal
        let gatewaySlug = $('#editModal').find('.slug').val();

        // Check if the payment method is not "bank" or "cash"
        if (gatewaySlug !== 'bank' && gatewaySlug !== 'cash') {
            // Check if the selected currency is supported by the current gateway
            if (supportedCurrency[gatewaySlug] && supportedCurrency[gatewaySlug].includes(selectedCurrency)) {
                // If the currency is supported, remove any existing warning notes
                $(this).closest('.currency-conversation-rate').find('.currency-warning').remove();
            } else {
                // If the currency is not supported, add a warning note
                let warningNote = '<div class="fs-14 currency-warning text-danger">Currency not supported, please check and add carefully.</div>';
                // Check if the warning note already exists to avoid duplicates
                if ($(this).closest('.currency-conversation-rate').find('.currency-warning').length === 0) {
                    $(this).closest('.currency-conversation-rate').append(warningNote);
                }
            }
        } else {
            // If payment method is "bank" or "cash", remove any existing warning notes
            $(this).closest('.currency-conversation-rate').find('.currency-warning').remove();
        }
    });

    // Bank
    $(document).on('click', '.add-bank', function () {
        var newBankHtml = addBank();
        var $newBank = $(newBankHtml);
        $('.bank-div-append').append($newBank);
        // Initialize niceSelect only on the newly added select element
        $newBank.find('.sf-select-without-search').niceSelect();
    });

    $(document).on('click', '.remove-bank', function () {
        $(this).closest('.multi-bank').remove()
    });


    window.getDataEditRes = function (response) {
        console.log(response);
        const selector = $('#editModal');
        selector.find('.gateway-input').removeClass('d-none');
        selector.modal('show')
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        $('#id').val(response.data.gateway.id)
        // Update form action for update
        var updateUrl = $('#editModal form').attr('action').replace(':id', response.data.gateway.id);
        $('#editModal form').attr('action', updateUrl);
        selector.find('.image').attr('src', response.data.image)
        selector.find('.title').val(response.data.gateway.title)
        selector.find('.slug').val(response.data.gateway.slug)

        selector.find('select[name=status]').val(response.data.gateway.status)
        selector.find('select[name=mode]').val(response.data.gateway.mode)
        selector.find('input[name=key]').val(response.data.gateway.key)

        $('.sf-select-without-search').niceSelect('update');

        var gatewaySettings = JSON.parse($('#gatewaySettings').val());
        let currentGateway = gatewaySettings[response.data.gateway.slug];

        // Hide all API configuration fields by default
        selector.find('.gateway-input').addClass('d-none');

        if (typeof currentGateway != 'undefined' && currentGateway.length > 0) {
            // Show and update fields based on gateway settings
            currentGateway.forEach(option => {
                if (option.name == 'url' && option.is_show == 1) {
                    selector.find('#gateway-url .gateway-field-label').text(option.label);
                    selector.find('#gateway-url').removeClass('d-none');
                } else if (option.name == 'key' && option.is_show == 1) {
                    selector.find('#gateway-key .gateway-field-label').text(option.label);
                    selector.find('#gateway-key').removeClass('d-none');
                } else if (option.name == 'secret' && option.is_show == 1) {
                    selector.find('#gateway-secret .gateway-field-label').text(option.label);
                    selector.find('#gateway-secret').removeClass('d-none');
                }
            });
        }


        selector.find('input[name=secret]').val(response.data.gateway.secret)
        selector.find('input[name=url]').val(response.data.gateway.url)

        if (response.data.gateway.slug == 'bank') {
            selector.find('.mode-div').hide();
            selector.find('.url-div').hide();
            selector.find('.bank-div').show();
            var banks = response.data.banks;
            var bankHtml = '';
            if (banks.length > 0) {
                Object.entries(banks).map(function (bank) {
                    var isSelected = '';
                    if (bank[1].status == 1) {
                        isSelected = 'selected';
                    } else {
                        isSelected = '';
                    }
                    bankHtml += `<div class="multi-bank mb-3 p-3 border rounded">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="hidden" name="bank[id][]" value="${bank[1].id}">
                                                <label for="bank-name-${bank[1].id}" class="form-label">Bank Name</label>
                                                <input type="text" name="bank[name][]" class="form-control bank-name" id="bank-name-${bank[1].id}" placeholder="Bank Name" value="${bank[1].name}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="bank-status-${bank[1].id}" class="form-label">Status</label>
                                                <select name="bank[status][]" class="select form-control wide sf-select-without-search" id="bank-status-${bank[1].id}">
                                                    <option value="1" ${bank[1].status == 1 ? 'selected' : ''}>Active</option>
                                                    <option value="0" ${bank[1].status == 0 ? 'selected' : ''}>Deactivate</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="bank-details-${bank[1].id}" class="form-label">Bank Details</label>
                                                <textarea name="bank[details][]" id="bank-details-${bank[1].id}" class="form-control" rows="3" placeholder="Enter bank details">${bank[1].details}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-sm btn-danger remove-bank" title="Remove">
                                                <i class="fa fa-trash me-1"></i>Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>`

                });
            } else {
                bankHtml += `<div class="multi-bank mb-3 p-3 border rounded">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="bank[id][]" value="">
                                <label for="bank-name-new" class="form-label">Bank Name</label>
                                <input type="text" name="bank[name][]" class="form-control bank-name" id="bank-name-new" placeholder="Bank Name" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bank-status-new" class="form-label">Status</label>
                                <select name="bank[status][]" class="select form-control wide sf-select-without-search" id="bank-status-new">
                                    <option value="1">Active</option>
                                    <option value="0">Deactivate</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="bank-details-new" class="form-label">Bank Details</label>
                                <textarea name="bank[details][]" id="bank-details-new" class="form-control" rows="3" placeholder="Enter bank details"></textarea>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-sm btn-danger remove-bank" title="Remove">
                                <i class="fa fa-trash me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>`
            }

            $('.bank-div-append').html(bankHtml);
            $('.bank-div-append').find('.sf-select-without-search').niceSelect();
        } else {
            selector.find('.mode-div').show();
            selector.find('.url-div').show();
            selector.find('.bank-div').hide();
        }
        var html = '';
        response.data.currencies.map(function (data) {
            html += '<div class="input-group mb-3 currency-conversation-rate">' +
                '<select name="currency[]" class="form-control currency" required>';
            Object.entries(allCurrency).forEach((currency) => {
                if (currency[0] == data.currency) {
                    html += '<option value="' + currency[0] + '" selected>' + currency[1] + '</option>';
                } else {
                    html += '<option value="' + currency[0] + '">' + currency[1] + '</option>';
                }
            });
            html += '</select>' +
                '<span class="input-group-text">1  ' + getCurrencySymbol + ' = </span>' +
                '<input type="number" step="any" min="0" name="conversion_rate[]" value="' + data.conversion_rate + '" class="form-control" required>' +
                '<input type="hidden" step="any" min="0" name="currency_id[]" value="' + data.id + '" class="form-control" required>' +
                '<span class="input-group-text append_currency">' + data.currency + '</span>' +
                '<button type="button" class="bg-white border-0 font-24 mr-5 ms-3 removedItem text-danger" title="Remove"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z"/></svg></button>' +
                '</div>';
        });
        $('#currencyConversionRateSection').html(html);
    }

    window.addBank = function () {
        var uniqueId = 'bank-' + Date.now();
        return `<div class="multi-bank mb-3 p-3 border rounded">
                    <div class="row gy-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="hidden" name="bank[id][]" value="">
                                <label for="${uniqueId}-name" class="form-label">Bank Name</label>
                                <input type="text" name="bank[name][]" class="form-control bank-name" id="${uniqueId}-name" placeholder="Bank Name" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="${uniqueId}-status" class="form-label">Status</label>
                                <select name="bank[status][]" class="select form-control wide sf-select-without-search" id="${uniqueId}-status">
                                    <option value="1">Active</option>
                                    <option value="0">Deactivate</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="${uniqueId}-details" class="form-label">Bank Details</label>
                                <textarea name="bank[details][]" id="${uniqueId}-details" class="form-control" rows="3" placeholder="Enter bank details"></textarea>
                            </div>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-sm btn-danger remove-bank" title="Remove">
                                <i class="fa fa-trash me-1"></i>Remove
                            </button>
                        </div>
                    </div>
                </div>`;
    }

    window.responseOnGatewaStore = function (response) {
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
