(function ($) {
    "use strict";

    const platformPostTypes = {
        'facebook': ['Feed', 'Reels'],
        'instagram': ['Reels', 'Story'],
        'twitter': ['Feed'],
        'linkedin': ['Feed'],
        'tiktok': ['Reels'],
        'youtube': ['Reels', 'Video'],
        'threads': ['Feed']
    };

    const platformIcons = {
        'facebook': '<i class="fa-brands fa-facebook-f"></i>',
        'instagram': '<i class="fa-brands fa-instagram"></i>',
        'twitter': '<i class="fa-brands fa-x-twitter"></i>',
        'linkedin': '<i class="fa-brands fa-linkedin-in"></i>',
        'tiktok': '<i class="fa-brands fa-tiktok"></i>',
        'youtube': '<i class="fa-brands fa-youtube"></i>',
        'threads': '<i class="fa-brands fa-threads"></i>'
    };

    const tabContentTemplates = {
        'Reels': '<div class="reels-area"><div class="author-area"><span class="icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="19" height="19" rx="5.75758" fill="#2563EB" /><path fill-rule="evenodd" clip-rule="evenodd" d="M6.99623 8.98805C6.52711 8.98805 6.4292 9.08012 6.4292 9.52115V10.3208C6.4292 10.7619 6.52711 10.8539 6.99623 10.8539H8.1303V14.0526C8.1303 14.4936 8.22821 14.5857 8.69733 14.5857H9.8314C10.3005 14.5857 10.3984 14.4936 10.3984 14.0526V10.8539H11.6718C12.0276 10.8539 12.1193 10.7889 12.2171 10.4673L12.4601 9.66763C12.6275 9.11668 12.5243 8.98805 11.9148 8.98805H10.3984V7.65529C10.3984 7.36086 10.6523 7.12218 10.9654 7.12218H12.5793C13.0484 7.12218 13.1464 7.03013 13.1464 6.58907V5.52285C13.1464 5.0818 13.0484 4.98975 12.5793 4.98975H10.9654C9.39963 4.98975 8.1303 6.18315 8.1303 7.65529V8.98805H6.99623Z" stroke="white" stroke-width="0.863636" stroke-linejoin="round" /></svg></span><div class="author-info"><h4>Alex Anderson</h4><span>Now </span></div></div><div class="template-preview-media reels-preview-media" style="display:none;"></div><div class="no-media"><svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="50" height="50" rx="25" fill="#FF4F02" /><path d="M31 25L22 30.1962L22 19.8038L31 25Z" fill="white" /></svg><h4>No Media Selected</h4></div><ul class="reels-options"><li><span class="icon"><svg width="27" height="28" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="27" height="27.9167" rx="13.5" fill="#333333" /><path d="M12.5732 18.6062C10.9275 17.3755 7.66699 14.562 7.66699 12.0301C7.66699 10.3566 8.89506 9 10.5837 9C11.4587 9 12.3337 9.29167 13.5003 10.4583C14.667 9.29167 15.542 9 16.417 9C18.1056 9 19.3337 10.3566 19.3337 12.0301C19.3337 14.562 16.0732 17.3755 14.4274 18.6062C13.8736 19.0203 13.1271 19.0203 12.5732 18.6062Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span> 40K</li><li><span class="icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="28" height="28" rx="14" fill="#333333" /><path d="M14.0025 14H14.0085M16.6662 14H16.6722M11.3389 14H11.3448" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M20.3337 13.9998C20.3337 17.4976 17.4981 20.3332 14.0003 20.3332C12.9149 20.3332 11.8933 20.0601 11.0003 19.579C9.75484 18.9078 8.91674 19.5318 8.17761 19.6437C8.06549 19.6607 7.95382 19.62 7.87364 19.5398C7.75193 19.4181 7.72877 19.2299 7.79599 19.0714C8.08609 18.3877 8.35246 17.092 7.98927 15.9998C7.78019 15.3712 7.66699 14.6987 7.66699 13.9998C7.66699 10.502 10.5025 7.6665 14.0003 7.6665C17.4981 7.6665 20.3337 10.502 20.3337 13.9998Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span> 1.5K</li><li><span class="icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="28" height="28" rx="14" fill="#333333" /><path d="M16.667 12.6665C17.6004 12.6665 18.0671 12.6665 18.4237 12.8482C18.7373 13.008 18.9922 13.2629 19.152 13.5765C19.3337 13.933 19.3337 14.3998 19.3337 15.3332V17.3332C19.3337 18.9045 19.3337 19.6902 18.8455 20.1784C18.3573 20.6665 17.5717 20.6665 16.0003 20.6665H12.0003C10.429 20.6665 9.64331 20.6665 9.15515 20.1784C8.66699 19.6902 8.66699 18.9045 8.66699 17.3332V15.3332C8.66699 14.3998 8.66699 13.933 8.84865 13.5765C9.00844 13.2629 9.26341 13.008 9.57701 12.8482C9.93353 12.6665 10.4002 12.6665 11.3337 12.6665" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 16.6665V8.6665" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M16 9.99999C16 9.99999 14.527 8.00001 14 8C13.4729 7.99999 12 10 12 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span> 55</li></ul><div class="reels-footer" style="padding:8px 0 0;border-top:1px solid rgba(255,255,255,0.2);margin-top:8px;"><p class="template-preview-content mb-0" style="font-size:14px;color:#ffffff !important;line-height:1.4;text-shadow:0 1px 4px rgba(0,0,0,1);"></p></div></div>',
        'Video': '<div class="reels-area"><div class="author-area"><span class="icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="19" height="19" rx="5.75758" fill="#FF0000" /><path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 6.5V12.5L12.5 9.5L7.5 6.5Z" stroke="white" stroke-width="1.5" stroke-linejoin="round" /></svg></span><div class="author-info"><h4>Alex Anderson</h4><span>Video </span></div></div><div class="template-preview-media reels-preview-media" style="display:none;"></div><div class="no-media"><svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="50" height="50" rx="25" fill="#FF0000" /><path d="M31 25L22 30.1962L22 19.8038L31 25Z" fill="white" /></svg><h4>No Media Selected</h4></div><ul class="reels-options"><li><span class="icon"><svg width="27" height="28" viewBox="0 0 27 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="27" height="27.9167" rx="13.5" fill="#333333" /><path d="M12.5732 18.6062C10.9275 17.3755 7.66699 14.562 7.66699 12.0301C7.66699 10.3566 8.89506 9 10.5837 9C11.4587 9 12.3337 9.29167 13.5003 10.4583C14.667 9.29167 15.542 9 16.417 9C18.1056 9 19.3337 10.3566 19.3337 12.0301C19.3337 14.562 16.0732 17.3755 14.4274 18.6062C13.8736 19.0203 13.1271 19.0203 12.5732 18.6062Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span> 0</li><li><span class="icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="28" height="28" rx="14" fill="#333333" /><path d="M14.0025 14H14.0085M16.6662 14H16.6722M11.3389 14H11.3448" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M20.3337 13.9998C20.3337 17.4976 17.4981 20.3332 14.0003 20.3332C12.9149 20.3332 11.8933 20.0601 11.0003 19.579C9.75484 18.9078 8.91674 19.5318 8.17761 19.6437C8.06549 19.6607 7.95382 19.62 7.87364 19.5398C7.75193 19.4181 7.72877 19.2299 7.79599 19.0714C8.08609 18.3877 8.35246 17.092 7.98927 15.9998C7.78019 15.3712 7.66699 14.6987 7.66699 13.9998C7.66699 10.502 10.5025 7.6665 14.0003 7.6665C17.4981 7.6665 20.3337 10.502 20.3337 13.9998Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span> 0</li><li><span class="icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="28" height="28" rx="14" fill="#333333" /><path d="M16.667 12.6665C17.6004 12.6665 18.0671 12.6665 18.4237 12.8482C18.7373 13.008 18.9922 13.2629 19.152 13.5765C19.3337 13.933 19.3337 14.3998 19.3337 15.3332V17.3332C19.3337 18.9045 19.3337 19.6902 18.8455 20.1784C18.3573 20.6665 17.5717 20.6665 16.0003 20.6665H12.0003C10.429 20.6665 9.64331 20.6665 9.15515 20.1784C8.66699 19.6902 8.66699 18.9045 8.66699 17.3332V15.3332C8.66699 14.3998 8.66699 13.933 8.84865 13.5765C9.00844 13.2629 9.26341 13.008 9.57701 12.8482C9.93353 12.6665 10.4002 12.6665 11.3337 12.6665" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M14 16.6665V8.6665" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M16 9.99999C16 9.99999 14.527 8.00001 14 8C13.4729 7.99999 12 10 12 10" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></span> 0</li></ul><div class="reels-footer" style="padding:8px 0 0;border-top:1px solid rgba(255,255,255,0.2);margin-top:8px;"><p class="template-preview-content mb-0" style="font-size:14px;color:#ffffff !important;line-height:1.4;text-shadow:0 1px 4px rgba(0,0,0,1);"></p></div></div>'
    };

    function getPreviewProfileName(platformKey) {
        if (!platformKey) return 'Account';
        return platformKey.charAt(0).toUpperCase() + platformKey.slice(1);
    }

    let selectedImages = [];
    let selectedVideos = [];
    let uploadedFiles = [];

    window.toggleMediaSelection = function (element) {
        const id = $(element).data('id');
        const type = $(element).data('type');
        const url = $(element).data('url');
        const icon = $(element).find('.select-icon');
        const postType = typeof getCurrentPostType === 'function' ? getCurrentPostType() : 'Feed';
        const videoOnly = typeof isVideoOnlyPostType === 'function' && isVideoOnlyPostType();

        if (videoOnly) {
            if (type === 'image') {
                if (typeof toastr !== 'undefined') toastr.warning('Reels, Story and Video posts support one video only. Please select a video.');
                return;
            }
            const vidIndex = selectedVideos.findIndex(vid => vid.id == id);
            if (vidIndex > -1) {
                selectedVideos.splice(vidIndex, 1);
                icon.hide();
                $(element).removeClass('selected');
            } else {
                if (selectedVideos.length >= 1) {
                    selectedVideos.forEach(function (v) {
                        $('.gallery-item-select[data-type="video"][data-id="' + v.id + '"]').removeClass('selected').find('.select-icon').hide();
                    });
                    selectedVideos = [];
                }
                selectedVideos.push({ id, url });
                icon.show();
                $(element).addClass('selected');
            }
            updateSelectionCount();
            return;
        }

        if (postType === 'Feed') {
            if (type === 'image') {
                const imgIndex = selectedImages.findIndex(img => img.id == id);
                if (imgIndex > -1) {
                    selectedImages.splice(imgIndex, 1);
                    icon.hide();
                    $(element).removeClass('selected');
                } else {
                    if (selectedVideos.length > 0) {
                        selectedVideos.forEach(function (v) {
                            $('.gallery-item-select[data-type="video"][data-id="' + v.id + '"]').removeClass('selected').find('.select-icon').hide();
                        });
                        selectedVideos = [];
                    }
                    selectedImages.push({ id, url });
                    icon.show();
                    $(element).addClass('selected');
                }
            } else {
                const vidIndex = selectedVideos.findIndex(vid => vid.id == id);
                if (vidIndex > -1) {
                    selectedVideos.splice(vidIndex, 1);
                    icon.hide();
                    $(element).removeClass('selected');
                } else {
                    if (selectedVideos.length >= 1) {
                        selectedVideos.forEach(function (v) {
                            $('.gallery-item-select[data-type="video"][data-id="' + v.id + '"]').removeClass('selected').find('.select-icon').hide();
                        });
                        selectedVideos = [];
                    }
                    selectedImages.forEach(function (img) {
                        $('.gallery-item-select[data-type="image"][data-id="' + img.id + '"]').removeClass('selected').find('.select-icon').hide();
                    });
                    selectedImages = [];
                    selectedVideos = [{ id, url }];
                    icon.show();
                    $(element).addClass('selected');
                }
            }
            updateSelectionCount();
            return;
        }

        if (type === 'image') {
            const index = selectedImages.findIndex(img => img.id == id);
            if (index > -1) {
                selectedImages.splice(index, 1);
                icon.hide();
                $(element).removeClass('selected');
            } else {
                selectedImages.push({ id, url });
                icon.show();
                $(element).addClass('selected');
            }
        } else {
            const index = selectedVideos.findIndex(vid => vid.id == id);
            if (index > -1) {
                selectedVideos.splice(index, 1);
                icon.hide();
                $(element).removeClass('selected');
            } else {
                selectedVideos.push({ id, url });
                icon.show();
                $(element).addClass('selected');
            }
        }
        updateSelectionCount();
    };

    function updateSelectionCount() {
        const total = selectedImages.length + selectedVideos.length;
        if (total > 0) {
            $('#selectionCount').text(total).show();
        } else {
            $('#selectionCount').hide();
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function syncContentToPreview() {
        var $content = $('#content');
        var $previews = $('#PostContent .template-preview-content');
        if (!$content.length || !$previews.length) return;
        var raw = $content.val();
        var hasContent = raw && raw.trim();
        var display = hasContent ? escapeHtml(raw.trim()).replace(/\n/g, '<br>') : '';

        $previews.each(function () {
            var $p = $(this);
            var placeholder = "What's in your mind";
            if ($p.closest('.story-content-center').length) placeholder = "Your story...";
            else if ($p.closest('.reels-footer').length) placeholder = "Add caption...";
            $p.html(display || placeholder);
        });
    }

    function getCurrentPostType() {
        var $activeBtn = $('#Post .nav-link.active');
        if ($activeBtn.length) {
            var btnId = $activeBtn.attr('id') || '';
            if (btnId.indexOf('Reels') !== -1) return 'Reels';
            if (btnId.indexOf('Story') !== -1) return 'Story';
            if (btnId.indexOf('Shorts') !== -1) return 'Shorts';
            if (btnId.indexOf('Video') !== -1) return 'Video';
            if (btnId.indexOf('Feed') !== -1) return 'Feed';

            var btnText = $activeBtn.text().trim();
            if (['Feed', 'Reels', 'Story', 'Shorts', 'Video'].indexOf(btnText) !== -1) {
                return btnText;
            }
        }

        var $activePane = $('#PostContent .tab-pane.active, #PostContent .tab-pane.show');
        if ($activePane.length) {
            var paneId = $activePane.attr('id') || '';
            if (paneId.indexOf('Reels') !== -1) return 'Reels';
            if (paneId.indexOf('Story') !== -1) return 'Story';
            if (paneId.indexOf('Shorts') !== -1) return 'Shorts';
            if (paneId.indexOf('Video') !== -1) return 'Video';
            if (paneId.indexOf('Feed') !== -1) return 'Feed';
        }

        var $input = $('#post_type');
        if ($input.length && $input.val()) {
            var val = $input.val().trim();
            if (['Feed', 'Reels', 'Story', 'Shorts', 'Video'].indexOf(val) !== -1) {
                return val;
            }
            val = val.toLowerCase();
            return val.charAt(0).toUpperCase() + val.slice(1);
        }

        return 'Feed';
    }

    function isVideoOnlyPostType() {
        var pt = getCurrentPostType();
        return pt === 'Reels' || pt === 'Shorts' || pt === 'Video';
    }

    function getAllPreviewMedia(videoOnly) {
        var list = [];
        if (!videoOnly) {
            (selectedImages || []).forEach(function (img) {
                list.push({ type: 'image', url: img.url, id: img.id, source: 'gallery' });
            });
        }
        (selectedVideos || []).forEach(function (vid) {
            list.push({ type: 'video', url: vid.url, id: vid.id, source: 'gallery' });
        });
        if (typeof uploadedFiles !== 'undefined') {
            uploadedFiles.forEach(function (item) {
                if (videoOnly && item.type !== 'video') return;
                list.push({ type: item.type, url: item.url, id: item.id, source: 'upload' });
            });
        }
        return list;
    }

    function renderPreviewMediaHtml(mediaList, isFeed) {
        if (!mediaList || mediaList.length === 0) return '';
        var html = '';
        if (isFeed && mediaList.length > 1) {
            html = '<div class="feed-media-grid" style="display:grid;grid-template-columns:repeat(' + Math.min(mediaList.length, 3) + ',1fr);gap:4px;margin-top:8px;">';
        }
        mediaList.forEach(function (item) {
            var wrapStyle = 'position:relative;display:block;margin-bottom:4px;';
            var btnStyle = 'position:absolute;top:6px;right:6px;z-index:2;width:24px;height:24px;padding:0;border-radius:50%;background:#ff4d4d;color:#fff;border:2px solid #fff;cursor:pointer;font-size:14px;line-height:20px;text-align:center;';
            html += '<div class="preview-media-item" data-id="' + (item.id || '') + '" data-type="' + (item.type || '') + '" data-source="' + (item.source || '') + '" style="' + wrapStyle + '">';
            if (item.type === 'image') {
                html += '<img src="' + item.url + '" alt="" style="width:100%;max-height:280px;object-fit:cover;border-radius:8px;display:block;">';
            } else {
                html += '<video src="' + item.url + '" controls style="width:100%;max-height:280px;object-fit:cover;border-radius:8px;display:block;"></video>';
            }
            html += '<button type="button" class="preview-media-remove" style="' + btnStyle + '" aria-label="Remove">&times;</button></div>';
        });
        if (isFeed && mediaList.length > 1) html += '</div>';
        return html;
    }

    function syncMediaToPreview() {
        var videoOnly = isVideoOnlyPostType();
        var mediaList = getAllPreviewMedia(false);
        var mediaForReels = videoOnly ? getAllPreviewMedia(true) : mediaList;
        var $postContent = $('#PostContent');
        if (!$postContent.length) return;

        $postContent.find('.template-preview-media').each(function () {
            var $el = $(this);
            var isFeed = $el.hasClass('feed-media');
            var isReels = $el.hasClass('reels-preview-media');
            var list = (isReels && videoOnly) ? mediaForReels : mediaList;
            var hasMedia = list.length > 0;
            if (hasMedia) {
                $el.html(renderPreviewMediaHtml(list, isFeed)).show();
            } else {
                $el.empty().hide();
            }
        });
        $postContent.find('.no-media').each(function () {
            var $pane = $(this).closest('.tab-pane');
            var isReelsPane = $pane.attr('id') && ($pane.attr('id').indexOf('Reels') !== -1 || $pane.attr('id').indexOf('Shorts') !== -1);
            var hasMedia = isReelsPane && videoOnly ? mediaForReels.length > 0 : mediaList.length > 0;
            $(this).toggle(!hasMedia);
        });
    }

    $(document).ready(function () {

        if ($('#content').length && $('#PostContent').length) {
            $('#content').on('input keyup', function () {
                syncContentToPreview();
            });
            syncContentToPreview();
            syncMediaToPreview();
        }

        (function initAIAssistant() {
            var $cfg = $('#post-config');
            var aiUrl = $cfg.data('ai-generate-url');
            var aiEnabled = $cfg.data('ai-enabled');
            var aiUpgradeMessage = $cfg.data('ai-upgrade-message') || 'Your current plan does not include AI features. Please upgrade your package.';
            if (!aiUrl) return;

            function getCsrfToken() {
                var meta = document.querySelector('meta[name="csrf-token"]');
                return meta ? meta.getAttribute('content') : '';
            }

            $(document).on('click', '#aiAssistantBtn', function (e) {
                e.preventDefault();

                var selectedPlatform = $('.platform-filter:checked').val();
                if (!selectedPlatform) {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Please select a platform first (Facebook, Instagram, etc.) before using AI Assistant.');
                    } else {
                        alert('Please select a platform first (Facebook, Instagram, etc.) before using AI Assistant.');
                    }
                    return;
                }

                var hasPostTypeTabs = $('#Post .nav-link').length > 0;
                if (hasPostTypeTabs) {
                    var activeTab = $('#Post .nav-link.active');
                    if (!activeTab.length) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Please select a post type (Feed, Reels, Story) first.');
                        } else {
                            alert('Please select a post type (Feed, Reels, Story) first.');
                        }
                        return;
                    }
                }

                $('#AIAssistantModal').modal('show');
            });

            $(document).on('submit', '#AIAssistantModal form', function (e) {
                e.preventDefault();
            });

            function showAiError(msg) {
                if (typeof toastr !== 'undefined') toastr.error(msg);
                else alert(msg);
            }

            function showAiSuccess(msg) {
                if (typeof toastr !== 'undefined') toastr.success(msg);
                else alert(msg);
            }

            $('#AIAssistantModal').on('hidden.bs.modal', function () {
                $('#ai-preview-section').hide();
                resetAIPreview();
            });

            $(document).on('click', '#close-ai-preview', function () {
                $('#ai-preview-section').hide();
                resetAIPreview();
            });

            function resetAIPreview() {
                $('#ai-text-preview, #ai-image-preview, #ai-video-preview').hide();
                $('#ai-loading-spinner').hide();
                $('#generated-text-content').text('');
                $('#generated-image').attr('src', '');
                $('#generated-video').attr('src', '');
            }

            var generatedContent = {
                type: null,
                text: null,
                mediaUrl: null,
                galleryId: null,
                videoId: null,
                aiRecordId: null
            };

            function getSelectedPlatform() {
                var platform = $('.platform-filter:checked').val();
                return platform || 'facebook'; // Default to Facebook
            }

            function getCurrentPostType() {
                var $activeBtn = $('#Post .nav-link.active');
                if ($activeBtn.length) {
                    var btnId = $activeBtn.attr('id') || '';
                    if (btnId.indexOf('Reels') !== -1) return 'Reels';
                    if (btnId.indexOf('Story') !== -1) return 'Story';
                    if (btnId.indexOf('Shorts') !== -1) return 'Shorts';
                    if (btnId.indexOf('Video') !== -1) return 'Video';
                    if (btnId.indexOf('Feed') !== -1) return 'Feed';
                }
                return 'Feed';
            }

            function optimizePrompt(prompt, contentType, platform, postType) {
                var platformSpecifics = {
                    'facebook': {
                        'Feed': 'Write engaging Facebook post content. Keep it conversational and shareable. Include relevant hashtags if appropriate.',
                        'Reels': 'Create a short, catchy caption for a Facebook Reel video. Make it trendy and engaging.',
                        'Story': 'Write a brief, casual message for a Facebook Story. Keep it personal and spontaneous.'
                    },
                    'instagram': {
                        'Feed': 'Create an Instagram caption that engages followers. Use line breaks, emojis, and relevant hashtags.',
                        'Reels': 'Write an attention-grabbing caption for an Instagram Reel. Include trending tags and call-to-action.',
                        'Story': 'Create a short, fun message for Instagram Story. Keep it casual and interactive.'
                    },
                    'twitter': {
                        'Feed': 'Write a concise, impactful Twitter post. Keep it under 280 characters with relevant hashtags.'
                    },
                    'linkedin': {
                        'Feed': 'Create a professional LinkedIn post. Focus on industry insights, professional achievements, or thought leadership.'
                    },
                    'tiktok': {
                        'Reels': 'Write a viral TikTok caption. Use popular trends and engaging language.'
                    },
                    'youtube': {
                        'Reels': 'Create a YouTube Shorts caption. Make it catchy to grab attention.',
                        'Shorts': 'Write a YouTube Shorts caption. Focus on hook and trending topics.'
                    },
                    'threads': {
                        'Feed': 'Create a Threads post. Keep it conversational and authentic. Use line breaks for readability.'
                    }
                };

                var instructions = '';

                if (contentType === 'text') {
                    if (platformSpecifics[platform] && platformSpecifics[platform][postType]) {
                        instructions = platformSpecifics[platform][postType];
                    } else if (platformSpecifics[platform] && platformSpecifics[platform]['Feed']) {
                        instructions = platformSpecifics[platform]['Feed'];
                    } else {
                        instructions = 'Create engaging social media content suitable for ' + platform + '.';
                    }
                } else if (contentType === 'image') {
                    instructions = 'Generate an image description for ' + platform + ' ' + postType + '. Make it visually appealing and ';
                    if (platform === 'instagram') instructions += 'aesthetic, trending, and Instagram-friendly.';
                    else if (platform === 'facebook') instructions += 'engaging and shareable.';
                    else if (platform === 'linkedin') instructions += 'professional and high-quality.';
                    else instructions += 'visually striking.';
                } else if (contentType === 'video') {
                    instructions = 'Generate a video concept for ' + platform + ' ' + postType + '. ';
                    instructions += 'Create short-form vertical video content (9:16 aspect ratio). ';
                    if (platform === 'instagram') instructions += 'Make it engaging for Instagram.';
                    else if (platform === 'facebook') instructions += 'Create engaging content.';
                    else if (platform === 'tiktok') instructions += 'Create trending content.';
                    else if (platform === 'youtube') instructions += 'Create short-form video content.';
                }

                return instructions + ' User request: ' + prompt;
            }

            $(document).on('click', '#AIAssistantModal .generate-btn', function (e) {
                e.preventDefault();
                if (aiEnabled !== 1 && aiEnabled !== '1') {
                    showAiError(aiUpgradeMessage);
                    return;
                }

                var selectedPlatform = $('.platform-filter:checked').val();
                if (!selectedPlatform) {
                    showAiError('Please select a platform first (Facebook, Instagram, etc.) before generating AI content.');
                    return;
                }

                var $btn = $(this);
                var contentType = $btn.data('content-type');

                var allowedPostTypes = platformPostTypes[selectedPlatform] || ['Feed'];
                var currentPostType = getCurrentPostType();

                if (contentType === 'video') {
                    var supportsVideo = allowedPostTypes.some(function (pt) {
                        return pt === 'Reels' || pt === 'Shorts';
                    });
                    if (!supportsVideo) {
                        showAiError('Video generation is not supported for ' + selectedPlatform.charAt(0).toUpperCase() + selectedPlatform.slice(1) + '. Supported types: ' + allowedPostTypes.join(', '));
                        return;
                    }
                }

                if (contentType === 'image') {
                }

                var $pane = $btn.closest('.tab-pane');
                var $textarea = $pane.find('textarea.assistant-text-box');
                if (!$textarea.length) return;

                var prompt = ($textarea.val() || '').trim();
                if (!prompt) {
                    showAiError('Please write something for AI Assistant.');
                    return;
                }

                var platform = getSelectedPlatform();
                var postType = getCurrentPostType();

                var optimizedPrompt = optimizePrompt(prompt, contentType, platform, postType);

                var originalHtml = $btn.html();
                $btn.prop('disabled', true).addClass('disabled');
                $btn.html('Generating...');

                // Show loading spinner
                $('#ai-preview-section').show();
                $('#ai-loading-spinner').show();
                // Hide all preview items initially
                $('#ai-text-preview, #ai-image-preview, #ai-video-preview').hide();

                var requestData = {
                    prompt: optimizedPrompt,
                    content_type: contentType,
                    platform: platform,
                    post_type: postType
                };

                fetch(aiUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(requestData)
                })
                    .then(function (res) { return res.json().then(function (data) { return { ok: res.ok, data: data }; }); })
                    .then(function (result) {
                        var data = result.data;
                        // Hide loading spinner
                        $('#ai-loading-spinner').hide();
                        if (result.ok && data && data.success) {
                            resetAIPreview();
                            $('#ai-preview-section').show();

                            if (contentType === 'text' && data.text) {
                                $('#ai-text-preview').show();
                                $('#generated-text-content').text(data.text);
                                generatedContent = {
                                    type: 'text',
                                    text: data.text,
                                    mediaUrl: null,
                                    galleryId: null,
                                    videoId: null,
                                    aiRecordId: data.id || null
                                };
                                showAiSuccess('Text generated successfully for ' + platform + ' ' + postType + '!');
                            } else if (contentType === 'image' && data.media_url) {
                                $('#ai-image-preview').show();
                                $('#generated-image').attr('src', data.media_url);
                                generatedContent = {
                                    type: 'image',
                                    text: null,
                                    mediaUrl: data.media_url,
                                    galleryId: data.gallery_id || null,
                                    videoId: null,
                                    aiRecordId: data.id || null
                                };
                                showAiSuccess('Image generated successfully for ' + platform + ' ' + postType + '! Saved to gallery.');
                            } else if (contentType === 'video' && data.media_url) {
                                $('#ai-video-preview').show();
                                $('#generated-video').attr('src', data.media_url);
                                generatedContent = {
                                    type: 'video',
                                    text: null,
                                    mediaUrl: data.media_url,
                                    galleryId: null,
                                    videoId: data.video_id || null,
                                    aiRecordId: data.id || null
                                };
                                showAiSuccess('Video generated successfully for ' + platform + ' ' + postType + '! Saved to gallery.');
                            } else {
                                showAiError('Unexpected response from AI. Please try again.');
                            }
                        } else {
                            showAiError((data && data.message) || 'AI content generation failed.');
                        }
                    })
                    .catch(function () {
                        showAiError('Request failed. Please try again.');
                    })
                    .finally(function () {
                        $('#ai-loading-spinner').hide();
                        $btn.prop('disabled', false).removeClass('disabled');
                        $btn.html(originalHtml);
                    });
            });

            $(document).on('click', '#save-text-btn', function () {
                if (generatedContent.type === 'text' && generatedContent.text) {
                    showAiSuccess('Text saved to AI Generated Content!');
                }
            });

            $(document).on('click', '#save-image-btn', function () {
                if (generatedContent.type === 'image' && generatedContent.mediaUrl) {
                    showAiSuccess('Image already saved to your gallery!');
                }
            });

            $(document).on('click', '#save-video-btn', function () {
                if (generatedContent.type === 'video' && generatedContent.mediaUrl) {
                    showAiSuccess('Video already saved to your gallery!');
                }
            });

            $(document).on('click', '#use-text-btn', function () {
                if (generatedContent.type === 'text' && generatedContent.text) {
                    var $content = $('#content');
                    if ($content.length) {
                        $content.val(generatedContent.text);
                        syncContentToPreview();
                    }
                    $('#AIAssistantModal').modal('hide');
                    showAiSuccess('Text added to post!');
                }
            });

            $(document).on('click', '#use-image-btn', function () {
                if (generatedContent.type === 'image' && generatedContent.mediaUrl) {
                    var imageId = generatedContent.galleryId || 'ai_' + Date.now();
                    if (getCurrentPostType() === 'Feed') {
                        selectedVideos = [];
                        $('.gallery-item-select[data-type="video"]').removeClass('selected').find('.select-icon').hide();
                    }
                    selectedImages.push({
                        id: imageId,
                        url: generatedContent.mediaUrl
                    });
                    syncMediaToPreview();
                    updateSelectionCount();
                    $('#AIAssistantModal').modal('hide');
                    showAiSuccess('Image added to post!');
                }
            });

            $(document).on('click', '#use-video-btn', function () {
                if (generatedContent.type === 'video' && generatedContent.mediaUrl) {
                    var videoId = generatedContent.videoId || 'ai_' + Date.now();
                    if (typeof isVideoOnlyPostType === 'function' && isVideoOnlyPostType()) {
                        selectedVideos = [];
                    } else if (getCurrentPostType() === 'Feed') {
                        selectedImages = [];
                        $('.gallery-item-select[data-type="image"]').removeClass('selected').find('.select-icon').hide();
                    }
                    selectedVideos.push({
                        id: videoId,
                        url: generatedContent.mediaUrl
                    });
                    syncMediaToPreview();
                    updateSelectionCount();
                    $('#AIAssistantModal').modal('hide');
                    showAiSuccess('Video added to post!');
                }
            });
        })();

        if ($('#PostContent').length && $('.platform-filter').length) {
            function filterAccounts() {
                var selectedPlatforms = [];
                $('.platform-name input[type="checkbox"]:checked').each(function () {
                    selectedPlatforms.push($(this).data('platform'));
                });
                if (selectedPlatforms.length === 0) {
                    $('.accounts-list li').show();
                } else {
                    $('.accounts-list li').hide();
                    $('.accounts-list li').each(function () {
                        var accountPlatform = $(this).find('input').data('platform');
                        if (selectedPlatforms.includes(accountPlatform)) {
                            $(this).show();
                        }
                    });
                }
            }

            function updatePostTypeTabs() {
                var selectedPlatform = $('.platform-filter:checked').data('platform');
                var tabsContainer = $('#Post');
                if (!tabsContainer.length) tabsContainer = $('.post-tabs');
                var tabContentContainer = $('#PostContent');

                var postTypes = platformPostTypes[selectedPlatform] || ['Feed'];

                tabsContainer.empty();
                tabContentContainer.empty();

                postTypes.forEach(function (postType, index) {
                    var isActive = index === 0 ? 'active' : '';
                    var ariaSelected = index === 0 ? 'true' : 'false';
                    var showClass = index === 0 ? 'show active' : '';
                    var tabId = postType + '-tab';
                    var tabPaneId = postType + '-tab-pane';
                    var platformIcon = platformIcons[selectedPlatform] || platformIcons['facebook'];

                    tabsContainer.append(
                        '<li class="nav-item" role="presentation">' +
                        '<button class="nav-link ' + isActive + '" id="' + tabId + '" data-bs-toggle="tab" data-bs-target="#' + tabPaneId + '" type="button" role="tab" aria-controls="' + tabPaneId + '" aria-selected="' + ariaSelected + '">' + postType + '</button>' +
                        '</li>'
                    );

                    var profileName = getPreviewProfileName(selectedPlatform);
                    var contentWithIcon;
                    if (postType === 'Feed') {
                        contentWithIcon = '<div class="feed-wrap"><div class="feed-top"><span class="icon">' + platformIcon + '</span><div class="feed-top-info"><h4>' + profileName + '</h4><span>Now </span></div></div><div class="feed-middle"><p class="template-preview-content">What\'s in your mind</p></div><div class="template-preview-media feed-media" style="display:none;"></div><div class="feed-bottom"><ul class="feed-action-lsit"><li><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.5835 9.89583C1.5835 9.02136 2.29238 8.3125 3.16683 8.3125C4.4785 8.3125 5.54183 9.37579 5.54183 10.6875V13.8542C5.54183 15.1659 4.4785 16.2292 3.16683 16.2292C2.29238 16.2292 1.5835 15.5203 1.5835 14.6458V9.89583Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M12.2538 6.18012L12.043 6.86102C11.8702 7.41896 11.7839 7.69793 11.8503 7.91825C11.9041 8.09646 12.022 8.25012 12.1828 8.35122C12.3815 8.47614 12.6821 8.47614 13.2833 8.47614H13.6031C15.6378 8.47614 16.6551 8.47614 17.1356 9.07844C17.1905 9.14724 17.2393 9.22047 17.2816 9.29726C17.6512 9.96891 17.231 10.8738 16.3905 12.6835C15.6192 14.3443 15.2335 15.1747 14.5174 15.6634C14.4482 15.7107 14.3769 15.7554 14.3039 15.7972C13.55 16.2293 12.616 16.2293 10.7478 16.2293H10.3426C8.07935 16.2293 6.94773 16.2293 6.24461 15.5481C5.5415 14.8668 5.5415 13.7703 5.5415 11.5774V10.8067C5.5415 9.65422 5.5415 9.07805 5.74602 8.55064C5.95054 8.02323 6.34216 7.58959 7.12538 6.7223L10.3644 3.13561C10.4456 3.04566 10.4863 3.00068 10.5221 2.96951C10.8564 2.67859 11.3723 2.71134 11.6646 3.04202C11.6959 3.07745 11.7301 3.12718 11.7985 3.22663C11.9056 3.3822 11.9591 3.45999 12.0058 3.53705C12.4233 4.22697 12.5497 5.04653 12.3584 5.82457C12.337 5.91148 12.3093 6.00108 12.2538 6.18012Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></li><li><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.50344 9.5H9.51056M12.6665 9.5H12.6737M6.34033 9.5H6.34743" stroke="#141B34" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /><path d="M17.0207 9.49984C17.0207 13.6535 13.6535 17.0207 9.49984 17.0207C8.21093 17.0207 6.9977 16.6964 5.93734 16.1251C4.45832 15.3281 3.46308 16.069 2.58536 16.2019C2.45221 16.2221 2.31961 16.1737 2.2244 16.0786C2.07987 15.934 2.05236 15.7105 2.13219 15.5224C2.47669 14.7104 2.793 13.1717 2.3617 11.8748C2.11343 11.1283 1.979 10.3297 1.979 9.49984C1.979 5.34619 5.34619 1.979 9.49984 1.979C13.6535 1.979 17.0207 5.34619 17.0207 9.49984Z" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></li><li><svg width="19" height="19" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12.6665 7.9165C13.7749 7.9165 14.3292 7.9165 14.7525 8.13223C15.1249 8.322 15.4277 8.62473 15.6174 8.99713C15.8332 9.42051 15.8332 9.97476 15.8332 11.0832V13.4582C15.8332 15.3241 15.8332 16.2571 15.2535 16.8368C14.6738 17.4165 13.7408 17.4165 11.8748 17.4165H7.12484C5.25886 17.4165 4.32588 17.4165 3.74619 16.8368C3.1665 16.2571 3.1665 15.3241 3.1665 13.4582V11.0832C3.1665 9.97476 3.1665 9.42051 3.38222 8.99713C3.57197 8.62473 3.87474 8.322 4.24714 8.13223C4.67051 7.9165 5.22473 7.9165 6.33317 7.9165" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M9.5 12.6665V3.1665" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /><path d="M11.875 4.74998C11.875 4.74998 10.1258 2.37501 9.5 2.375C8.87411 2.37499 7.125 4.75 7.125 4.75" stroke="#141B34" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" /></svg></li></ul></div></div>';
                    } else if (postType === 'Story') {
                        contentWithIcon = '<div class="reels-area story-area"><div class="story-top"><div class="progress" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="height: 2px"><div class="progress-bar" style="width: 25%"></div></div></div><div class="author-area"><span class="icon">' + platformIcon + '</span><div class="author-info"><h4>' + profileName + '</h4><span>Now </span></div></div><div class="story-content-center" style="display:flex;align-items:center;justify-content:center;min-height:140px;padding:12px;text-align:center"><p class="template-preview-content mb-0" style="font-size:16px;color:#ffffff !important;line-height:1.4;text-shadow:0 1px 4px rgba(0,0,0,1), 0 0 12px rgba(0,0,0,0.6)"></p></div><div class="template-preview-media story-preview-media" style="display:none;"></div><div class="no-media"><svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="50" height="50" rx="25" fill="#FF4F02" /><path d="M31 25L22 30.1962L22 19.8038L31 25Z" fill="white" /></svg><h4>No Media Selected</h4></div></div>';
                    } else {
                        contentWithIcon = tabContentTemplates[postType] || tabContentTemplates['Reels'];
                    }

                    tabContentContainer.append(
                        '<div class="tab-pane fade ' + showClass + '" id="' + tabPaneId + '" role="tabpanel" aria-labelledby="' + tabId + '" tabindex="0">' + contentWithIcon + '</div>'
                    );
                });

                $('#post_type').val(postTypes[0]);

                var $upload = $('#directMediaUpload');
                if ($upload.length) $upload.attr('accept', (postTypes[0] === 'Reels' || postTypes[0] === 'Shorts') ? 'video/*' : 'image/*,video/*');
                syncContentToPreview();
                syncMediaToPreview();
            }

            $('.platform-filter').on('change', function () {
                if ($(this).is(':checked')) {
                    $('.platform-filter').not(this).prop('checked', false);
                    $('.accounts-list input[type="checkbox"]').prop('checked', false);
                }

                var selectedPlatform = $('.platform-filter:checked').val() || '';
                $('#selected_platforms').val(selectedPlatform);

                var $templatePlatformFilter = $('#templatePlatformFilter');
                if ($templatePlatformFilter.length) {
                    $templatePlatformFilter.val(selectedPlatform);
                    if (typeof filterTemplateCards === 'function') {
                        filterTemplateCards();
                    }
                }

                filterAccounts();
                updatePostTypeTabs();
            });

            filterAccounts();
            updatePostTypeTabs();

            var selectedPlatform = $('.platform-filter:checked').val() || '';
            if (!selectedPlatform) {
                selectedPlatform = $('#selected_platforms').val() || '';
            }
            if (selectedPlatform) {
                $('.platform-filter[value="' + selectedPlatform + '"]').prop('checked', true);
            }
            $('#selected_platforms').val(selectedPlatform);

            $('#search').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('.accounts-list li').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $(document).on('shown.bs.tab', '#Post button[data-bs-toggle="tab"], .post-tabs button[data-bs-toggle="tab"]', function () {
                var tabId = $(this).attr('id');
                var postType = (tabId || '').replace('-tab', '') || 'Feed';

                $('#post_type').val(postType);

                var $upload = $('#directMediaUpload');
                if ($upload.length) $upload.attr('accept', (postType === 'Reels' || postType === 'Shorts') ? 'video/*' : 'image/*,video/*');
                syncContentToPreview();
                syncMediaToPreview();
            });

            $('#saveTemplateCheckbox').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#TemplateSaveModal').modal('show');
                }
            });
        }

        $('#uploadMediaBtn').on('click', function (e) {
            e.preventDefault();
            $('#directMediaUpload').trigger('click');
        });

        $('#directMediaUpload').on('change', function (e) {
            var files = Array.from(e.target.files);
            if (files.length === 0) return;

            var videoOnly = typeof isVideoOnlyPostType === 'function' && isVideoOnlyPostType();
            var postType = typeof getCurrentPostType === 'function' ? getCurrentPostType() : 'Feed';

            if (videoOnly) {
                files = files.filter(function (f) { return f.type.startsWith('video/'); });
                if (files.length === 0) {
                    if (typeof toastr !== 'undefined') toastr.warning('Reels, Story and Video support one video only. Please upload a video.');
                    $(this).val('');
                    return;
                }
                files = files.slice(0, 1);
                uploadedFiles = uploadedFiles.filter(function (f) { return f.type !== 'video'; });
            } else if (postType === 'Feed') {
                var hasImage = files.some(function (f) { return f.type.startsWith('image/'); });
                var hasVideo = files.some(function (f) { return f.type.startsWith('video/'); });
                if (hasImage && hasVideo) {
                    if (typeof toastr !== 'undefined') toastr.warning('Feed post allows multiple images OR one video, not both. Please select only images or only one video.');
                    $(this).val('');
                    return;
                }
                if (hasVideo) {
                    files = files.filter(function (f) { return f.type.startsWith('video/'); }).slice(0, 1);
                    selectedImages = [];
                    selectedVideos = [];
                    uploadedFiles = uploadedFiles.filter(function (f) { return f.type !== 'video'; });
                } else {
                    selectedVideos = [];
                    uploadedFiles = uploadedFiles.filter(function (f) { return f.type !== 'video'; });
                }
            }

            files.forEach(function (file) {
                var fileId = Date.now() + Math.random().toString(36).substr(2, 9);
                var fileType = file.type.startsWith('image/') ? 'image' : 'video';
                if (videoOnly && fileType !== 'video') return;
                var fileUrl = URL.createObjectURL(file);

                uploadedFiles.push({
                    id: fileId,
                    url: fileUrl,
                    type: fileType,
                    file: file
                });
            });

            if (postType === 'Feed' && files.some(function (f) { return f.type.startsWith('video/'); })) {
                $('.gallery-item-select').removeClass('selected').find('.select-icon').hide();
                selectedImages = [];
                selectedVideos = [];
            }
            updateSelectionCount();
            syncMediaToPreview();
            $(this).val('');
        });

        $(document).on('click', '.remove-uploaded-media', function () {
            const id = $(this).data('id');
            const fileObj = uploadedFiles.find(f => f.id === id);
            if (fileObj) {
                URL.revokeObjectURL(fileObj.url);
                uploadedFiles = uploadedFiles.filter(f => f.id !== id);
            }
            $(this).closest('.selected-media-item').remove();
            syncMediaToPreview();
        });

        $(document).on('click', '.preview-media-remove', function (e) {
            e.preventDefault();
            var $item = $(this).closest('.preview-media-item');
            var id = $item.data('id');
            var type = $item.data('type');
            var source = $item.data('source');

            if (source === 'gallery') {
                if (type === 'image') {
                    selectedImages = selectedImages.filter(function (img) { return String(img.id) !== String(id); });
                    $('[data-type="image"][data-id="' + id + '"]').removeClass('selected').find('.select-icon').hide();
                } else {
                    selectedVideos = selectedVideos.filter(function (vid) { return String(vid.id) !== String(id); });
                    $('[data-type="video"][data-id="' + id + '"]').removeClass('selected').find('.select-icon').hide();
                }
                updateSelectionCount();
            } else if (source === 'upload') {
                var fileObj = uploadedFiles.find(function (f) { return String(f.id) === String(id); });
                if (fileObj) {
                    URL.revokeObjectURL(fileObj.url);
                    uploadedFiles = uploadedFiles.filter(function (f) { return f.id !== fileObj.id; });
                }
            }
            syncMediaToPreview();
        });

        if (window.existingMedia) {
            selectedImages = window.existingMedia.images || [];
            selectedVideos = window.existingMedia.videos || [];
            updateSelectionCount();
        }

        $('#applyGallerySelection').on('click', function () {
            $('#selected_gallery_image_ids').val(selectedImages.map(img => img.id).join(','));
            $('#selected_gallery_video_ids').val(selectedVideos.map(vid => vid.id).join(','));

            syncMediaToPreview();

            var galleryOffcanvas = document.getElementById('GalleryModal');
            var bsOffcanvas = bootstrap.Offcanvas.getInstance(galleryOffcanvas);
            if (bsOffcanvas) {
                bsOffcanvas.hide();
            }
        });

        $(document).on('click', '.remove-selected-media', function () {
            const id = $(this).data('id');
            const type = $(this).data('type');

            if (type === 'image') {
                selectedImages = selectedImages.filter(img => img.id != id);
            } else {
                selectedVideos = selectedVideos.filter(vid => vid.id != id);
            }

            $(this).closest('.selected-media-item').remove();
            updateSelectionCount();
            $(`.gallery-item-select[data-id="${id}"][data-type="${type}"]`).find('.select-icon').hide();
            $(`.gallery-item-select[data-id="${id}"][data-type="${type}"]`).removeClass('selected');
            $('#selected_gallery_image_ids').val(selectedImages.map(img => img.id).join(','));
            $('#selected_gallery_video_ids').val(selectedVideos.map(vid => vid.id).join(','));
            syncMediaToPreview();
        });

        $(document).on('click', '.bottom-tag', function () {
            var tag = $(this).data('tag');
            var hashtagWithHash = '#' + tag;

            var contentField = $('#content');
            var currentContent = contentField.val();

            if (currentContent.length > 0) {
                contentField.val(currentContent + ' ' + hashtagWithHash);
            } else {
                contentField.val(hashtagWithHash);
            }
            syncContentToPreview();

            var hashtagModal = document.getElementById('HashtagModal');
            var bsModal = bootstrap.Modal.getInstance(hashtagModal);
            if (bsModal) {
                bsModal.hide();
            }
        });

        $('#saveHashtagBtn').on('click', function () {
            var hashtagInput = $('#customHashtagInput');
            var hashtagName = hashtagInput.val().trim();

            if (!hashtagName) {
                toastr.error('Please enter a hashtag');
                return;
            }

            if (hashtagName.startsWith('#')) {
                hashtagName = hashtagName.substring(1);
            }

            if (hashtagName.length === 0) {
                toastr.error('Please enter a valid hashtag');
                return;
            }

            $('#hashtagName').val(hashtagName);

            var form = $('#hashtagStoreForm');
            var formData = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        hashtagInput.val('');
                        $(document).trigger('hashtagsReload');
                        $(document).one('hashtagsReloadDone', function () {
                            var hashtagModal = document.getElementById('HashtagModal');
                            var bsModal = bootstrap.Modal.getInstance(hashtagModal);
                            if (bsModal) bsModal.hide();
                        });
                    } else {
                        toastr.error(response.message || 'Failed to create hashtag');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors.name) {
                            toastr.error(errors.name[0]);
                        } else {
                            toastr.error('Validation error');
                        }
                    } else {
                        toastr.error('Failed to create hashtag. Please try again.');
                    }
                }
            });
        });

        $('#insertLinkBtn').on('click', function () {
            var url = $('#URL').val().trim();

            if (!url) {
                toastr.warning('Please enter a URL');
                return;
            }

            if (!url.startsWith('http://') && !url.startsWith('https://')) {
                url = 'https://' + url;
            }

            var contentEditor = $('#content');
            var currentContent = contentEditor.val();

            if (currentContent) {
                currentContent += '\n' + url;
            } else {
                currentContent = url;
            }

            contentEditor.val(currentContent);
            syncContentToPreview();

            $('#URL').val('');
            $('#InsertLinkModal').modal('hide');

            toastr.success('Link added to content');
        });

        $('#saveTemplateBtn').on('click', function () {
            var templateTitle = $('#template_title').val().trim();

            if (!templateTitle) {
                toastr.error('Please enter a template title');
                return;
            }

            var currentPostType = getCurrentPostType();

            var content = $('#content').val();
            var selectedPlatform = $('.platform-filter:checked').val() || '';
            var categoryId = $('#template_category_id').val() || '';
            var status = $('#template_status_select').val() || 'active';

            var formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('title', templateTitle);
            formData.append('content', content);
            formData.append('post_type', currentPostType);
            formData.append('platform', selectedPlatform);
            formData.append('category_id', categoryId);
            formData.append('status', status);
            formData.append('gallery_image_ids', selectedImages.map(img => img.id).join(','));
            formData.append('gallery_video_ids', selectedVideos.map(vid => vid.id).join(','));

            if (typeof uploadedFiles !== 'undefined' && uploadedFiles.length > 0) {
                uploadedFiles.forEach(function (item) {
                    formData.append('direct_media[]', item.file);
                });
            }

            var saveBtn = $(this);
            var originalBtnText = saveBtn.html();
            saveBtn.prop('disabled', true).html('Saving...');

            var saveUrl = saveBtn.data('url') || '/autopost/admin/templates/store';

            $.ajax({
                url: saveUrl,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message || 'Template saved successfully');
                        $('#TemplateSaveModal').modal('hide');
                        $('#template_title').val('');
                        saveBtn.prop('disabled', false).html(originalBtnText);
                    } else {
                        toastr.error(response.message || 'Failed to save template');
                        saveBtn.prop('disabled', false).html(originalBtnText);
                    }
                },
                error: function (xhr) {
                    saveBtn.prop('disabled', false).html(originalBtnText);
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(key + ': ' + value[0]);
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong! Error: ' + xhr.statusText);
                    }
                }
            });
        });

        window.loadTemplateIntoPost = function (template) {
            if (!template) return;

            $('#content').val(template.content || '');
            syncContentToPreview();

            var pt = (template.post_type || 'Feed').toLowerCase();
            var postType = 'feed';
            var tabName = 'Feed';
            if (pt === 'feed') {
                postType = 'feed';
                tabName = 'Feed';
            } else if (pt === 'reels' || pt === 'shorts') {
                postType = 'reels';
                tabName = 'Reels';
            } else if (pt === 'story') {
                postType = 'story';
                tabName = 'Story';
            }

            var platformChanged = false;
            if (template.platform) {
                var platformValue = String(template.platform);
                var platformName = null;

                if (/^\d+$/.test(platformValue)) {
                    if (platformKeyToName && platformKeyToName[platformValue]) {
                        platformName = platformKeyToName[platformValue];
                    }
                    if (!platformName) {
                        $('.platform-filter').each(function () {
                            var checkboxVal = $(this).val();
                            var checkboxDataPlatform = $(this).data('platform');
                            if (String(checkboxDataPlatform) === platformValue ||
                                (platformKeyToName[checkboxVal] && platformKeyToName[checkboxVal] === platformKeyToName[platformValue])) {
                                platformName = checkboxVal;
                            }
                        });
                    }
                } else {
                    platformName = platformValue.toLowerCase();
                }

                if (platformName) {
                    var currentPlatform = $('.platform-filter:checked').val();

                    if (currentPlatform !== platformName) {
                        $('.platform-filter').prop('checked', false);
                        var $platformCheckbox = $('.platform-filter[value="' + platformName + '"]');

                        if ($platformCheckbox.length) {
                            $platformCheckbox.prop('checked', true);
                            platformChanged = true;
                            $platformCheckbox.trigger('change');
                        } else {
                            var $platformByData = $('.platform-filter[data-platform="' + platformName + '"]');
                            if ($platformByData.length) {
                                $platformByData.prop('checked', true);
                                platformChanged = true;
                                $platformByData.trigger('change');
                            }
                        }
                    }
                }
            }

            selectedImages = [];
            selectedVideos = [];
            uploadedFiles.forEach(function (item) {
                if (item.url) URL.revokeObjectURL(item.url);
            });
            uploadedFiles = [];

            if (template.media && template.media.length) {
                template.media.forEach(function (m) {
                    if (m.id && String(m.id).startsWith('uploaded')) {
                        if (m.type === 'image') {
                            selectedImages.push({ id: m.id, url: m.url });
                        } else {
                            selectedVideos.push({ id: m.id, url: m.url });
                        }
                        return;
                    }

                    if (m.type === 'image') {
                        selectedImages.push({ id: m.id, url: m.url });
                    } else {
                        selectedVideos.push({ id: m.id, url: m.url });
                    }
                });
            } else if (template.gallery_image_ids || template.gallery_video_ids) {
                if (template.gallery_image_ids) {
                    var imageIds = (template.gallery_image_ids || '').split(',').filter(function (id) { return id.trim(); });
                    imageIds.forEach(function (id) {
                        var $galleryItem = $('.gallery-item-select[data-type="image"][data-id="' + id.trim() + '"]');
                        var url = '';
                        if ($galleryItem.length) {
                            url = $galleryItem.data('url') || '';
                        }
                        selectedImages.push({ id: id.trim(), url: url });
                    });
                }
                if (template.gallery_video_ids) {
                    var videoIds = (template.gallery_video_ids || '').split(',').filter(function (id) { return id.trim(); });
                    videoIds.forEach(function (id) {
                        var $galleryItem = $('.gallery-item-select[data-type="video"][data-id="' + id.trim() + '"]');
                        var url = '';
                        if ($galleryItem.length) {
                            url = $galleryItem.data('url') || '';
                        }
                        selectedVideos.push({ id: id.trim(), url: url });
                    });
                }
            }

            if (!selectedImages.length && template.gallery_image_ids) {
                var imageIds = (template.gallery_image_ids || '').split(',').filter(function (id) { return id.trim(); });
                imageIds.forEach(function (id) {
                    selectedImages.push({ id: id.trim(), url: '' });
                });
            }
            if (!selectedVideos.length && template.gallery_video_ids) {
                var videoIds = (template.gallery_video_ids || '').split(',').filter(function (id) { return id.trim(); });
                videoIds.forEach(function (id) {
                    selectedVideos.push({ id: id.trim(), url: '' });
                });
            }

            $('#selected_gallery_image_ids').val(selectedImages.map(function (img) { return img.id; }).join(','));
            $('#selected_gallery_video_ids').val(selectedVideos.map(function (vid) { return vid.id; }).join(','));

            $('.gallery-item-select').removeClass('selected').find('.select-icon').hide();
            selectedImages.forEach(function (img) {
                $('.gallery-item-select[data-type="image"][data-id="' + img.id + '"]').addClass('selected').find('.select-icon').show();
            });
            selectedVideos.forEach(function (vid) {
                $('.gallery-item-select[data-type="video"][data-id="' + vid.id + '"]').addClass('selected').find('.select-icon').show();
            });

            updateSelectionCount();

            var switchToTab = function () {
                $('#post_type').val(postType);

                var tabId = tabName + '-tab';
                var $tab = $('#Post button[data-bs-toggle="tab"][id="' + tabId + '"]');
                if (!$tab.length) {
                    $tab = $('.post-tabs button[data-bs-toggle="tab"][id="' + tabId + '"]');
                }
                if (!$tab.length) {
                    $tab = $('button[data-bs-toggle="tab"][id="' + tabId + '"]');
                }

                if ($tab.length) {
                    $tab.tab('show');

                    $tab.one('shown.bs.tab', function () {
                        syncMediaToPreview();
                        syncContentToPreview();
                        var $upload = $('#directMediaUpload');
                        if ($upload.length) {
                            $upload.attr('accept', (postType === 'reels') ? 'video/*' : 'image/*,video/*');
                        }
                    });

                    if ($tab.hasClass('active')) {
                        syncMediaToPreview();
                        syncContentToPreview();
                        var $upload = $('#directMediaUpload');
                        if ($upload.length) {
                            $upload.attr('accept', (postType === 'reels') ? 'video/*' : 'image/*,video/*');
                        }
                    }
                } else {
                    syncMediaToPreview();
                    syncContentToPreview();
                }
            };

            if (platformChanged) {
                setTimeout(function () {
                    switchToTab();
                    setTimeout(function () {
                        syncMediaToPreview();
                    }, 200);
                }, 100);
            } else {
                switchToTab();
            }

            setTimeout(function () {
                syncMediaToPreview();
            }, 300);

            if (typeof toastr !== 'undefined') {
                toastr.success('Template loaded successfully');
            }
        };

        $(document).on('submit', '#scheduledPostForm, form[action*="all-posts.store"], form[action*="all-posts"][action*="edit"]', function (e) {
            var imageIds = selectedImages.map(function (img) { return img.id; }).join(',');
            var videoIds = selectedVideos.map(function (vid) { return vid.id; }).join(',');

            $('#selected_gallery_image_ids').val(imageIds);
            $('#selected_gallery_video_ids').val(videoIds);

            var selectedAccounts = $('input[name="account_ids[]"]:checked').length;
            if (selectedAccounts === 0) {
                e.preventDefault();
                if (typeof toastr !== 'undefined') toastr.error('Please select at least one social media account.');
                return false;
            }

            var isPublishNow = $('#publish_now_field').val() === '1';
            if (isPublishNow) {
                var _cfg = document.getElementById('post-config');
                if (_cfg) {
                    var postLimitAllowed = _cfg.dataset.postLimitAllowed || _cfg.dataset['post-limit-allowed'] || '1';
                    if (postLimitAllowed === '0') {
                        e.preventDefault();
                        var limitMsg = _cfg.dataset.postLimitMessage || _cfg.dataset['post-limit-message'] || 'You have reached your post limit. Please upgrade your package.';
                        if (typeof toastr !== 'undefined') toastr.error(limitMsg);
                        return false;
                    }

                    var allowedProviders = [];
                    try {
                        allowedProviders = JSON.parse(_cfg.dataset.allowedProviders || _cfg.dataset['allowed-providers'] || '[]');
                    } catch (ex) { allowedProviders = []; }

                    if (allowedProviders.length > 0) {
                        var blockedPlatforms = [];
                        $('input[name="account_ids[]"]:checked').each(function () {
                            var platform = ($(this).data('platform') || '').toString().toLowerCase();
                            if (platform && allowedProviders.indexOf(platform) === -1) {
                                if (blockedPlatforms.indexOf(platform) === -1) {
                                    blockedPlatforms.push(platform);
                                }
                            }
                        });
                        if (blockedPlatforms.length > 0) {
                            e.preventDefault();
                            var names = blockedPlatforms.map(function (p) { return p.charAt(0).toUpperCase() + p.slice(1); }).join(', ');
                            if (typeof toastr !== 'undefined') toastr.error('Your plan does not include: ' + names + '. Please upgrade or deselect those accounts.');
                            return false;
                        }
                    }
                }
            }

            var postType = ($('#post_type').val() || 'feed').toLowerCase();
            if ((postType === 'reels' || postType === 'reel' || postType === 'video' || postType === 'story') &&
                selectedImages.length === 0 && selectedVideos.length === 0 && uploadedFiles.length === 0) {
                e.preventDefault();
                if (typeof toastr !== 'undefined') toastr.error('Please select one video for Reels, Story or Video posts.');
                return false;
            }
            if (postType === 'feed') {
                var hasImages = selectedImages.length > 0 || uploadedFiles.some(function (f) { return f.type === 'image'; });
                var hasVideo = selectedVideos.length > 0 || uploadedFiles.some(function (f) { return f.type === 'video'; });
                if (hasImages && hasVideo) {
                    e.preventDefault();
                    if (typeof toastr !== 'undefined') toastr.error('Feed post allows multiple images OR one video only, not both. Please use either images or one video.');
                    return false;
                }
            }

            var fileInput = document.getElementById('directMediaUpload');
            if (fileInput && uploadedFiles.length > 0) {
                try {
                    var dt = new DataTransfer();
                    uploadedFiles.forEach(function (item) { dt.items.add(item.file); });
                    fileInput.files = dt.files;
                } catch (err) {
                }
            }

            showCreatePostLoading();
        });

        function showCreatePostLoading(title, subtitle) {
            $('#create-post-loading-overlay').remove();

            var loadingTitle = title || 'Creating post';
            var loadingSubtitle = subtitle || 'Please wait while we create your post.';

            var loadingHtml = '<div id="create-post-loading-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.7);z-index:9999;display:flex;flex-direction:column;align-items:center;justify-content:center;">' +
                '<div style="background:#fff;padding:30px 50px;border-radius:10px;text-align:center;box-shadow:0 4px 15px rgba(0,0,0,0.3);">' +
                '<h3 style="margin:0;font-size:18px;color:#333;font-weight:600;">' + loadingTitle + '</h3>' +
                '<p style="margin:10px 0 20px;color:#666;font-size:14px;">' + loadingSubtitle + '</p>' +
                '<svg class="loading-spinner" width="40" height="40" viewBox="0 0 50 50" style="animation:spin 1s linear infinite;">' +
                '<circle cx="25" cy="25" r="20" fill="none" stroke="#ddd" stroke-width="5"></circle>' +
                '<circle cx="25" cy="25" r="20" fill="none" stroke="#FF4F02" stroke-width="5" stroke-dasharray="90" stroke-dashoffset="60" stroke-linecap="round"></circle>' +
                '</svg>' +
                '</div>' +
                '</div>' +
                '<style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>';

            $('body').append(loadingHtml);

            $('#create-post-loading-overlay').fadeIn(200);
        }

        window.AppLoading = {
            show: function(title, subtitle) {
                showCreatePostLoading(title, subtitle);
            },
            hide: function() {
                $('#create-post-loading-overlay').fadeOut(200);
            }
        };

        var postConfig = {};
        var _configEl = document.getElementById('post-config');
        if (_configEl) {
            var _ds = _configEl.dataset;
            postConfig = {
                templateListUrl: _ds.templateListUrl || _ds['template-list-url'] || '',
                hashtagsIndexUrl: _ds.hashtagsUrl || _ds['hashtags-url'] || '',
                platformKeyToName: JSON.parse(_ds.platformKeyMap || _ds['platform-key-map'] || '{}'),
                isEditPage: _ds.isEdit === 'true',
                postType: _ds.postType || _ds['post-type'] || 'Feed',
                platform: _ds.platform || '',
                scheduledTime: _ds.scheduledTime || _ds['scheduled-time'] || '',
                galleryImageIds: _ds.galleryImageIds || _ds['gallery-image-ids'] || '',
                galleryVideoIds: _ds.galleryVideoIds || _ds['gallery-video-ids'] || '',
            };
        }
        var templateListUrl = postConfig.templateListUrl || '';
        var hashtagsIndexUrl = postConfig.hashtagsIndexUrl || '';
        var platformKeyToName = postConfig.platformKeyToName || {};

        var templateCache = [];
        var filteredTemplates = [];

        function loadTemplatesFromApi() {
            var $container = $('#templateListContainer');
            $container.html('<div class="col-12 text-center"><p class="text-muted">Loading templates...</p></div>');

            $.ajax({
                url: templateListUrl || '',
                type: 'GET',
                success: function (response) {
                    if (response && response.templates && response.templates.length > 0) {
                        templateCache = response.templates;
                        filteredTemplates = templateCache;
                        filterTemplateCards();
                    } else {
                        $container.html('<div class="col-12 text-center"><p class="text-muted">No templates found.</p></div>');
                    }
                },
                error: function (xhr, status, error) {
                    $container.html('<div class="col-12 text-center"><p class="text-danger">Failed to load templates. Error: ' + error + '</p></div>');
                }
            });
        }

        function renderTemplateCards(templates) {
            var $container = $('#templateListContainer');
            if (!templates || templates.length === 0) {
                $container.html('<div class="col-12 text-center"><p class="text-muted">No templates found.</p></div>');
                return;
            }
            var html = '';
            templates.forEach(function (template) {
                var platformLabel = template.platform_label || '—';
                var mediaPreview = '';
                if (template.media && template.media.length > 0) {
                    var firstMedia = template.media[0];
                    if (firstMedia.type === 'image') {
                        mediaPreview = '<img src="' + firstMedia.url + '" alt="Template" style="width:100%;height:120px;object-fit:cover;border-radius:8px;">';
                    } else {
                        mediaPreview = '<video src="' + firstMedia.url + '" style="width:100%;height:120px;object-fit:cover;border-radius:8px;" muted></video>';
                    }
                } else {
                    mediaPreview = '<div style="width:100%;height:120px;background:#f0f0f0;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#999;">No Media</div>';
                }
                html += '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 template-card-item"' +
                    ' data-template-id="' + template.id + '"' +
                    ' data-platform="' + (template.platform || '') + '"' +
                    ' style="cursor:pointer;">' +
                    '<div class="single-card-image"' +
                    ' style="border:1px solid #dee2e6;border-radius:8px;overflow:hidden;transition:all 0.3s;"' +
                    ' onmouseover="this.style.borderColor=\'#4CAF50\';this.style.boxShadow=\'0 2px 8px rgba(76,175,80,0.2)\'"' +
                    ' onmouseout="this.style.borderColor=\'#dee2e6\';this.style.boxShadow=\'none\'">' +
                    '<div class="image-wrapper">' + mediaPreview + '</div>' +
                    '<div class="image-info p-3">' +
                    '<h5 class="mb-1" style="font-size:14px;font-weight:600;">' + (template.title || 'Untitled') + '</h5>' +
                    '<div class="d-flex justify-content-between align-items-center mt-2">' +
                    '<span class="badge bg-primary" style="font-size:11px;">' + platformLabel + '</span>' +
                    '<span class="badge bg-secondary" style="font-size:11px;">' + (template.post_type || 'Feed') + '</span>' +
                    '</div></div></div></div>';
            });
            $container.html(html);
        }

        function filterTemplateCards() {
            var searchTerm = $('#templateSearch').val().toLowerCase();
            var platformFilter = $('#templatePlatformFilter').val();
            filteredTemplates = templateCache.filter(function (template) {
                var matchesSearch = !searchTerm || (template.title || '').toLowerCase().indexOf(searchTerm) !== -1;
                var templatePlatform = (template.platform || '').toLowerCase();
                var filterPlatform = platformFilter ? platformFilter.toLowerCase() : '';
                var platformKeyMap = { 1: 'facebook', 2: 'twitter', 3: 'youtube', 4: 'linkedin', 5: 'instagram', 6: 'tiktok', 7: 'threads' };
                var filterPlatformNormalized = platformKeyMap[filterPlatform] || filterPlatform;
                var matchesPlatform = !platformFilter || templatePlatform === filterPlatformNormalized;
                return matchesSearch && matchesPlatform;
            });
            renderTemplateCards(filteredTemplates);
        }

        if ($('#SelectTemplateOffcanvas').length) {
            $('#SelectTemplateOffcanvas').on('show.bs.offcanvas', function () {
                if (templateCache.length === 0) {
                    loadTemplatesFromApi();
                } else {
                    filterTemplateCards();
                }
            });

            $(document).on('click', '.template-card-item', function () {
                var templateId = $(this).data('template-id');
                var template = templateCache.find(function (t) { return t.id == templateId; });
                if (template && typeof window.loadTemplateIntoPost === 'function') {
                    window.loadTemplateIntoPost(template);
                    var offcanvasEl = document.getElementById('SelectTemplateOffcanvas');
                    var bsOffcanvas = offcanvasEl ? bootstrap.Offcanvas.getInstance(offcanvasEl) : null;
                    if (bsOffcanvas) bsOffcanvas.hide();
                }
            });

            $('#templateSearch').on('keyup', filterTemplateCards);
            $('#templatePlatformFilter').on('change', filterTemplateCards);
        }

        if (postConfig.isEditPage) {

            if (postConfig.scheduledTime) {
                var timeParts = postConfig.scheduledTime.split(' ');
                var dateStr = timeParts[0];
                var timeStr = timeParts[1] ? timeParts[1].substring(0, 5) : '12:00';
                $('#custom-time').val(timeStr);
                $('#display-date').html('<strong>' + dateStr + ' at ' + timeStr + '</strong>');
            }

            if (postConfig.galleryImageIds || postConfig.galleryVideoIds) {
                var imgIds = (postConfig.galleryImageIds || '').split(',').filter(Boolean);
                var vidIds = (postConfig.galleryVideoIds || '').split(',').filter(Boolean);
                imgIds.forEach(function (id) {
                    var $item = $('.gallery-item-select[data-id="' + id + '"][data-type="image"]');
                    if ($item.length) {
                        window.toggleMediaSelection($item[0]);
                    }
                });
                vidIds.forEach(function (id) {
                    var $item = $('.gallery-item-select[data-id="' + id + '"][data-type="video"]');
                    if ($item.length) {
                        window.toggleMediaSelection($item[0]);
                    }
                });
                $('#applyGallerySelection').trigger('click');
            }

            setTimeout(function () {
                var pt = postConfig.postType || 'Feed';
                var $tab = $('#' + pt + '-tab');
                if ($tab.length) {
                    $tab.tab('show');
                    $('#post_type').val(pt);
                }
            }, 600);
        }

    });

})(jQuery);
