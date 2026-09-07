(function ($) {
    "use strict";

    // Table search functionality
    $(document).ready(function () {
        $('#searchData').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('table tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });

    $('#add').on('click', function () {
        var selector = $('#addLanguageModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show')
        selector.find('form').trigger("reset");
    });

    $(document).on('click', '.editLanguageBtn', function () {
        var selector = $('#editLanguageModal');
        selector.find('.is-invalid').removeClass('is-invalid');
        selector.find('.error-message').remove();
        selector.modal('show');
        var url = $('#editLanguageRoute').data('route')
        selector.find('form').attr('action', url.replace("@", $(this).data('data')['id']));
        selector.find('form input[name=name]').val($(this).data('data')['name']);
        selector.find('form input[name=code]').val($(this).data('data')['code']);
        selector.find('form select[name=rtl]').val($(this).data('data')['rtl']);
        selector.find('form select[name=default]').val($(this).data('data')['default']);
        selector.find('form select[name=status]').val($(this).data('data')['status']);
        document.getElementById("editImageShow").src = $(this).data('data')['icon'];
    });

    // Translate
    $('.addmore').on('click', function (e) {
        e.preventDefault()
        let updateText = $('#update-text').length ? $('#update-text').val() : 'Update';
        let html = `
                    <tr>
                        <td>
                            <textarea type="text" name="key" class="key form-control" required></textarea>
                        </td>
                        <td>
                            <input type="hidden" value="1" class="is_new">
                            <textarea type="text" name="value" class="val form-control" required></textarea>
                        </td>
                        <td class="text-end col-1">
                            <button type="button" class="primary-btn updateLangItem">${updateText}</button>
                        </td>
                    </tr>

                    <tr>

                    `;
        $('#append').prepend(html);
    })

    $(document).on('input', '.val', function () {
        $(this).closest('tr').find('button').attr('disabled', false);
    })

    $(document).on('click', '.updateLangItem', function () {
        var keyStr = $(this).closest('tr').find('.key').val();
        var valStr = $(this).closest('tr').find('.val').val();
        var is_new = $(this).closest('tr').find('.is_new').val();
        commonAjax('GET', $('#updateLangItemRoute').val(), getDataShowRes, getDataShowRes, {
            'key': keyStr,
            'val': valStr,
            'is_new': is_new
        });
    });

    $("#sf-select-modal-add").select2({
        dropdownCssClass: "sf-select-dropdown",
        selectionCssClass: "sf-select-section",
        dropdownParent: $("#add-modal"),
    });

    // Initialize select2 for edit modal ISO code select
    $("#iso_code-edit").select2({
        dropdownCssClass: "sf-select-dropdown",
        selectionCssClass: "sf-select-section",
        dropdownParent: $("#edit-modal"),
    });

    // Initialize select2 for edit modal RTL select
    $("#rtl-edit").select2({
        dropdownCssClass: "sf-select-dropdown",
        selectionCssClass: "sf-select-section",
        dropdownParent: $("#edit-modal"),
    });

    // Initialize select2 for import modal
    $(document).on('shown.bs.modal', '#importModal', function () {
        if ($('#inputGroupSelect02').length && !$('#inputGroupSelect02').hasClass('select2-hidden-accessible')) {
            $('#inputGroupSelect02').select2({
                dropdownCssClass: "sf-select-dropdown",
                selectionCssClass: "sf-select-section",
                dropdownParent: $("#importModal"),
            });
        }
    });

    // Open edit modal and populate with data
    window.openEditModal = function (url, languageId) {
        $.ajax({
            type: 'GET',
            url: url,
            success: function (response) {
                if (response.status === true && response.data) {
                    var language = response.data.language;
                    var flagUrl = response.data.flag_url;

                    // Set form action - extract base URL and replace ID
                    var currentAction = $('#edit-language-form').attr('action');
                    var updateUrl = currentAction.replace(/\/\d+$/, '/' + languageId);
                    $('#edit-language-form').attr('action', updateUrl);

                    // Populate form fields
                    $('#language-edit').val(language.language);
                    $('#iso_code-edit').val(language.iso_code).trigger('change');
                    $('#rtl-edit').val(language.rtl).trigger('change');

                    // Set flag preview
                    if (flagUrl) {
                        $('#flag-preview-edit').attr('src', flagUrl).show();
                    }

                    // Set default checkbox
                    if (language.default == 1) {
                        $('#flexCheckChecked-edit').prop('checked', true);
                    } else {
                        $('#flexCheckChecked-edit').prop('checked', false);
                    }

                    // Initialize select2 if not already initialized
                    if (!$('#iso_code-edit').hasClass('select2-hidden-accessible')) {
                        $('#iso_code-edit').select2({
                            dropdownCssClass: "sf-select-dropdown",
                            selectionCssClass: "sf-select-section",
                            dropdownParent: $("#edit-modal"),
                        });
                    } else {
                        $('#iso_code-edit').trigger('change');
                    }

                    if (!$('#rtl-edit').hasClass('select2-hidden-accessible')) {
                        $('#rtl-edit').select2({
                            dropdownCssClass: "sf-select-dropdown",
                            selectionCssClass: "sf-select-section",
                            dropdownParent: $("#edit-modal"),
                        });
                    } else {
                        $('#rtl-edit').trigger('change');
                    }

                    // Show modal
                    $('#edit-modal').modal('show');
                } else {
                    toastr.error('Failed to load language data');
                }
            },
            error: function (error) {
                toastr.error(error.responseJSON?.message || 'Failed to load language data');
            }
        });
    };

    window.languageHandler = function (response) {
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

    window.getDataShowRes = function (response) {
        var output = '';
        var type = 'error';
        $('.error-message').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (response['status'] == true) {
            output = output + response['message'];
            type = 'success';
            toastr.success(response.message)
        } else {
            toastr.error(response['responseJSON'].message)
        }
    }

    // Load translations via AJAX
    function loadTranslations(page = 1, search = '') {
        $.ajax({
            url: $('#language-translate-route').val(),
            type: 'GET',
            data: { page: page, search: search },
            success: function (data) {
                $('#translations-container').html(data);
            },
            error: function () {
                toastr.error('Failed to load translations');
            }
        });
    }

    // Debounce timer
    let debounceTimer;

    // Live search on input
    $('#search-form input[name="search"]').on('input', function () {
        clearTimeout(debounceTimer);
        let search = $(this).val();
        debounceTimer = setTimeout(function () {
            loadTranslations(1, search); // always start from page 1
        }, 500); // 500ms debounce delay
    });

    // Also intercept form submit (optional, for search button)
    $('#search-form').on('submit', function (e) {
        e.preventDefault();
        let search = $(this).find('input[name="search"]').val();
        loadTranslations(1, search);
    });

    // Pagination click (AJAX)
    $(document).on('click', '.ajax-page', function () {
        let page = $(this).data('page');
        let search = $('#search-form').find('input[name="search"]').val();
        loadTranslations(page, search);
    });


})(jQuery)
