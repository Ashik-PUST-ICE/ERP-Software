$(document).ready(function () {
    // Module switching
    $('.module-trigger').on('click', function (e) {
        e.preventDefault();
        var module = $(this).data('module');

        $('.module-trigger').removeClass('active');
        $(this).addClass('active');

        $('.module-content').addClass('d-none');
        $('.module-content[data-module="' + module + '"]').removeClass('d-none');

        $('#permission-search').val('');
        $('.permission-item').show();
    });

    // Permission search
    $('#permission-search').on('keyup', function () {
        var searchTerm = $(this).val().toLowerCase();
        var activeModule = $('.module-trigger.active').data('module');

        $('.permission-item').each(function () {
            var permissionText = $(this).find('.form-check-label').text().toLowerCase();
            var permissionModule = $(this).closest('.module-content').data('module');

            if (permissionModule === activeModule && permissionText.indexOf(searchTerm) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

