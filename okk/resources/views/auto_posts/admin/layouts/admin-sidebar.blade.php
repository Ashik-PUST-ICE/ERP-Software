<aside class="sidebar-area">
    <a class="brand-logo" href="{{ route('admin.dashboard') }}">
        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}">
    </a>
    <div class="menu-wrapr">
        <ul id="metismenu" class="primary-menu metismenu">
            <li class="{{ isset($activeDashboard) && $activeDashboard == 'active' ? 'currrent-menu' : '' }}">
                <a href="{{ route('admin.dashboard') }}" aria-expanded="true">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7 5.83333V4.5C7 3.40417 7 2.85626 6.69733 2.48747C6.64194 2.41997 6.58003 2.35806 6.51253 2.30265C6.14374 2 5.59583 2 4.5 2C3.40417 2 2.85626 2 2.48747 2.30265C2.41997 2.35806 2.35806 2.41997 2.30265 2.48747C2 2.85626 2 3.40417 2 4.5V5.83333C2 6.92913 2 7.47707 2.30265 7.84587C2.35806 7.9134 2.41997 7.97527 2.48747 8.03067C2.85626 8.33333 3.40417 8.33333 4.5 8.33333C5.59583 8.33333 6.14374 8.33333 6.51253 8.03067C6.58003 7.97527 6.64194 7.9134 6.69733 7.84587C7 7.47707 7 6.92913 7 5.83333Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path
                            d="M5.16667 10.333H3.83333C3.36815 10.333 3.13555 10.333 2.94629 10.3904C2.52015 10.5197 2.18668 10.8531 2.05741 11.2793C2 11.4685 2 11.7011 2 12.1663C2 12.6315 2 12.8641 2.05741 13.0534C2.18668 13.4795 2.52015 13.813 2.94629 13.9423C3.13555 13.9997 3.36815 13.9997 3.83333 13.9997H5.16667C5.63185 13.9997 5.86445 13.9997 6.05371 13.9423C6.47985 13.813 6.81333 13.4795 6.9426 13.0534C7 12.8641 7 12.6315 7 12.1663C7 11.7011 7 11.4685 6.9426 11.2793C6.81333 10.8531 6.47985 10.5197 6.05371 10.3904C5.86445 10.333 5.63185 10.333 5.16667 10.333Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path
                            d="M14 11.5003V10.167C14 9.07119 14 8.52326 13.6973 8.15446C13.6419 8.08693 13.5801 8.02506 13.5125 7.96966C13.1437 7.66699 12.5958 7.66699 11.5 7.66699C10.4042 7.66699 9.85627 7.66699 9.48747 7.96966C9.41993 8.02506 9.35807 8.08693 9.30267 8.15446C9 8.52326 9 9.07119 9 10.167V11.5003C9 12.5961 9 13.1441 9.30267 13.5129C9.35807 13.5804 9.41993 13.6423 9.48747 13.6977C9.85627 14.0003 10.4042 14.0003 11.5 14.0003C12.5958 14.0003 13.1437 14.0003 13.5125 13.6977C13.5801 13.6423 13.6419 13.5804 13.6973 13.5129C14 13.1441 14 12.5961 14 11.5003Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path
                            d="M12.1667 2H10.8333C10.3681 2 10.1355 2 9.94627 2.05741C9.52013 2.18668 9.18667 2.52015 9.0574 2.94629C9 3.13555 9 3.36815 9 3.83333C9 4.29852 9 4.53111 9.0574 4.72038C9.18667 5.14651 9.52013 5.47999 9.94627 5.60925C10.1355 5.66667 10.3681 5.66667 10.8333 5.66667H12.1667C12.6319 5.66667 12.8645 5.66667 13.0537 5.60925C13.4799 5.47999 13.8133 5.14651 13.9426 4.72038C14 4.53111 14 4.29852 14 3.83333C14 3.36815 14 3.13555 13.9426 2.94629C13.8133 2.52015 13.4799 2.18668 13.0537 2.05741C12.8645 2 12.6319 2 12.1667 2Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                    </svg>
                    {{ __('Dashboard') }}
                </a>
            </li>
            <li class="divider"><span>{{ __('Social Media') }}</span></li>
            <li
                class="{{ ((isset($activeSocialMedia) && $activeSocialMedia == 'active') || (isset($activeSocialMediaConfigs) && $activeSocialMediaConfigs == 'active')) ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#platforms-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($showSocialMediaMenu) && $showSocialMediaMenu) ? 'true' : 'false' }}"
                    aria-controls="platforms-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M2.66699 5.33301C2.66699 3.44739 2.66699 2.50458 3.33647 1.91879C4.00593 1.33301 5.08342 1.33301 7.23839 1.33301H8.76226C10.9172 1.33301 11.9947 1.33301 12.6642 1.91879C13.3337 2.50458 13.3337 3.44739 13.3337 5.33301V11.333H2.66699V5.33301Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path d="M2 11.333H14" stroke="#808080" stroke-width="1.3" stroke-linecap="round" />
                        <path
                            d="M7.13248 3.71091C7.95262 3.59337 9.31162 3.64039 8.18688 4.76877C6.78102 6.17923 4.67226 9.35293 7.13248 8.29506C9.59275 7.23726 10.6473 7.94246 9.59288 9.00033"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M8 11.333V13.9997" stroke="#808080" stroke-width="1.3" stroke-linecap="round" />
                        <path d="M3.33301 14.6663L5.33301 11.333" stroke="#808080" stroke-width="1.3"
                            stroke-linecap="round" />
                        <path d="M12.667 14.6663L10.667 11.333" stroke="#808080" stroke-width="1.3"
                            stroke-linecap="round" />
                    </svg>
                    {{ __('Accounts') }}
                </a>
                <ul id="platforms-menu"
                    class="collapse {{ (isset($showSocialMediaMenu) && $showSocialMediaMenu) ? 'show' : '' }}">
                    <li
                        class="{{ isset($activeSocialMediaConfigs) && $activeSocialMediaConfigs == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.platform.index') }}">{{ __('Platforms') }}</a>
                    </li>
                    <li class="{{ isset($activeSocialMedia) && $activeSocialMedia == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.social.account.index') }}">{{ __('Accounts') }}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ isset($activePosts) && $activePosts == 'active' ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#posts-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showPostsMenu) ? 'true' : 'false' }}" aria-controls="posts-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14.0321 2.03561C12.5801 0.4719 1.65798 4.30246 1.667 5.70099C1.67722 7.28692 5.93238 7.77479 7.11179 8.10572C7.82106 8.30466 8.01099 8.50866 8.17452 9.25239C8.91519 12.6207 9.28706 14.296 10.1346 14.3334C11.4855 14.3931 15.4492 3.56166 14.0321 2.03561Z"
                            stroke="#808080" stroke-width="1.3" />
                        <path d="M7.66699 8.33333L10.0003 6" stroke="#808080" stroke-width="1.3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    {{ __('Posts') }}
                </a>
                <ul id="posts-menu" class="collapse {{ isset($showPostsMenu) ? 'show' : '' }}">
                    <li class="{{ isset($activeScheduledPosts) && $activeScheduledPosts == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.all-posts.index') }}">{{ __('All Posts') }}</a>
                    </li>
                    <li class="{{ isset($activeCreatePost) && $activeCreatePost == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.all-posts.create') }}">{{ __('Create Post') }}</a>
                    </li>
                    <li class="{{ isset($activePublishPosts) && $activePublishPosts == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.publish-posts.index') }}">{{ __('Publish Posts') }}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ isset($activeCampaigns) && $activeCampaigns == 'active' ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#campaigns-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showCampaignsMenu) ? 'true' : 'false' }}" aria-controls="campaigns-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.86786 4.32665L8.85826 3.33627C9.97339 2.22113 11.4333 1.75857 12.9784 1.68299C13.5794 1.65359 13.8799 1.63889 14.1208 1.87985C14.3618 2.1208 14.3471 2.42128 14.3177 3.02225C14.2421 4.56733 13.7795 6.02727 12.6644 7.14239L11.674 8.13279C10.8584 8.94839 10.6265 9.18033 10.7977 10.065C10.9667 10.7408 11.1303 11.3952 10.6389 11.8866C10.0428 12.4827 9.49906 12.4827 8.90299 11.8866L4.11404 7.09766C3.51798 6.50158 3.51796 5.95786 4.11404 5.36179C4.60544 4.87039 5.25985 5.03395 5.93564 5.20292C6.82032 5.37415 7.05226 5.14225 7.86786 4.32665Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path d="M11.3301 4.66699H11.3361" stroke="#808080" stroke-width="1.3" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M1.66699 14.3333L5.00033 11" stroke="#808080" stroke-width="1.3"
                            stroke-linecap="round" />
                        <path d="M5.66699 14.3333L7.00033 13" stroke="#808080" stroke-width="1.3"
                            stroke-linecap="round" />
                        <path d="M1.66699 10.3333L3.00033 9" stroke="#808080" stroke-width="1.3"
                            stroke-linecap="round" />
                    </svg>
                    {{ __('Campaigns') }}
                </a>
                <ul id="campaigns-menu" class="collapse {{ isset($showCampaignsMenu) ? 'show' : '' }}">
                    <li class="{{ isset($activeCampaignList) && $activeCampaignList == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.campaign.index') }}">{{ __(' List') }}</a>
                    </li>
                    <li class="{{ isset($activeCampaignCreate) && $activeCampaignCreate == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.campaign.create') }}">{{ __(' Create') }}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (isset($activeCalendar) && $activeCalendar == 'active') || (isset($activeTimezoneSettings) && $activeTimezoneSettings == 'active') ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#manage-calendar-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($activeCalendar) && $activeCalendar == 'active') || (isset($activeTimezoneSettings) && $activeTimezoneSettings == 'active') ? 'true' : 'false' }}"
                    aria-controls="manage-calendar-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M10.6663 1.33301V3.99967M5.33301 1.33301V3.99967" stroke="#808080" stroke-width="1.3"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M8.66667 2.66699H7.33333C4.81917 2.66699 3.5621 2.66699 2.78105 3.44804C2 4.22909 2 5.48617 2 8.00033V9.33366C2 11.8478 2 13.1049 2.78105 13.8859C3.5621 14.667 4.81917 14.667 7.33333 14.667H8.66667C11.1808 14.667 12.4379 14.667 13.2189 13.8859C14 13.1049 14 11.8478 14 9.33366V8.00033C14 5.48617 14 4.22909 13.2189 3.44804C12.4379 2.66699 11.1808 2.66699 8.66667 2.66699Z"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M2 6.66699H14" stroke="#808080" stroke-width="1.3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    {{ __('Manage Calendar') }}
                </a>
                <ul id="manage-calendar-menu"
                    class="collapse {{ (isset($activeCalendar) && $activeCalendar == 'active') || (isset($activeTimezoneSettings) && $activeTimezoneSettings == 'active') ? 'show' : '' }}">
                    <li class="{{ isset($activeCalendar) && $activeCalendar == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.calendar') }}">{{ __('Calendar') }}</a>
                    </li>
                    <li class="{{ isset($activeTimezoneSettings) && $activeTimezoneSettings == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.timezone') }}">{{ __('Timezone') }}</a>
                    </li>
                </ul>
            </li>
            <li class="{{ isset($activeAnalytics) && $activeAnalytics == 'active' ? 'currrent-menu' : '' }}">
                <a href="{{ route('admin.analytics.index') }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.33301 11.667V9.66699M7.66634 11.667V5.66699M10.9997 11.667V9.00033" stroke="#808080"
                            stroke-width="1.3" stroke-linecap="round" />
                        <path
                            d="M14.333 3.66699C14.333 4.77156 13.4376 5.66699 12.333 5.66699C11.2284 5.66699 10.333 4.77156 10.333 3.66699C10.333 2.56243 11.2284 1.66699 12.333 1.66699C13.4376 1.66699 14.333 2.56243 14.333 3.66699Z"
                            stroke="#808080" stroke-width="1.3" />
                        <path
                            d="M14.3307 7.33366C14.3307 7.33366 14.3337 7.55999 14.3337 8.00033C14.3337 10.9859 14.3337 12.4787 13.4062 13.4062C12.4787 14.3337 10.9859 14.3337 8.00033 14.3337C5.01477 14.3337 3.52199 14.3337 2.59449 13.4062C1.66699 12.4787 1.66699 10.9859 1.66699 8.00033C1.66699 5.01479 1.66699 3.52201 2.59449 2.59451C3.52199 1.66701 5.01477 1.66701 8.00033 1.66701L8.66699 1.66699"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ __('Analytics') }}
                </a>
            </li>
            <li class="divider"><span>{{ __('Support') }}</span></li>
            <li class="{{ isset($activeSupportTicket) && $activeSupportTicket == 'active' ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#support-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showSupportMenu) ? 'true' : 'false' }}" aria-controls="support-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1.64256 6.22884C1.47687 6.22884 1.32567 6.09453 1.33328 5.91897C1.3779 4.89092 1.50288 4.22166 1.85306 3.69223C2.05453 3.38765 2.30478 3.12273 2.5925 2.90946C3.37018 2.33301 4.46727 2.33301 6.66145 2.33301H9.33794C11.5321 2.33301 12.6292 2.33301 13.4069 2.90946C13.6946 3.12273 13.9448 3.38765 14.1463 3.69223C14.4965 4.2216 14.6215 4.89077 14.6661 5.91863C14.6737 6.09439 14.5223 6.22884 14.3564 6.22884C13.4325 6.22884 12.6836 7.02167 12.6836 7.99967C12.6836 8.97767 13.4325 9.77047 14.3564 9.77047C14.5223 9.77047 14.6737 9.90494 14.6661 10.0807C14.6215 11.1086 14.4965 11.7777 14.1463 12.3071C13.9448 12.6117 13.6946 12.8766 13.4069 13.0899C12.6292 13.6663 11.5321 13.6663 9.33794 13.6663H6.66145C4.46727 13.6663 3.37018 13.6663 2.5925 13.0899C2.30478 12.8766 2.05453 12.6117 1.85306 12.3071C1.50288 11.7777 1.3779 11.1084 1.33328 10.0804C1.32567 9.90481 1.47687 9.77047 1.64256 9.77047C2.56642 9.77047 3.31536 8.97767 3.31536 7.99967C3.31536 7.02167 2.56642 6.22884 1.64256 6.22884Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path d="M6 2.33301V13.6663" stroke="#808080" stroke-width="1.3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    {{ __('Support Ticket') }}
                </a>
                <ul id="support-menu" class="collapse {{ isset($showSupportMenu) ? 'show' : '' }}">
                    <li class="{{ isset($activeSupportTicket) && $activeSupportTicket == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.ticket.list') }}">{{ __('Add Ticket') }}</a>
                    </li>
                </ul>
            </li>

            <li class="divider"><span>{{ __('ALL content') }}</span></li>
            <li
                class="{{ (isset($activeTemplates) && $activeTemplates == 'active') || (isset($activeCategory) && $activeCategory == 'active') ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#templates-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showTemplatesMenu) || isset($activeCategory) ? 'true' : 'false' }}"
                    aria-controls="templates-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M6.00033 4.66699H10.0003C12.2002 4.66699 13.3001 4.66699 13.9836 5.35041C14.667 6.03383 14.667 7.13379 14.667 9.33366V10.0003C14.667 12.2002 14.667 13.3001 13.9836 13.9836C13.3001 14.667 12.2002 14.667 10.0003 14.667H9.33366C7.13379 14.667 6.03383 14.667 5.35041 13.9836C4.66699 13.3001 4.66699 12.2002 4.66699 10.0003V6.00033"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1.33301 4.66699H3.33301" stroke="#808080" stroke-width="1.3" stroke-linecap="round" />
                        <path d="M4.66699 3.33301V1.33301" stroke="#808080" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    {{ __('All templates') }}
                </a>
                <ul id="templates-menu"
                    class="collapse {{ isset($showTemplatesMenu) || isset($activeCategory) ? 'show' : '' }}">
                    <li class="{{ isset($activeTemplateList) && $activeTemplateList == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.template.index') }}">{{ __(' List') }}</a>
                    </li>
                    <li class="{{ isset($activeTemplateCreate) && $activeTemplateCreate == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.template.create') }}">{{ __(' Create') }}</a>
                    </li>
                    <li class="{{ isset($activeCategory) && $activeCategory == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.category.index') }}">{{ __('Categories') }}</a>
                    </li>
                </ul>
            </li>
            <!-- <li class="{{ isset($activeAIContent) && $activeAIContent == 'active' ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#ai-content-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showAIContentMenu) ? 'true' : 'false' }}" aria-controls="ai-content-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.3337 14.6663V14.333C10.3337 13.5966 10.9551 13 11.6152 12.6737C12.2567 12.3566 12.7965 11.8337 12.8647 11.2205L13.0003 9.99967L14.3337 9.33301L12.667 6.83301C12.667 3.79544 10.2045 1.33301 7.16699 1.33301C4.12943 1.33301 1.66699 3.79544 1.66699 6.83301C1.66699 8.69161 2.58888 10.3349 4.00033 11.3305M4.00033 11.3305V14.6663M4.00033 11.3305C4.50076 11.6835 5.06274 11.9551 5.66699 12.126"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M7.53879 8.00033L6.56501 4.98627C6.50379 4.79677 6.31595 4.66699 6.10289 4.66699C5.88983 4.66699 5.70199 4.79677 5.64077 4.98627L4.66699 8.00033M9.33366 4.66699V8.00033M5.02597 7.00033H7.17979"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ __('generate ai content') }}
                </a>
                <ul id="ai-content-menu" class="collapse {{ isset($showAIContentMenu) ? 'show' : '' }}">
                    <li
                        class="{{ isset($activeGenerateContent) && $activeGenerateContent == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.ai.generate-content') }}">{{ __('Generate Content') }}</a>
                    </li>
                    <li class="{{ isset($activeGeneratedList) && $activeGeneratedList == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.ai.generated-content.list') }}">{{ __('Generated List') }}</a>
                    </li>
                </ul>
            </li> -->
            <li
                class="{{ (isset($activeGallery) && $activeGallery == 'active') || (isset($activeVideoGallery) && $activeVideoGallery == 'active') ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#gallery-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showGalleryMenu) || isset($showVideoGalleryMenu) ? 'true' : 'false' }}"
                    aria-controls="gallery-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.6667 4.66699C12.238 4.66699 13.0237 4.66699 13.5119 5.15515C14 5.64331 14 6.42898 14 8.00033C14 9.57166 14 10.3573 13.5119 10.8455C13.0237 11.3337 12.238 11.3337 10.6667 11.3337H5.33333C3.76199 11.3337 2.97631 11.3337 2.48815 10.8455C2 10.3573 2 9.57166 2 8.00033C2 6.42898 2 5.64331 2.48815 5.15515C2.97631 4.66699 3.76199 4.66699 5.33333 4.66699H10.6667Z"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.3337 1.33301C11.264 1.69765 11.1408 1.96602 10.9229 2.17819C10.4214 2.66634 9.61439 2.66634 8.00033 2.66634C6.38626 2.66634 5.57923 2.66634 5.0778 2.17819C4.85987 1.96602 4.73665 1.69765 4.66699 1.33301"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M11.3337 14.6663C11.264 14.3017 11.1408 14.0333 10.9229 13.8211C10.4214 13.333 9.61439 13.333 8.00033 13.333C6.38626 13.333 5.57923 13.333 5.0778 13.8211C4.85987 14.0333 4.73665 14.3017 4.66699 14.6663"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ __('Gallery') }}
                </a>
                <ul id="gallery-menu"
                    class="collapse {{ isset($showGalleryMenu) || isset($showVideoGalleryMenu) ? 'show' : '' }}">
                    <li class="{{ isset($activeGallery) && $activeGallery == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.gallery.index') }}">{{ __('Image Gallery') }}</a>
                    </li>
                    <li class="{{ isset($activeVideoGallery) && $activeVideoGallery == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.video-gallery.index') }}">{{ __('Video Gallery') }}</a>
                    </li>
                </ul>
            </li>

            <li class="divider"><span>{{ __('Access Control') }}</span></li>
            <li class="{{ isset($activeRoles) && $activeRoles == 'active' ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#roles-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showRolesMenu) ? 'true' : 'false' }}" aria-controls="roles-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4.66634 1.33398C2.82539 1.33398 1.33301 2.82637 1.33301 4.66732C1.33301 5.90112 2.00334 6.97838 2.99967 7.55472V11.8961C2.99967 12.4411 2.99967 12.7136 3.10117 12.9586C3.20266 13.2036 3.39535 13.3963 3.78072 13.7817L4.66634 14.6673L6.0718 13.2619C6.13662 13.1971 6.16905 13.1646 6.19594 13.1294C6.26655 13.0371 6.31178 12.9279 6.32715 12.8127C6.33301 12.7688 6.33301 12.7229 6.33301 12.6313C6.33301 12.5571 6.33301 12.52 6.32907 12.4839C6.31877 12.3894 6.28836 12.2982 6.23991 12.2164C6.22143 12.1852 6.19917 12.1555 6.15465 12.0962L5.33301 11.0007L5.79967 10.3785C6.064 10.026 6.19616 9.84978 6.26459 9.64452C6.33301 9.43925 6.33301 9.21898 6.33301 8.77845V7.55472C7.32934 6.97838 7.99967 5.90112 7.99967 4.66732C7.99967 2.82637 6.50729 1.33398 4.66634 1.33398Z"
                            stroke="#808080" stroke-width="1.3" stroke-linejoin="round" />
                        <path d="M4.66699 4.66602H4.67298" stroke="#808080" stroke-width="1.3" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path
                            d="M8.66699 9.33398H12.667C13.2883 9.33398 13.5989 9.33398 13.8439 9.43545C14.1706 9.57078 14.4302 9.83038 14.5655 10.1571C14.667 10.4021 14.667 10.7127 14.667 11.334C14.667 11.9553 14.667 12.2659 14.5655 12.5109C14.4302 12.8376 14.1706 13.0972 13.8439 13.2325C13.5989 13.334 13.2883 13.334 12.667 13.334H8.66699"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" />
                        <path
                            d="M10 3.33301H12.6667C13.2879 3.33301 13.5985 3.33301 13.8436 3.4345C14.1703 3.56983 14.4299 3.82939 14.5652 4.15609C14.6667 4.40113 14.6667 4.71175 14.6667 5.33301C14.6667 5.95426 14.6667 6.26489 14.5652 6.50992C14.4299 6.83661 14.1703 7.09621 13.8436 7.23154C13.5985 7.33301 13.2879 7.33301 12.6667 7.33301H10"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" />
                    </svg>
                    {{ __('Role & Permission') }}
                </a>
                <ul id="roles-menu" class="collapse {{ isset($showRolesMenu) ? 'show' : '' }}">
                    <li class="{{ isset($activeRoles) && $activeRoles == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">{{ __('Roles') }}</a>
                    </li>
                </ul>
            </li>
            <li class="divider"><span>{{ __('Billing Center') }}</span></li>
            <li
                class="{{ (isset($activeBilling) && $activeBilling == 'active') || (isset($activePricing) && $activePricing == 'active') ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#billing-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ isset($showBillingMenu) ? 'true' : 'false' }}" aria-controls="billing-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M10.8719 2.92328C10.4779 2.92328 10.2808 2.92328 10.1013 2.85668C10.0764 2.84743 10.0519 2.83726 10.0277 2.82617C9.85367 2.74635 9.71441 2.60704 9.43574 2.32843C8.79447 1.68716 8.47387 1.36653 8.07934 1.33696C8.02634 1.33299 7.97301 1.33299 7.92001 1.33696C7.52547 1.36653 7.20481 1.68716 6.56357 2.32842C6.28496 2.60704 6.14565 2.74635 5.97165 2.82617C5.94749 2.83726 5.92291 2.84743 5.89799 2.85668C5.71851 2.92328 5.52149 2.92328 5.12748 2.92328H5.0548C4.04953 2.92328 3.54689 2.92328 3.2346 3.23558C2.9223 3.54787 2.9223 4.0505 2.9223 5.05578V5.12846C2.9223 5.52247 2.9223 5.71948 2.85571 5.89896C2.84645 5.92389 2.83628 5.94846 2.82519 5.97263C2.74537 6.14663 2.60607 6.28594 2.32745 6.56455C1.68619 7.20578 1.36555 7.52645 1.33599 7.92098C1.33201 7.97398 1.33201 8.02732 1.33599 8.08032C1.36555 8.47485 1.68619 8.79545 2.32745 9.43672C2.60607 9.71538 2.74537 9.85465 2.82519 10.0287C2.83628 10.0529 2.84645 10.0774 2.85571 10.1023C2.9223 10.2818 2.9223 10.4789 2.9223 10.8729V10.9455C2.9223 11.9508 2.9223 12.4535 3.2346 12.7657C3.54689 13.0781 4.04953 13.0781 5.0548 13.0781H5.12748C5.52149 13.0781 5.71851 13.0781 5.89799 13.1447C5.92291 13.1539 5.94749 13.1641 5.97165 13.1751C6.14565 13.255 6.28496 13.3943 6.56357 13.6729C7.20481 14.3141 7.52547 14.6348 7.92001 14.6643C7.97301 14.6683 8.02627 14.6683 8.07934 14.6643C8.47387 14.6348 8.79447 14.3141 9.43574 13.6729C9.71441 13.3943 9.85367 13.255 10.0277 13.1751C10.0519 13.1641 10.0764 13.1539 10.1013 13.1447C10.2808 13.0781 10.4779 13.0781 10.8719 13.0781H10.9445C11.9498 13.0781 12.4525 13.0781 12.7647 12.7657C13.0771 12.4535 13.0771 11.9508 13.0771 10.9455V10.8729C13.0771 10.4789 13.0771 10.2818 13.1437 10.1023C13.1529 10.0774 13.1631 10.0529 13.1741 10.0287C13.254 9.85465 13.3933 9.71538 13.6719 9.43672C14.3131 8.79545 14.6338 8.47485 14.6633 8.08032C14.6673 8.02725 14.6673 7.97398 14.6633 7.92098C14.6338 7.52645 14.3131 7.20578 13.6719 6.56455C13.3933 6.28594 13.254 6.14663 13.1741 5.97263C13.1631 5.94846 13.1529 5.92389 13.1437 5.89896C13.0771 5.71948 13.0771 5.52247 13.0771 5.12846V5.05578C13.0771 4.0505 13.0771 3.54787 12.7647 3.23558C12.4525 2.92328 11.9498 2.92328 10.9445 2.92328H10.8719Z"
                            stroke="#808080" stroke-width="1.3" />
                        <path
                            d="M10.3337 7.99935C10.3337 9.28802 9.28899 10.3327 8.00033 10.3327C6.71166 10.3327 5.66699 9.28802 5.66699 7.99935C5.66699 6.71068 6.71166 5.66602 8.00033 5.66602C9.28899 5.66602 10.3337 6.71068 10.3337 7.99935Z"
                            stroke="#808080" stroke-width="1.3" />
                    </svg>
                    {{ __('My Plan') }}
                </a>
                <ul id="billing-menu"
                    class="collapse {{ (isset($showBillingMenu) && $showBillingMenu == 'show') || (isset($activePricing) && $activePricing == 'active') || (isset($activeBilling) && $activeBilling == 'active') ? 'show' : '' }}">
                    <!-- <li class="{{ isset($activePricing) && $activePricing == 'active' ? 'active' : '' }}">
                            <a href="{{ route('admin.pricing.index') }}">{{ __('Pricing') }}</a>
                        </li> -->
                    <li class="{{ isset($activeBilling) && $activeBilling == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.billings.index') }}">{{ __('Billing') }}</a>
                    </li>
                </ul>
            </li>

            <li class="{{ isset($activeProfile) && $activeProfile == 'active' ? 'currrent-menu' : '' }}">
                <a href="{{ route('admin.profile.index') }}">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.3337 5.66634C11.3337 3.82539 9.84126 2.33301 8.00033 2.33301C6.15938 2.33301 4.66699 3.82539 4.66699 5.66634C4.66699 7.50727 6.15938 8.99967 8.00033 8.99967C9.84126 8.99967 11.3337 7.50727 11.3337 5.66634Z"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M12.6663 13.6667C12.6663 11.0893 10.577 9 7.99967 9C5.42235 9 3.33301 11.0893 3.33301 13.6667"
                            stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ __('Profile') }}
                </a>
            </li>
        </ul>
    </div>
    <div class="sidebar-overlay"></div>
</aside>
