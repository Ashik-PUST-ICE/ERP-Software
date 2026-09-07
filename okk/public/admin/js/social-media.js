(function ($) {
    "use strict";

    if (window.location.hash === '#_=_') {
        history.replaceState
            ? history.replaceState(null, null, window.location.href.split('#')[0])
            : (window.location.hash = '');
    }

    var socialAccountsDataTable = null;
    var platformDataTables = {};

    function initPlatformDataTable(platform) {
        var tableId = '#socialMediaDataTable-' + platform;
        var searchInputId = '#searchData' + platform.charAt(0).toUpperCase() + platform.slice(1);

        if ($(tableId).length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable(tableId)) {
                var dt = $(tableId).DataTable({
                    responsive: true,
                    paging: false,
                    searching: true,
                    info: false,
                    ordering: false,
                    dom: 't',
                    columnDefs: [{ targets: 'keep-show', className: 'all' }]
                });
                platformDataTables[platform] = dt;

                $(searchInputId).off('keyup.platformSearch').on('keyup.platformSearch', function () {
                    dt.search(this.value).draw();
                });
            }
        }
    }

    function openPlatformModal(platform) {
        if (!platform) return;
        var modalEl = document.querySelector('#' + platform + '-connect-modal');
        if (modalEl) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else {
            if (typeof toastr !== 'undefined') toastr.error('Platform modal not found');
        }
    }

    function submitDelete(url) {
        $.ajax({
            url: url,
            type: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            data: { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (response) {
                if (response.status) {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ title: 'Deleted', html: '<span style="color:green">Account has been deleted</span>', timer: 2000, icon: 'success' });
                    }
                    if (typeof toastr !== 'undefined') toastr.success(response.message);
                    setTimeout(function () { window.location.reload(); }, 500);
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({ title: 'Error!', text: response.message || 'Failed to delete', icon: 'error' });
                    } else {
                        alert(response.message || 'Failed to delete');
                    }
                }
            },
            error: function (xhr) {
                var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Something went wrong!';
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ title: 'Error!', text: msg, icon: 'error' });
                } else {
                    alert(msg);
                }
            }
        });
    }

    $(document).ready(function () {

        // ── DataTables ─────────────────────────────────────────────────────────

        if ($('#socialMediaDataTable').length && typeof $.fn.DataTable !== 'undefined') {
            if (!$.fn.DataTable.isDataTable('#socialMediaDataTable')) {
                socialAccountsDataTable = $('#socialMediaDataTable').DataTable({
                    responsive: true,
                    paging: false,
                    searching: true,
                    info: false,
                    ordering: false,
                    dom: 't',
                    columnDefs: [{ targets: 'keep-show', className: 'all' }]
                });
            }
            $('#searchData').off('keyup.socialAccounts').on('keyup.socialAccounts', function () {
                if (socialAccountsDataTable) socialAccountsDataTable.search(this.value).draw();
            });
        }

        function updateConnectButtonVisibility() {
            var $activeTabBtn = $('.nav-link.active[data-platform]');
            var platform = $activeTabBtn.length ? $activeTabBtn.data('platform') : null;
            var $connectBtn = $('#connectAccountBtnMain');

            if (!platform || platform === 'all') {
                $connectBtn.addClass('d-none').hide();
            } else {
                $connectBtn.removeClass('d-none').show();
            }
        }

        $(document).on('shown.bs.tab', '[data-bs-toggle="tab"][data-platform]', function (e) {
            var platform = $(this).data('platform');
            updateConnectButtonVisibility();
            if (platform && platform !== 'all') {
                setTimeout(function () { initPlatformDataTable(platform); }, 100);
            }
        });

        var $activeTab = $('[data-bs-toggle="tab"][data-platform].active');
        if ($activeTab.length) {
            var activePlatform = $activeTab.data('platform');
            updateConnectButtonVisibility();
            if (activePlatform && activePlatform !== 'all') {
                setTimeout(function () { initPlatformDataTable(activePlatform); }, 300);
            }
        } else {
            updateConnectButtonVisibility();
        }

        // ── Edit account ───────────────────────────────────────────────────────

        $(document).on('click', '.edit-account-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var accountId = $(this).data('account-id');
            var platform = $(this).data('platform');
            var editDataUrl = $(this).data('edit-data-url');

            if (!accountId || !platform || !editDataUrl) return;

            $.ajax({
                url: editDataUrl,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var $modal = $('#' + data.platform + '-edit-modal');
                    var $form = $('#' + data.platform + '-edit-form');
                    if (!$modal.length || !$form.length) return;

                    var urlTemplate = $form.attr('data-update-url-template') || $form.data('updateUrlTemplate');
                    if (urlTemplate) {
                        $form.attr('action', urlTemplate.replace('__ID__', data.id));
                    }

                    $form.find('[name="account_id"]').val(data.account_id || '');
                    $form.find('[name="username"]').val(data.username || '');
                    $form.find('[name="access_token"]').val(data.access_token || '');
                    $form.find('[name="is_active"]').val(data.is_active ? '1' : '0');
                    $form.find('[name="email"]').val(data.email || '');
                    $form.find('[name="page_id"]').val(data.page_id || '');
                    if (data.access_token_secret !== undefined) {
                        $form.find('[name="access_token_secret"]').val(data.access_token_secret || '');
                    }
                    if (data.bearer_token !== undefined) {
                        $form.find('[name="bearer_token"]').val(data.bearer_token || '');
                    }
                    if (data.refresh_token !== undefined) {
                        $form.find('[name="refresh_token"]').val(data.refresh_token || '');
                    }

                    bootstrap.Modal.getOrCreateInstance($modal[0]).show();
                },
                error: function () {
                    if (typeof toastr !== 'undefined') toastr.error('Failed to load account data.');
                }
            });
        });

        // ── Edit form AJAX submit ──────────────────────────────────────────────

        $(document).on('submit', '.social-account-edit-form', function (e) {
            e.preventDefault();
            var $form = $(this);
            var url = $form.attr('action');
            if (!url) return;

            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.html();
            $submitBtn.prop('disabled', true).html('...');

            $.ajax({
                url: url,
                type: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                data: $form.serialize(),
                success: function (response) {
                    if (response.status) {
                        if (typeof toastr !== 'undefined') toastr.success(response.message);
                        $form.closest('.modal').modal('hide');
                        setTimeout(function () { window.location.reload(); }, 500);
                    } else {
                        if (typeof toastr !== 'undefined') toastr.error(response.message || 'Update failed');
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function (xhr) {
                    var msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Update failed';
                    if (typeof toastr !== 'undefined') toastr.error(msg);
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // ── Connect Account ────────────────────────────────────────────────────

        $('#connectAccountBtnMain').on('click', function (e) {
            e.preventDefault();
            var $activeTabBtn = $('.nav-link.active[data-platform]');
            var platform = $activeTabBtn.length ? $activeTabBtn.data('platform') : null;
            if (!platform || platform === 'all') return;
            openPlatformModal(platform);
        });

        $('#connectAccountBtn').on('click', function () {
            var selectedPlatform = $('.platform-select-radio:checked').val();
            if (!selectedPlatform) {
                if (typeof toastr !== 'undefined') toastr.warning('Please select a platform');
                return;
            }
            var connectModal = bootstrap.Modal.getInstance(document.getElementById('ConnectAccountModal'));
            if (connectModal) connectModal.hide();
            openPlatformModal(selectedPlatform);
        });

        // ── Create form AJAX submit ────────────────────────────────────────────

        $(document).on('submit', 'form[action*="admin.social.account.store"]', function (e) {
            var $form = $(this);
            var formAction = $form.attr('action');
            if (!formAction || formAction.indexOf('admin.social.account.store') === -1) return;

            e.preventDefault();
            var $submitBtn = $form.find('button[type="submit"]');
            var originalText = $submitBtn.html();
            $submitBtn.prop('disabled', true).html('Saving...');

            $.ajax({
                url: formAction,
                type: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                data: $form.serialize(),
                success: function (response) {
                    if (response.status || response.success || response.message) {
                        if (typeof toastr !== 'undefined') toastr.success(response.message || 'Account created successfully');
                        $form.closest('.modal').modal('hide');
                        setTimeout(function () { window.location.reload(); }, 500);
                    } else {
                        if (typeof toastr !== 'undefined') toastr.error(response.message || 'Failed to create account');
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                },
                error: function (xhr) {
                    var msg = 'Failed to create account';
                    if (xhr.responseJSON) {
                        msg = xhr.responseJSON.message || (xhr.responseJSON.errors ? Object.values(xhr.responseJSON.errors).flat().join(', ') : msg);
                    }
                    if (typeof toastr !== 'undefined') toastr.error(msg);
                    $submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        // ── Delete ────────────────────────────────────────────────────────────

        $(document).on('click', '.delete-item', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var url = $(this).data('route');

            if (typeof Swal === 'undefined') {
                if (confirm('Are you sure you want to delete this account? This action cannot be undone.')) {
                    submitDelete(url);
                }
                return;
            }

            Swal.fire({
                title: 'Sure! You want to delete?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Delete It!'
            }).then(function (result) {
                if (result.isConfirmed) submitDelete(url);
            });
        });

    });

})(jQuery);
