/**
 * Calendar JavaScript
 * Handles FullCalendar initialization and event management.
 * Platform icons: inline SVG (same as other pages), no Font Awesome dependency.
 */
var platformSvgs = {
    // facebook: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="18" viewBox="0 0 8 11" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.887822 4.49038C0.395276 4.49038 0.29248 4.58705 0.29248 5.0501V5.8897C0.29248 6.35279 0.395276 6.44941 0.887822 6.44941H2.0785V9.80776C2.0785 10.2708 2.1813 10.3675 2.67384 10.3675H3.86452C4.35709 10.3675 4.45985 10.2708 4.45985 9.80776V6.44941H5.79681C6.17039 6.44941 6.26665 6.38115 6.36927 6.04349L6.62442 5.20389C6.80018 4.62543 6.69187 4.49038 6.05196 4.49038H4.45985V3.09109C4.45985 2.78197 4.72639 2.53137 5.05519 2.53137H6.74965C7.24217 2.53137 7.34498 2.43472 7.34498 1.97165V0.852202C7.34498 0.389125 7.24217 0.29248 6.74965 0.29248H5.05519C3.4112 0.29248 2.0785 1.54546 2.0785 3.09109V4.49038H0.887822Z" stroke="#0D0D0D" stroke-width="0.585038" stroke-linejoin="round"/></svg>',
    // twitter: '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 26 26" fill="none"><path d="M15.1408 11.0092L24.6118 0H22.3675L14.1438 9.55916L7.57563 0H0L9.9324 14.4551L0 26H2.24444L10.9288 15.9052L17.8653 26H25.4409L15.1403 11.0092H15.1408ZM12.0667 14.5825L11.0604 13.1431L3.05315 1.68957H6.50048L12.9624 10.9329L13.9688 12.3723L22.3685 24.3873H18.9212L12.0667 14.583V14.5825Z" fill="black"/></svg>',
    // instagram: '<svg width="18" height="18" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" stroke="#ECE3E1"/><path d="M9.08 17c0-3.73 0-5.6 1.16-6.76 1.16-1.16 3.03-1.16 6.76-1.16 3.73 0 5.6 0 6.76 1.16 1.16 1.16 1.16 3.03 1.16 6.76 0 3.73 0 5.6-1.16 6.76-1.16 1.16-3.03 1.16-6.76 1.16-3.73 0-5.6 0-6.76-1.16C9.08 22.6 9.08 20.73 9.08 17z" stroke="#0D0D0D" stroke-width="1.2" stroke-linejoin="round"/><path d="M20.75 17a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" stroke="#0D0D0D" stroke-width="1.2"/></svg>',
    // linkedin: '<svg width="18" height="18" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" stroke="#ECE3E1"/><path d="M12.83 15.33v5.84M16.17 17.83v3.33c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5v-3.33M16.17 17.83c0-1.38-1.12-2.5-2.5-2.5s-2.5 1.12-2.5 2.5v3.33M12.84 12.83h-.01" stroke="#0D0D0D" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    // youtube: '<i class="fa-brands fa-youtube" style="font-size:18px;color:#FF0000;"></i>',
    // tiktok: '<svg width="18" height="18" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" stroke="#ECE3E1"/><path d="M9.08 17c0-3.73 0-5.6 1.16-6.76 1.16-1.16 3.03-1.16 6.76-1.16 3.73 0 5.6 0 6.76 1.16 1.16 1.16 1.16 3.03 1.16 6.76 0 3.73 0 5.6-1.16 6.76-1.16 1.16-3.03 1.16-6.76 1.16-3.73 0-5.6 0-6.76-1.16C9.08 22.6 9.08 20.73 9.08 17z" stroke="#0D0D0D" stroke-width="1.2" stroke-linejoin="round"/><path d="M15.78 16.17c-.68-.1-2.24.06-2.99 1.47-.77 1.41.01 2.88.48 3.44.47.52 1.98 1.51 3.6.54.4-.24.89-.42 1.45-2.29l-.07-7.36c.91.81 2.8 2.12 4.35 2.35" stroke="#0D0D0D" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    // threads: '<svg width="18" height="18" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" stroke="#ECE3E1"/><path d="M9.08 17c0-3.73 0-5.6 1.16-6.76 1.16-1.16 3.03-1.16 6.76-1.16 3.73 0 5.6 0 6.76 1.16 1.16 1.16 1.16 3.03 1.16 6.76 0 3.73 0 5.6-1.16 6.76-1.16 1.16-3.03 1.16-6.76 1.16-3.73 0-5.6 0-6.76-1.16C9.08 22.6 9.08 20.73 9.08 17z" stroke="#0D0D0D" stroke-width="1.2" stroke-linejoin="round"/></svg>'


    facebook: '<i class="fa-brands fa-facebook-f"></i>',
    twitter: '<i class="fa-brands fa-x-twitter"></i>',
    instagram: '<i class="fa-brands fa-instagram"></i>',
    linkedin: '<i class="fa-brands fa-linkedin-in"></i>',
    youtube: '<i class="fa-brands fa-youtube"></i>',
    tiktok: '<i class="fa-brands fa-tiktok"></i>',
    threads: '<i class="fa-brands fa-threads"></i>'


};
var defaultPlatformSvg = '<svg width="18" height="18" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="0.5" y="0.5" width="32" height="32" rx="9.5" fill="#FBF9F8" stroke="#ECE3E1"/><circle cx="16.5" cy="16.5" r="6" stroke="#0D0D0D" stroke-width="1.2"/></svg>';
function getPlatformSvg(platform) {
    if (!platform) return defaultPlatformSvg;
    var key = (platform + '').toLowerCase();
    return platformSvgs[key] || defaultPlatformSvg;
}
function escapeHtml(text) {
    if (!text) return '';
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
function getTimeLeftText(scheduledAtIso, isPosted) {
    if (isPosted) return '';
    if (!scheduledAtIso) return '';
    try {
        var scheduled = new Date(scheduledAtIso);
        var now = new Date();
        if (scheduled <= now) return '0s left';
        var diffMs = scheduled - now;
        var totalSec = Math.floor(diffMs / 1000);
        var sec = totalSec % 60;
        var totalMin = Math.floor(totalSec / 60);
        var min = totalMin % 60;
        var totalH = Math.floor(totalMin / 60);
        var h = totalH % 24;
        var d = Math.floor(totalH / 24);
        var parts = [];
        if (d > 0) parts.push(d + 'd');
        if (h > 0) parts.push(h + 'h');
        parts.push(min + 'm');
        parts.push(sec + 's');
        return parts.join(' ') + ' left';
    } catch (e) {
        return '';
    }
}
function updateAllTimeLeft() {
    document.querySelectorAll('.event-time-left[data-scheduled-at]').forEach(function (el) {
        var at = el.getAttribute('data-scheduled-at');
        if (at) el.textContent = getTimeLeftText(at, false);
    });
}

$(document).ready(function () {
    // Read configuration from #calendar-config data attributes (set by backend controller)
    var _calCfgEl = document.getElementById('calendar-config');
    var _ds = _calCfgEl ? _calCfgEl.dataset : {};
    const calendarConfig = {
        eventsUrl: _ds.eventsUrl || '',
        updateDateUrl: _ds.updateDateUrl || '',
        publishNowUrl: _ds.publishNowUrl || '',
        publishCampaignUrl: _ds.publishCampaignUrl || '',
        editPostUrl: _ds.editPostUrl || '',
        editCampaignUrl: _ds.editCampaignUrl || '',
        deletePostUrl: _ds.deletePostUrl || '',
        deleteCampaignUrl: _ds.deleteCampaignUrl || '',
        csrfToken: _ds.csrfToken || '',
        defaultAvatar: _ds.defaultAvatar || '',
        baseUrl: _ds.baseUrl || '',
        appTimeZone: _ds.appTimezone || 'UTC',
    };

    let calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    let calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: calendarConfig.appTimeZone,
        initialView: 'dayGridMonth',
        initialDate: new Date(),
        headerToolbar: false,
        editable: true,
        events: function (info, successCallback, failureCallback) {
            // Get selected platform (only one can be selected - mutually exclusive)
            const selectedPlatform = $('.platform-filter:checked').val() || '';
            const selectedPlatforms = selectedPlatform ? [selectedPlatform] : [];

            const selectedAccountIds = [];
            $('.account-filter:checked').each(function () {
                selectedAccountIds.push($(this).val());
            });

            // Fetch events from API
            $.ajax({
                url: calendarConfig.eventsUrl,
                type: 'GET',
                data: {
                    start: info.startStr,
                    end: info.endStr,
                    platforms: selectedPlatforms,
                    account_ids: selectedAccountIds
                },
                success: function (response) {
                    successCallback(response);
                },
                error: function () {
                    failureCallback();
                }
            });
        },
        eventContent: function (arg) {
            let wrap = document.createElement('div');
            wrap.className = 'post';

            let accountAvatar = arg.event.extendedProps.account_avatar || calendarConfig.defaultAvatar;
            if (accountAvatar && !accountAvatar.startsWith('http') && !accountAvatar.startsWith('/')) {
                accountAvatar = calendarConfig.baseUrl + '/' + accountAvatar.replace(/^\//, '');
            }
            const accountName = arg.event.extendedProps.account_name || '';
            const platform = (arg.event.extendedProps.platform || '').toString();
            const platformSvg = getPlatformSvg(platform);
            const eventTitle = arg.event.title || 'No content';
            const status = arg.event.extendedProps.status || 'pending';
            const isPosted = status === 'posted';
            const isCampaign = arg.event.extendedProps.type === 'campaign';
            const campaignName = arg.event.extendedProps.campaign_name || '';
            const scheduledAt = arg.event.extendedProps.scheduled_at_iso || '';

            // Page info: Campaign = name only. Platform = SVG icon only (in post-head)
            let pageInfo = '';
            if (isCampaign && campaignName) {
                pageInfo = 'Campaign: ' + campaignName;
            }

            const timeLeftHtml = (!isPosted && scheduledAt) ? '<div class="event-time-left" data-scheduled-at="' + scheduledAt + '">' + getTimeLeftText(scheduledAt, false) + '</div>' : '';

            if (isPosted) {
                wrap.innerHTML = `
                    <div class="post-head">
                        <div class="account-platform layout-model">
                            <span class="platform-top">${platformSvg}</span>
                        </div>
                        <span class="published-badge" style="font-size:10px;background:#10A958;color:#fff;padding:2px 6px;border-radius:4px;font-weight:600;">Published</span>
                    </div>
                    <div class="account-name">${escapeHtml(accountName)}</div>
                    ${pageInfo ? '<div class="event-page-info" >' + escapeHtml(pageInfo) + '</div>' : ''}
                    <div class="event-title">${escapeHtml(eventTitle)}</div>
                    ${timeLeftHtml}
                `;
            } else if (status === 'failed') {
                wrap.innerHTML = `
                    <div class="post-head">
                        <div class="account-platform layout-model">
                            <span class="platform-top">${platformSvg}</span>
                        </div>
                        <span class="failed-badge" style="font-size:10px;background:#DC3545;color:#fff;padding:2px 6px;border-radius:4px;font-weight:600;">Failed</span>
                    </div>
                    <div class="account-name">${escapeHtml(accountName)}</div>
                    ${pageInfo ? '<div class="event-page-info" >' + escapeHtml(pageInfo) + '</div>' : ''}
                    <div class="event-title">${escapeHtml(eventTitle)}</div>
                    <div class="event-dropdown">
                        <div class="edit"    data-id="${arg.event.id}">Edit</div>
                        <div class="publish" data-id="${arg.event.id}">Publish Now</div>
                        <div class="delete"  data-id="${arg.event.id}">Delete</div>
                    </div>
                `;
            } else {
                // For pending (scheduled) posts
                wrap.innerHTML = `
                    <div class="post-head">
                        <div class="account-platform layout-model">
                            <div class="platform-top">${platformSvg}</div>                            
                        </div>
                        <span class="scheduled-badge" style="font-size:10px;background:#FFA500;color:#fff;padding:2px 6px;border-radius:4px;font-weight:600;">Scheduled</span>
                        <span class="menu-btn">⋯</span>
                    </div>
                    <div class="account-name">${escapeHtml(accountName)}</div>
                    ${pageInfo ? '<div class="event-page-info">' + escapeHtml(pageInfo) + '</div>' : ''}
                    <div class="event-title">${escapeHtml(eventTitle)}</div>
                    ${timeLeftHtml}
                    <div class="event-dropdown">
                        <div class="edit"    data-id="${arg.event.id}">Edit</div>
                        <div class="publish" data-id="${arg.event.id}">Publish Now</div>
                        <div class="delete"  data-id="${arg.event.id}">Delete</div>
                    </div>
                `;
            }
            return { domNodes: [wrap] };
        },
        eventDidMount: function (info) {
            const el = info.el;
            if (!el) return;

            const dropdown = el.querySelector('.event-dropdown');
            const menuBtn = el.querySelector('.menu-btn');
            if (!dropdown || !menuBtn) return;

            dropdown.style.display = 'none';

            menuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                document.querySelectorAll('.event-dropdown').forEach(d => {
                    if (d !== dropdown) d.style.display = 'none';
                });
                dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
            });

            const editBtn = el.querySelector('.edit');
            const publishBtn = el.querySelector('.publish');
            const deleteBtn = el.querySelector('.delete');

            if (editBtn) {
                editBtn.onclick = () => {
                    const postId = editBtn.getAttribute('data-id');
                    if (!postId) return;
                    if (postId.startsWith('campaign_')) {
                        window.location.href = calendarConfig.editCampaignUrl.replace(':id', postId.replace('campaign_', ''));
                    } else {
                        const actualId = postId.startsWith('post_') ? postId.replace('post_', '') : postId;
                        window.location.href = calendarConfig.editPostUrl.replace(':id', actualId);
                    }
                };
            }

            if (publishBtn) {
                publishBtn.onclick = () => {
                    const postId = publishBtn.getAttribute('data-id');
                    if (!postId) return;
                    dropdown.style.display = 'none';

                    if (postId.startsWith('campaign_')) {
                        const campaignId = postId.replace('campaign_', '');
                        const url = (calendarConfig.publishCampaignUrl || '').replace(':id', campaignId);
                        if (!url) {
                            if (typeof toastr !== 'undefined') toastr.error('Campaign publish URL not configured');
                            return;
                        }
                        Swal.fire({
                            title: 'Publish Campaign?',
                            text: 'Publish all pending scheduled posts for this campaign now?',
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#FF4F02',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, Publish!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: url,
                                    type: 'POST',
                                    data: { _token: calendarConfig.csrfToken },
                                    headers: { 'Accept': 'application/json' },
                                    success: function (response) {
                                        if (response.status) {
                                            calendar.refetchEvents();
                                            if (typeof toastr !== 'undefined') toastr.success(response.message || 'Campaign posts published successfully');
                                        } else {
                                            if (typeof toastr !== 'undefined') toastr.error(response.message || 'Failed to publish campaign posts');
                                        }
                                    },
                                    error: function (xhr) {
                                        const msg = (xhr.responseJSON && xhr.responseJSON.message) ? xhr.responseJSON.message : 'Failed to publish campaign posts';
                                        if (typeof toastr !== 'undefined') toastr.error(msg);
                                    }
                                });
                            }
                        });
                        return;
                    }

                    const actualId = postId.startsWith('post_') ? postId.replace('post_', '') : postId;
                    const url = calendarConfig.publishNowUrl.replace(':id', actualId);

                    Swal.fire({
                        title: 'Publish Now?',
                        text: 'This post will be published immediately to the social media account.',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#FF4F02',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Publish!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: { _token: calendarConfig.csrfToken },
                                success: function (response) {
                                    if (response.success) {
                                        calendar.refetchEvents();
                                        toastr.success(response.message || 'Post published successfully');
                                    } else {
                                        toastr.error(response.error || 'Failed to publish post');
                                    }
                                },
                                error: function (xhr) {
                                    const msg = xhr.responseJSON && xhr.responseJSON.error
                                        ? xhr.responseJSON.error : 'Failed to publish post';
                                    toastr.error(msg);
                                }
                            });
                        }
                    });
                };
            }

            if (deleteBtn) {
                deleteBtn.onclick = () => {
                    const postId = deleteBtn.getAttribute('data-id');
                    if (!postId) return;
                    dropdown.style.display = 'none';
                    let url;
                    if (postId.startsWith('campaign_')) {
                        url = calendarConfig.deleteCampaignUrl.replace(':id', postId.replace('campaign_', ''));
                    } else {
                        const actualId = postId.startsWith('post_') ? postId.replace('post_', '') : postId;
                        url = calendarConfig.deletePostUrl.replace(':id', actualId);
                    }

                    Swal.fire({
                        title: 'Sure! You want to delete?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Delete It!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: { _token: calendarConfig.csrfToken },
                                success: function (response) {
                                    if (response.status || response.success) {
                                        Swal.fire({
                                            title: 'Deleted',
                                            html: '<span style="color:green">Post has been deleted</span>',
                                            timer: 2000,
                                            icon: 'success'
                                        });
                                        toastr.success(response.message || 'Post deleted successfully');
                                        calendar.refetchEvents();
                                    } else {
                                        Swal.fire({ title: 'Error!', text: response.message, icon: 'error' });
                                    }
                                },
                                error: function () {
                                    Swal.fire({ title: 'Error!', text: 'Something went wrong!', icon: 'error' });
                                }
                            });
                        }
                    });
                };
            }
        },
        datesSet: function (info) {
            updateHeader(info.start, info.end, info.view.title);
            setTimeout(updateAllTimeLeft, 300);
        },
        eventDrop: function (info) {
            const postId = info.event.id;

            // Block drag for published posts
            if (info.event.extendedProps.status === 'posted') {
                info.revert();
                if (typeof toastr !== 'undefined') toastr.warning('Published posts cannot be rescheduled');
                return;
            }

            // Check if it's a campaign - campaigns don't support drag and drop update yet
            if (postId && postId.startsWith('campaign_')) {
                if (typeof toastr !== 'undefined') {
                    toastr.info('Campaign date update is not supported yet');
                }
                calendar.refetchEvents(); // Refresh to revert the change
                return;
            }

            let newDate = info.event.startStr; // Could be ISO: 2026-02-17T00:00:00+06:00

            // Extract date part (YYYY-MM-DD) from ISO format
            if (newDate && typeof newDate === 'string' && newDate.includes('T')) {
                newDate = newDate.split('T')[0];
            } else if (newDate && typeof newDate === 'string' && newDate.includes(' ')) {
                newDate = newDate.split(' ')[0];
            }

            const oldTime = info.event.extendedProps.time || '00:00'; // Format: HH:mm

            // Combine date with existing time: Y-m-d H:i:s
            // Controller will handle parsing and validation
            const scheduledDateTime = `${newDate} ${oldTime}:00`;

            // Debug logging
            console.log('Event Drop Debug:', {
                postId: postId,
                originalDate: info.event.startStr,
                datePart: newDate,
                oldTime: oldTime,
                scheduledDateTime: scheduledDateTime
            });

            // Update via AJAX - extract actual post ID if it has 'post_' prefix
            const actualPostId = postId.startsWith('post_') ? postId.replace('post_', '') : postId;
            const updateUrl = calendarConfig.updateDateUrl.replace(':id', actualPostId);

            $.ajax({
                url: updateUrl,
                type: 'POST',
                data: {
                    _token: calendarConfig.csrfToken,
                    scheduled_time: scheduledDateTime
                },
                success: function (response) {
                    if (response.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message ||
                                'Post scheduled date updated successfully');
                        } else {
                            alert(response.message ||
                                'Post scheduled date updated successfully');
                        }
                    }
                },
                error: function (xhr) {
                    // Revert the event if update fails
                    info.revert();
                    const errorMsg = xhr.responseJSON?.error || xhr.responseJSON?.message || 'Failed to update scheduled date';
                    if (typeof toastr !== 'undefined') {
                        toastr.error(errorMsg);
                    } else {
                        alert(errorMsg);
                    }
                }
            });
        }
    });

    calendar.render();

    // Dynamic time-remaining: update every 1 sec so seconds keep decreasing (format: Xd Xh Xm Xs left)
    setInterval(updateAllTimeLeft, 1000);
    setTimeout(updateAllTimeLeft, 100);

    // Navigation buttons
    $('#prevBtn').click(() => calendar.prev());
    $('#nextBtn').click(() => calendar.next());

    // Update header function (matching HTML template)
    function updateHeader(start, end, title) {
        let s = new Date(start);
        let e = new Date(end);
        e.setDate(e.getDate() - 1);

        let opt = {
            month: 'short',
            day: 'numeric'
        };
        $('#rangeText').text(
            s.toLocaleDateString('en-US', opt) + ' - ' +
            e.toLocaleDateString('en-US', opt)
        );
        $('#monthTitle').text(title);
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', () => {
        document.querySelectorAll('.event-dropdown').forEach(d => {
            if (d.isConnected) d.style.display = 'none';
        });
    });

    // Platform filter - mutually exclusive selection (like create post page)
    $('.platform-filter').on('change', function () {
        const isChecked = $(this).is(':checked');

        // If a platform is checked, uncheck all other platforms (mutually exclusive)
        if (isChecked) {
            $('.platform-filter').not(this).prop('checked', false);
        }

        // Get the selected platform (only one can be selected)
        const selectedPlatform = $('.platform-filter:checked').val();

        if (selectedPlatform) {
            // Show only accounts matching the selected platform
            $('.account-filter').each(function () {
                const accountPlatform = $(this).data('platform');
                if (accountPlatform && accountPlatform.toLowerCase() === selectedPlatform.toLowerCase()) {
                    $(this).closest('li').show();
                } else {
                    $(this).closest('li').hide();
                }
            });
        } else {
            // If no platform selected, show all accounts
            $('.account-filter').closest('li').show();
        }

        // Refresh calendar events to show only posts from selected platform
        calendar.refetchEvents();
    });

    // Account filter - dynamic filtering
    $('.account-filter').on('change', function () {
        calendar.refetchEvents();
    });

    // Search filter for accounts
    $('#search').on('keyup', function () {
        const searchTerm = $(this).val().toLowerCase();
        $('.accounts-list li').each(function () {
            const accountText = $(this).text().toLowerCase();
            if (accountText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
