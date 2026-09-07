@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Edit Template') }}

@endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __('Edit Template') }}</h2>
</div>
<form action="{{ route('admin.template.update', $template->id) }}" method="POST" id="templateForm"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="create-post-wrap">
        <div class="create-post-left">
            <div class="post-left-top">
                <ul class="post-latform-lsit">
                    @foreach(SOCIAL_MEDIA_PLATFORMS as $platform)
                    <li>
                        <label class="platform-name">
                            <input type="checkbox" class="platform-filter" value="{{ strtolower($platform) }}"
                                data-platform="{{ strtolower($platform) }}"
                                {{ $template->platform == strtolower($platform) ? 'checked' : '' }}>
                            <span class="post-paltform"><span class="icon">
                                    @switch($platform)
                                    @case('Facebook')
                                    <i class="fa-brands fa-facebook-f"></i>
                                    @break
                                    @case('LinkedIn')
                                    <i class="fa-brands fa-linkedin-in"></i>
                                    @break
                                    @case('YouTube')
                                    <i class="fa-brands fa-youtube"></i>
                                    @break
                                    @case('TikTok')
                                    <i class="fa-brands fa-tiktok"></i>
                                    @break
                                    @case('Twitter')
                                    <i class="fa-brands fa-x-twitter"></i>
                                    @break
                                    @case('Instagram')
                                    <i class="fa-brands fa-instagram"></i>
                                    @break
                                    @case('Threads')
                                    <i class="fa-brands fa-threads"></i>
                                    @break
                                    @default
                                    <i class="fa-solid fa-globe"></i>
                                    @endswitch
                                </span>
                                {{ $platform }}</span>
                        </label>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="create-post-center">
            <div class="post-center-top">
                <ul class="nav nav-tabs post-tabs" id="Post" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $template->post_type == 'Feed' ? 'active' : '' }}" id="Feed-tab"
                            data-bs-toggle="tab" data-bs-target="#Feed-tab-pane" type="button" role="tab"
                            aria-controls="Feed-tab-pane" aria-selected="true">Feed</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $template->post_type == 'Reels' ? 'active' : '' }}" id="Reels-tab"
                            data-bs-toggle="tab" data-bs-target="#Reels-tab-pane" type="button" role="tab"
                            aria-controls="Reels-tab-pane" aria-selected="false">Reels</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $template->post_type == 'Story' ? 'active' : '' }}" id="Story-tab"
                            data-bs-toggle="tab" data-bs-target="#Story-tab-pane" type="button" role="tab"
                            aria-controls="Story-tab-pane" aria-selected="false">Story</button>
                    </li>
                </ul>
                <div class="post-content">
                    <div id="emoji-picker-container" style="position: absolute; display: none; z-index: 1000;">
                    </div>
                    <textarea class="post-content-box" name="content" id="content"
                        placeholder="Start from here...">{{ old('content', $template->content) }}</textarea>
                </div>
            </div>
            <input type="hidden" name="post_type" id="template_post_type"
                value="{{ old('post_type', $template->post_type) }}">
            <div class="post-center-footer">
                <div class="post-footer-top">
                    <div class="post-footer-left">
                        <ul class="post-actions">
                            <li class="dropdown">
                                <a href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.73 6.7622H6.83102V11.7302H4.87602V6.7622H2.35513e-05V4.9912H4.87602V0.000201941H6.83102V4.9912H11.73V6.7622Z"
                                            fill="black" />
                                    </svg>
                                </a>
                                <ul class="dropdown-menu media-dropdown">
                                    <li>
                                        <a class="dropdown-item" href="#" data-bs-toggle="offcanvas"
                                            data-bs-target="#GalleryModal">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2.25" y="2.25" width="13.5" height="13.5" rx="1.5"
                                                    stroke="currentColor" stroke-width="1.5" />
                                                <circle cx="6.25" cy="6.25" r="1.25" fill="currentColor" />
                                                <path d="M15.75 11.25L12 7.5L6.75 12.75" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            Open Gallery
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#" id="uploadMediaBtn">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M15.75 11.25V14.25C15.75 15.0784 15.0784 15.75 14.25 15.75H3.75C2.92157 15.75 2.25 15.0784 2.25 14.25V11.25"
                                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M12.75 6L9 2.25L5.25 6" stroke="currentColor"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M9 2.25V11.25" stroke="currentColor" stroke-width="1.5"
                                                    stroke-linecap="round" />
                                            </svg>
                                            Upload Media
                                        </a>
                                        <input type="file" id="directMediaUpload" name="direct_media[]"
                                            accept="image/*,video/*" multiple style="display: none;">
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#" id="emoji-trigger"><svg width="18" height="18" viewBox="0 0 18 18"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M9 16.5C13.1421 16.5 16.5 13.1421 16.5 9C16.5 4.85786 13.1421 1.5 9 1.5C4.85786 1.5 1.5 4.85786 1.5 9C1.5 13.1421 4.85786 16.5 9 16.5Z"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M6 11.25C6.68409 12.1608 7.77322 12.75 9 12.75C10.2268 12.75 11.3159 12.1608 12 11.25"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M6.00673 6.75H6M12 6.75H11.9932" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg></a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#HashtagModal"><svg width="18"
                                        height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.5 15.75L13.5 2.25" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.5 15.75L7.5 2.25" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M3.75 6H15.75" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M2.25 12H14.25" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg></a></li>
                            <li><a href="#" data-bs-toggle="modal" data-bs-target="#InsertLinkModal"><svg width="18"
                                        height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.5 9.92175C7.6062 10.0957 7.73227 10.2603 7.87815 10.4121C8.78377 11.3546 10.1641 11.502 11.2182 10.8542C11.4135 10.7341 11.5975 10.5868 11.7654 10.4121L14.1949 7.88355C15.2683 6.76638 15.2683 4.95507 14.1949 3.83789C13.1215 2.7207 11.3811 2.72071 10.3076 3.83789L9.7725 4.39484"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round" />
                                        <path
                                            d="M8.22773 13.605L7.69238 14.1621C6.61895 15.2793 4.87853 15.2793 3.80509 14.1621C2.73164 13.0449 2.73164 11.2336 3.80509 10.1164L6.23465 7.5879C7.3081 6.4707 9.04853 6.4707 10.1219 7.5879C10.2678 7.73962 10.3938 7.90425 10.5 8.0781"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round" />
                                    </svg></a></li>
                        </ul>
                    </div>
                    <div class="post-footer-right">
                        <div class="btn-list mt-0">
                            <a href="javascript:void(0)" id="aiAssistantBtn" class="primary-btn btn-outline-secondary">
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.06229 3.9506L7.48888 5.13529C7.96276 6.45013 8.99816 7.48554 10.313 7.95941L11.4977 8.38601C11.6045 8.42479 11.6045 8.5762 11.4977 8.61445L10.313 9.04104C8.99816 9.51491 7.96276 10.5503 7.48888 11.8652L7.06229 13.0499C7.02351 13.1566 6.8721 13.1566 6.83385 13.0499L6.40726 11.8652C5.93338 10.5503 4.89798 9.51491 3.58313 9.04104L2.39845 8.61445C2.29166 8.57566 2.29166 8.42426 2.39845 8.38601L3.58313 7.95941C4.89798 7.48554 5.93338 6.45013 6.40726 5.13529L6.83385 3.9506C6.8721 3.84329 7.02351 3.84329 7.06229 3.9506Z"
                                        fill="#0D0D0D" />
                                    <path
                                        d="M12.3948 1.10354L12.611 1.70332C12.8511 2.36898 13.3755 2.89332 14.0411 3.13345L14.6409 3.34966C14.6951 3.36932 14.6951 3.44582 14.6409 3.46548L14.0411 3.6817C13.3755 3.92182 12.8511 4.44616 12.611 5.11182L12.3948 5.7116C12.3751 5.76579 12.2986 5.76579 12.279 5.7116L12.0627 5.11182C11.8226 4.44616 11.2983 3.92182 10.6326 3.6817L10.0328 3.46548C9.97864 3.44582 9.97864 3.36932 10.0328 3.34966L10.6326 3.13345C11.2983 2.89332 11.8226 2.36898 12.0627 1.70332L12.279 1.10354C12.2986 1.04882 12.3756 1.04882 12.3948 1.10354Z"
                                        fill="#0D0D0D" />
                                    <path
                                        d="M12.3948 11.2892L12.611 11.889C12.8511 12.5546 13.3755 13.079 14.0411 13.3191L14.6409 13.5353C14.6951 13.555 14.6951 13.6315 14.6409 13.6511L14.0411 13.8673C13.3755 14.1075 13.3755 14.6318 12.611 15.2975L12.3948 15.8972C12.3751 15.9514 12.2986 15.9514 12.279 15.8972L12.0627 15.2975C11.8226 14.6318 11.2983 14.1075 10.6326 13.8673L10.0328 13.6511C9.97864 13.6315 9.97864 13.555 10.0328 13.5353L10.6326 13.3191C11.2983 13.079 11.8226 12.5546 12.0627 11.889L12.279 11.2892C12.2986 11.235 12.3756 11.235 12.3948 11.2892Z"
                                        fill="#0D0D0D" />
                                </svg>
                                AI Assistant</a>
                            <button type="button" class="primary-btn" data-bs-toggle="modal"
                                data-bs-target="#TemplateSaveModal">Update
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M13.1548 1.90808C11.7936 0.442101 1.55405 4.03325 1.56251 5.34437C1.57209 6.83119 5.56131 7.28856 6.667 7.59881C7.33194 7.78531 7.51 7.97656 7.66331 8.67381C8.35769 11.8316 8.70631 13.4022 9.50087 13.4372C10.7674 13.4932 14.4833 3.33875 13.1548 1.90808Z"
                                        stroke="white" stroke-width="1.5" />
                                    <path d="M7.1875 7.8125L9.375 5.625" stroke="white" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="create-post-right">
            <div class="tab-content" id="PostContent">
                <div class="tab-pane fade {{ $template->post_type == 'Feed' ? 'show active' : '' }}" id="Feed-tab-pane"
                    role="tabpanel" aria-labelledby="Feed-tab" tabindex="0">
                    <div class="feed-wrap">
                        <div class="feed-top">
                            <span class="icon"><i class="fa-brands fa-facebook-f"></i></span>
                            <div class="feed-top-info">
                                <h4>Alex Anderson</h4>
                                <span>Now </span>
                            </div>
                        </div>
                        <div class="feed-middle">
                            <p class="template-preview-content">{{ $template->content ?? "What's in your mind" }}</p>
                        </div>
                        <div class="template-preview-media feed-media" style="display: none;"></div>
                        <div class="feed-bottom">
                            <ul class="feed-action-lsit">
                                <li><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.5835 9.89583C1.5835 9.02136 2.29238 8.3125 3.16683 8.3125C4.4785 8.3125 5.54183 9.37579 5.54183 10.6875V13.8542C5.54183 15.1659 4.4785 16.2292 3.16683 16.2292C2.29238 16.2292 1.5835 15.5203 1.5835 14.6458V9.89583Z"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </li>
                                <li><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.50344 9.5H9.51056M12.6665 9.5H12.6737M6.34033 9.5H6.34743"
                                            stroke="#141B34" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M17.0207 9.49984C17.0207 13.6535 13.6535 17.0207 9.49984 17.0207C8.21093 17.0207 6.9977 16.6964 5.93734 16.1251C4.45832 15.3281 3.46308 16.069 2.58536 16.2019C2.45221 16.2221 2.31961 16.1737 2.2244 16.0786C2.07987 15.934 2.05236 15.7105 2.13219 15.5224C2.47669 14.7104 2.793 13.1717 2.3617 11.8748C2.11343 11.1283 1.979 10.3297 1.979 9.49984C1.979 5.34619 5.34619 1.979 9.49984 1.979C13.6535 1.979 17.0207 5.34619 17.0207 9.49984Z"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </li>
                                <li><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12.6665 7.9165C13.7749 7.9165 14.3292 7.9165 14.7525 8.13223C15.1249 8.322 15.4277 8.62473 15.6174 8.99713C15.8332 9.42051 15.8332 9.97476 15.8332 11.0832V13.4582C15.8332 15.3241 15.8332 16.2571 15.2535 16.8368C14.6738 17.4165 13.7408 17.4165 11.8748 17.4165H7.12484C5.25886 17.4165 4.32588 17.4165 3.74619 16.8368C3.1665 16.2571 3.1665 15.3241 3.1665 13.4582V11.0832C3.1665 9.97476 3.1665 9.42051 3.38222 8.99713C3.57197 8.62473 3.87474 8.322 4.24714 8.13223C4.67051 7.9165 5.22473 7.9165 6.33317 7.9165"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M9.5 12.6665V3.1665" stroke="#141B34" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M11.875 4.74998C11.875 4.74998 10.1258 2.37501 9.5 2.375C8.87411 2.37499 7.125 4.75 7.125 4.75"
                                            stroke="#141B34" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{ $template->post_type == 'Reels' ? 'show active' : '' }}"
                    id="Reels-tab-pane" role="tabpanel" aria-labelledby="Reels-tab" tabindex="0">
                    <div class="reels-area">
                        <div class="author-area">
                            <span class="icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="19" height="19" rx="5.75758" fill="#2563EB" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.99623 8.98805C6.52711 8.98805 6.4292 9.08012 6.4292 9.52115V10.3208C6.4292 10.7619 6.52711 10.8539 6.99623 10.8539H8.1303V14.0526C8.1303 14.4936 8.22821 14.5857 8.69733 14.5857H9.8314C10.3005 14.5857 10.3984 14.4936 10.3984 14.0526V10.8539H11.6718C12.0276 10.8539 12.1193 10.7889 12.2171 10.4673L12.4601 9.66763C12.6275 9.11668 12.5243 8.98805 11.9148 8.98805H10.3984V7.65529C10.3984 7.36086 10.6523 7.12218 10.9654 7.12218H12.5793C13.0484 7.12218 13.1464 7.03013 13.1464 6.58907V5.52285C13.1464 5.0818 13.0484 4.98975 12.5793 4.98975H10.9654C9.39963 4.98975 8.1303 6.18315 8.1303 7.65529V8.98805H6.99623Z"
                                        stroke="white" stroke-width="0.863636" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div class="author-info">
                                <h4>Alex Anderson</h4>
                                <span>Now </span>
                            </div>
                        </div>
                        <div class="reels-caption" style="padding: 6px 0; font-size: 14px; color: #333;">
                            <p class="template-preview-content mb-0"></p>
                        </div>
                        <div class="template-preview-media reels-preview-media" style="display: none;"></div>
                        <div class="no-media">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="50" height="50" rx="25" fill="#FF4F02" />
                                <path d="M31 25L22 30.1962L22 19.8038L31 25Z" fill="white" />
                            </svg>
                            <h4>No Media Selected</h4>
                        </div>
                        <ul class="reels-options">
                            <li><span class="icon"><svg width="27" height="28" viewBox="0 0 27 28" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="27" height="27.9167" rx="13.5" fill="#333333" />
                                        <path
                                            d="M12.5732 18.6062C10.9275 17.3755 7.66699 14.562 7.66699 12.0301C7.66699 10.3566 8.89506 9 10.5837 9C11.4587 9 12.3337 9.29167 13.5003 10.4583C14.667 9.29167 15.542 9 16.417 9C18.1056 9 19.3337 10.3566 19.3337 12.0301C19.3337 14.562 16.0732 17.3755 14.4274 18.6062C13.8736 19.0203 13.1271 19.0203 12.5732 18.6062Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span> 40K</li>
                            <li><span class="icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="28" height="28" rx="14" fill="#333333" />
                                        <path d="M14.0025 14H14.0085M16.6662 14H16.6722M11.3389 14H11.3448"
                                            stroke="white" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M20.3337 13.9998C20.3337 17.4976 17.4981 20.3332 14.0003 20.3332C12.9149 20.3332 11.8933 20.0601 11.0003 19.579C9.75484 18.9078 8.91674 19.5318 8.17761 19.6437C8.06549 19.6607 7.95382 19.62 7.87364 19.5398C7.75193 19.4181 7.72877 19.2299 7.79599 19.0714C8.08609 18.3877 8.35246 17.092 7.98927 15.9998C7.78019 15.3712 7.66699 14.6987 7.66699 13.9998C7.66699 10.502 10.5025 7.6665 14.0003 7.6665C17.4981 7.6665 20.3337 10.502 20.3337 13.9998Z"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span> 1.5K</li>
                            <li><span class="icon"><svg width="28" height="28" viewBox="0 0 28 28" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect width="28" height="28" rx="14" fill="#333333" />
                                        <path
                                            d="M16.667 12.6665C17.6004 12.6665 18.0671 12.6665 18.4237 12.8482C18.7373 13.008 18.9922 13.2629 19.152 13.5765C19.3337 13.933 19.3337 14.3998 19.3337 15.3332V17.3332C19.3337 18.9045 19.3337 19.6902 18.8455 20.1784C18.3573 20.6665 17.5717 20.6665 16.0003 20.6665H12.0003C10.429 20.6665 9.64331 20.6665 9.15515 20.1784C8.66699 19.6902 8.66699 18.9045 8.66699 17.3332V15.3332C8.66699 14.3998 8.66699 13.933 8.84865 13.5765C9.00844 13.2629 9.26341 13.008 9.57701 12.8482C9.93353 12.6665 10.4002 12.6665 11.3337 12.6665"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M14 16.6665V8.6665" stroke="white" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M16 9.99999C16 9.99999 14.527 8.00001 14 8C13.4729 7.99999 12 10 12 10"
                                            stroke="white" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </span> 55</li>
                        </ul>
                        <div class="reels-footer"
                            style="padding: 8px 0 0; border-top: 1px solid rgba(255,255,255,0.2); margin-top: 8px;">
                            <p class="template-preview-content mb-0"
                                style="padding: 8px 8px 0 8px; font-size: 14px; color: #fff; line-height: 1.4;"></p>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade {{ $template->post_type == 'Story' ? 'show active' : '' }}"
                    id="Story-tab-pane" role="tabpanel" aria-labelledby="Story-tab" tabindex="0">
                    <div class="reels-area story-area">
                        <div class="story-top">
                            <div class="progress" role="progressbar" aria-valuenow="25" aria-valuemin="0"
                                aria-valuemax="100" style="height: 2px">
                                <div class="progress-bar" style="width: 25%"></div>
                            </div>
                        </div>
                        <div class="author-area">
                            <span class="icon"><svg width="19" height="19" viewBox="0 0 19 19" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="19" height="19" rx="5.75758" fill="#2563EB" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M6.99623 8.98805C6.52711 8.98805 6.4292 9.08012 6.4292 9.52115V10.3208C6.4292 10.7619 6.52711 10.8539 6.99623 10.8539H8.1303V14.0526C8.1303 14.4936 8.22821 14.5857 8.69733 14.5857H9.8314C10.3005 14.5857 10.3984 14.4936 10.3984 14.0526V10.8539H11.6718C12.0276 10.8539 12.1193 10.7889 12.2171 10.4673L12.4601 9.66763C12.6275 9.11668 12.5243 8.98805 11.9148 8.98805H10.3984V7.65529C10.3984 7.36086 10.6523 7.12218 10.9654 7.12218H12.5793C13.0484 7.12218 13.1464 7.03013 13.1464 6.58907V5.52285C13.1464 5.0818 13.0484 4.98975 12.5793 4.98975H10.9654C9.39963 4.98975 8.1303 6.18315 8.1303 7.65529V8.98805H6.99623Z"
                                        stroke="white" stroke-width="0.863636" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <div class="author-info">
                                <h4>Alex Anderson</h4>
                                <span>Now </span>
                            </div>
                        </div>
                        <div class="story-caption" style="padding: 6px 0; font-size: 14px; color: #333;">
                            <p class="template-preview-content mb-0"></p>
                        </div>
                        <div class="template-preview-media story-preview-media" style="display: none;"></div>
                        <div class="no-media">
                            <svg width="50" height="50" viewBox="0 0 50 50" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect width="50" height="50" rx="25" fill="#FF4F02" />
                                <path d="M31 25L22 30.1962L22 19.8038L31 25Z" fill="white" />
                            </svg>
                            <h4>No Media Selected</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TemplateSaveModal -->
    <div class="modal fade primary-modal" id="TemplateSaveModal" tabindex="-1" aria-labelledby="TemplateSaveModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="TemplateSaveModalLabel">Edit Template</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="primary-form">
                        <input type="hidden" name="gallery_image_ids" id="selected_gallery_image_ids"
                            value="{{ $template->gallery_image_ids }}">
                        <input type="hidden" name="gallery_video_ids" id="selected_gallery_video_ids"
                            value="{{ $template->gallery_video_ids }}">
                        <input type="hidden" name="uploaded_files_data" id="uploaded_files_data">
                        <div class="row gy-4">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title" class="form-label">Template Name<span
                                            class="required">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        placeholder="Enter Template Name" value="{{ old('title', $template->title) }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="status" class="form-label">Status<span class="required">*</span></label>
                                    <select class="select form-control wide" id="status" name="status" required>
                                        <option value="active"
                                            {{ $template->status == STATUS_ACTIVE ? 'selected' : '' }}>Active</option>
                                        <option value="inactive"
                                            {{ $template->status == STATUS_DEACTIVATE ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <input type="hidden" name="platform" id="selected_platforms"
                                value="{{ $template->platform }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="primary-btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="primary-btn" id="saveTemplateSubmitBtn">Update Template</button>
                </div>
            </div>
        </div>
    </div>
</form>


@include('auto_posts.admin.template.modals')

<!-- GalleryModal - Slide in from right -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="GalleryModal" aria-labelledby="GalleryModalLabel">
    <div class="offcanvas-header">
        <h2 class="offcanvas-title" id="GalleryModalLabel">Gallery</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body primary-form">
        <!-- Search and Filter -->
        <div class="search-input-wrap mb-4">
            <label class="icon" for="gallerySearch">
                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                        stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </label>
            <input type="text" class="search-input" id="gallerySearch" placeholder="Search By Title...">
        </div>

        <div class="form-group mb-4">
            <select class="select form-control wide" id="galleryPlatformFilter" name="platform">
                <option value="">{{ __('Select Platform') }}</option>
                @foreach(SOCIAL_MEDIA_PLATFORMS as $key => $platform)
                <option value="{{ $key }}">{{ __($platform) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Gallery Tabs -->
        <ul class="nav nav-tabs post-tabs mb-4 mt-4" id="GalleryTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="All-tab" data-bs-toggle="tab" data-bs-target="#All-tab-pane"
                    type="button" role="tab">All</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="Images-tab" data-bs-toggle="tab" data-bs-target="#Images-tab-pane"
                    type="button" role="tab">Images</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="Videos-tab" data-bs-toggle="tab" data-bs-target="#Videos-tab-pane"
                    type="button" role="tab">Videos</button>
            </li>
        </ul>

        <!-- Gallery Content -->
        <div class="tab-content" id="GalleryTabContent">
            <div class="tab-pane fade show active" id="All-tab-pane" role="tabpanel" tabindex="0">
                <div class="row gy-4">
                    @php
                    $selectedImageIds = explode(',', $template->gallery_image_ids);
                    $selectedVideoIds = explode(',', $template->gallery_video_ids);
                    @endphp
                    @foreach($galleries as $gallery)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image gallery-item-select {{ in_array($gallery->id, $selectedImageIds) ? 'selected' : '' }}"
                            data-id="{{ $gallery->id }}" data-url="{{ asset('storage/' . $gallery->file_path) }}"
                            data-type="image" style="cursor: pointer; position: relative;">
                            <div class="selection-checkbox"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="select-icon"
                                    style="{{ in_array($gallery->id, $selectedImageIds) ? '' : 'display: none;' }}">
                                    <circle cx="12" cy="12" r="11" fill="#4CAF50" stroke="white" stroke-width="2" />
                                    <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="image-wrapper">
                                <img class="logo" src="{{ asset('storage/' . $gallery->file_path) }}"
                                    alt="{{ $gallery->title ?? $gallery->file_name }}" style="pointer-events: none;">
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @foreach($videos as $video)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                        <div class="single-card-image gallery-item-select {{ in_array($video->id, $selectedVideoIds) ? 'selected' : '' }}"
                            data-id="{{ $video->id }}" data-url="{{ asset('storage/' . $video->file_path) }}"
                            data-type="video" style="cursor: pointer; position: relative;">
                            <div class="selection-checkbox"
                                style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg" class="select-icon"
                                    style="{{ in_array($video->id, $selectedVideoIds) ? '' : 'display: none;' }}">
                                    <circle cx="12" cy="12" r="11" fill="#4CAF50" stroke="white" stroke-width="2" />
                                    <path d="M7 12L10.5 15.5L17 9" stroke="white" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                            <div class="image-wrapper"
                                style="background: #000; display: flex; align-items: center; justify-content: center; min-height: 120px;">
                                <video preload="none" class="logo"
                                    style="width: 100%; height: 120px; object-fit: cover; pointer-events: none;">
                                    <source src="{{ asset('storage/' . $video->file_path) }}"
                                        type="{{ $video->file_type }}">
                                </video>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas-footer" style="padding: 15px; border-top: 1px solid #dee2e6; background: #fff;">
        <button type="button" class="primary-btn w-100" id="applyGallerySelection">
            Apply <span class="badge bg-light text-dark ms-2" id="selectionCount">0</span>
        </button>
    </div>
</div>

<div id="template-config" data-is-edit="true" data-post-type="{{ $templateConfig['postType'] }}"
    data-platform="{{ $templateConfig['platform'] }}"
    data-existing-images="{{ json_encode($templateConfig['existingImages']) }}"
    data-existing-videos="{{ json_encode($templateConfig['existingVideos']) }}"
    data-ai-generate-url="{{ route('admin.ai.generate-content.submit') }}"
    data-ai-enabled="{{ ($ai_enabled ?? false) ? '1' : '0' }}" data-ai-upgrade-message="{{ $ai_upgrade_message ?? '' }}"
    style="display:none;"></div>

@endsection

@push('script')
<script src="{{ asset('assets/js/hashtag.js') }}"></script>
<script src="{{ asset('admin/js/template.js') }}?v={{ time() }}"></script>
<script type="module" src="{{ asset('admin/js/emoji-picker.js') }}?v={{ time() }}"></script>
@endpush