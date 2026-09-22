(function ($) {
    "use strict";

    function readTemplates() {
        try {
            return JSON.parse($('#email-templates-data').text() || '{}');
        } catch (error) {
            return {};
        }
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    function previewText(value, fallback) {
        var appName = $('#email-app-name').val() || 'Application';
        return (value || '')
            .split('\u007b\u007bname\u007d\u007d').join('Recipient Name')
            .split('\u007b\u007bemail\u007d\u007d').join('recipient@example.com')
            .split('\u007b\u007bapp_name\u007d\u007d').join(appName)
            .split('\u007b\u007bdate\u007d\u007d').join(new Date().toLocaleDateString()) || fallback;
    }

    function refreshPreview() {
        $('#modal_preview_subject').text(previewText($('#modal_email_subject').val(), 'Your subject will appear here'));
        $('#modal_preview_message').text(previewText($('#modal_email_message').val(), 'Your message preview will appear here.'));
    }

    function statusMarkup(history) {
        if (history.status === 1) return '<span class="email-status sent"><i class="fa-solid fa-check"></i>Sent</span>';
        if (history.status === 2) return '<span class="email-status pending"><i class="fa-solid fa-clock"></i>Pending</span>';
        return '<span class="email-status failed"><i class="fa-solid fa-xmark"></i>Failed</span>' +
            (history.error ? '<div class="small text-danger mt-1">' + escapeHtml(history.error.substring(0, 70)) + '</div>' : '');
    }

    function renderPagination(pagination) {
        var html = '';
        if (pagination.last_page > 1) {
            for (var page = 1; page <= pagination.last_page; page++) {
                html += '<button type="button" class="btn btn-sm ' +
                    (page === pagination.current_page ? 'primary-btn' : 'btn-outline-secondary') +
                    ' me-1 history-page" data-page="' + page + '">' + page + '</button>';
            }
        }
        $('#email-history-pagination').html(html);
    }

    function loadHistory(page) {
        var $body = $('#email-history-body');
        $body.html('<tr><td colspan="5" class="text-muted text-center py-4"><i class="fa-solid fa-spinner fa-spin me-2"></i>Loading email history...</td></tr>');

        $.get($('#email-history-route').val(), { page: page || 1 })
            .done(function (response) {
                if (!response.data || !response.data.length) {
                    $body.html('<tr><td colspan="5" class="text-muted text-center py-4">No email history found</td></tr>');
                    $('#email-history-pagination').empty();
                    return;
                }

                $body.html(response.data.map(function (history) {
                    var retry = history.status === 0
                        ? '<form method="POST" action="' + $('#email-retry-route').val().replace('__ID__', history.id) + '">' +
                          '<input type="hidden" name="_token" value="' + $('meta[name="csrf-token"]').attr('content') + '">' +
                          '<button type="submit" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-rotate-right"></i> Retry</button></form>'
                        : '<span class="text-muted">—</span>';

                    return '<tr><td class="fw-500">' + escapeHtml(history.email) + '</td>' +
                        '<td>' + escapeHtml((history.subject || '').substring(0, 45)) + '</td>' +
                        '<td>' + statusMarkup(history) + '</td>' +
                        '<td>' + escapeHtml(history.date || '') + '</td>' +
                        '<td class="text-end">' + retry + '</td></tr>';
                }).join(''));
                renderPagination(response.pagination);
            })
            .fail(function () {
                $body.html('<tr><td colspan="5" class="text-danger text-center py-4">Unable to load email history.</td></tr>');
            });
    }

    $(document).ready(function () {
        var templates = readTemplates();
        var $modal = $('#send-template-modal');
        var $templateModal = $('#template-management-modal');
        var $templateForm = $('#template-management-form');

        function resetTemplateForm() {
            $templateForm.attr('action', $('#template-store-route').val());
            $('#template-form-method').val('POST');
            $('#template_name, #template_subject, #template_body, #template_variables').val('');
            $('#template_status').prop('checked', true);
            $('#template-save-btn').text('Save Template');
        }

        $('#template-new-btn').on('click', resetTemplateForm);
        $templateModal.on('show.bs.modal', function () {
            resetTemplateForm();
        });
        $templateModal.on('click', '.template-edit-btn', function () {
            var template = templates[$(this).data('template-id')];
            if (!template) return;
            $templateForm.attr('action', $('#template-update-route').val().replace('__ID__', $(this).data('template-id')));
            $('#template-form-method').val('PUT');
            $('#template_name').val(template.name || '');
            $('#template_subject').val(template.subject || '');
            $('#template_body').val(template.body || '');
            $('#template_variables').val(template.variables || '');
            $('#template_status').prop('checked', !!template.status);
            $('#template-save-btn').text('Update Template');
            $('#template_name').trigger('focus');
        });

        var $deleteModal = $('#template-delete-modal');
        $deleteModal.on('show.bs.modal', function (event) {
            var $button = $(event.relatedTarget);
            $('#template-delete-name').text($button.data('template-name') || '');
            $('#template-delete-form').attr('action', $('#template-delete-route').val().replace('__ID__', $button.data('template-id')));
        });

        $modal.on('show.bs.modal', function (event) {
            var id = $(event.relatedTarget).data('template-id') || '';
            var template = templates[id];
            $('#modal_template_id').val(id);

            if (template) {
                $('#selected-template-name').html('<i class="fa-solid fa-layer-group"></i>' + escapeHtml(template.name));
                $('#modal_email_subject').val(template.subject);
                $('#modal_email_message').val(template.body);
            } else {
                $('#selected-template-name').html('<i class="fa-solid fa-pen-to-square"></i>Custom email');
                $('#modal_email_subject').val('');
                $('#modal_email_message').val('');
            }
            refreshPreview();
        });

        $('#modal_email_subject, #modal_email_message').on('input', refreshPreview);
        $('#email-history-pagination').on('click', '.history-page', function () {
            loadHistory($(this).data('page'));
        });
        loadHistory(1);
    });
})(jQuery);
