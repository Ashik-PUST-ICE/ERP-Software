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
            <li class="divider"><span>{{ __('HRM Management') }}</span></li>
            <li class="{{ (isset($activeHrm) || isset($showHrmMenu) || isset($showHRMMenu) || isset($activeHrmDashboard) || isset($activeDepartment) || isset($activeDepartments) || isset($activeDesignation) || isset($activeDesignations) || isset($activeEmployee) || isset($activeEmployees) || isset($activeAttendance) || isset($activeAttendances) || isset($activeLeave) || isset($activeLeaves) || isset($activePayroll) || isset($activePayrolls)) ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#hrm-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($activeHrm) || isset($showHrmMenu) || isset($showHRMMenu) || isset($activeHrmDashboard) || isset($activeDepartment) || isset($activeDepartments) || isset($activeDesignation) || isset($activeDesignations) || isset($activeEmployee) || isset($activeEmployees) || isset($activeAttendance) || isset($activeAttendances) || isset($activeLeave) || isset($activeLeaves) || isset($activePayroll) || isset($activePayrolls)) ? 'true' : 'false' }}"
                    aria-controls="hrm-menu">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.33301 1.33301V3.99967M10.6663 1.33301V3.99967" stroke="#808080" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8 8C9.65685 8 11 6.65685 11 5C11 3.34315 9.65685 2 8 2C6.34315 2 5 3.34315 5 5C5 6.65685 6.34315 8 8 8Z" stroke="#808080" stroke-width="1.3"/>
                        <path d="M2 14C2 11.2386 4.68629 9 8 9C11.3137 9 14 11.2386 14 14" stroke="#808080" stroke-width="1.3"/>
                    </svg>
                    {{ __('HRM Management') }}
                </a>
                <ul id="hrm-menu" class="collapse {{ (isset($activeHrm) || isset($showHrmMenu) || isset($showHRMMenu) || isset($activeHrmDashboard) || isset($activeDepartment) || isset($activeDepartments) || isset($activeDesignation) || isset($activeDesignations) || isset($activeEmployee) || isset($activeEmployees) || isset($activeAttendance) || isset($activeAttendances) || isset($activeLeave) || isset($activeLeaves) || isset($activePayroll) || isset($activePayrolls)) ? 'show' : '' }}">
                    <li class="{{ isset($activeHrmDashboard) && $activeHrmDashboard == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li class="{{ (isset($activeDepartment) && $activeDepartment == 'active') || (isset($activeDepartments) && $activeDepartments == 'active') ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.departments.index') }}">{{ __('Departments') }}</a>
                    </li>
                    <li class="{{ (isset($activeDesignation) && $activeDesignation == 'active') || (isset($activeDesignations) && $activeDesignations == 'active') ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.designations.index') }}">{{ __('Designations') }}</a>
                    </li>
                    <li class="{{ (isset($activeEmployee) && $activeEmployee == 'active') || (isset($activeEmployees) && $activeEmployees == 'active') ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.employees.index') }}">{{ __('Employees') }}</a>
                    </li>
                    <li class="{{ (isset($activeAttendance) && $activeAttendance == 'active') || (isset($activeAttendances) && $activeAttendances == 'active') ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.attendance.index') }}">{{ __('Attendance') }}</a>
                    </li>
                    <li class="{{ (isset($activeLeave) && $activeLeave == 'active') || (isset($activeLeaves) && $activeLeaves == 'active') ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.leaves.index') }}">{{ __('Leave Requests') }}</a>
                    </li>
                    <li class="{{ (isset($activePayroll) && $activePayroll == 'active') || (isset($activePayrolls) && $activePayrolls == 'active') ? 'active' : '' }}">
                        <a href="{{ route('admin.hrm.payroll.index') }}">{{ __('Payroll') }}</a>
                    </li>
                </ul>
            </li>

            <li class="divider"><span>{{ __('Garments ERP') }}</span></li>
            <li class="{{ (isset($activeGarments) || isset($showGarmentsMenu) || isset($activeGarmentBuyers) || isset($activeGarmentOrders) || isset($activeGarmentStyles) || isset($activeGarmentCostings) || isset($activeGarmentTna) || isset($activeGarmentPlans)) ? 'currrent-menu' : '' }}">
                <a class="has-arrow" href="#garments-menu" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ (isset($activeGarments) || isset($showGarmentsMenu) || isset($activeGarmentBuyers) || isset($activeGarmentOrders) || isset($activeGarmentStyles) || isset($activeGarmentCostings) || isset($activeGarmentTna) || isset($activeGarmentPlans)) ? 'true' : 'false' }}"
                    aria-controls="garments-menu">
                    <i class="fa-solid fa-shirt" style="width: 16px; text-align: center; color: #808080;"></i>
                    {{ __('Garments ERP') }}
                </a>
                <ul id="garments-menu" class="collapse {{ (isset($activeGarments) || isset($showGarmentsMenu) || isset($activeGarmentBuyers) || isset($activeGarmentOrders) || isset($activeGarmentStyles) || isset($activeGarmentCostings) || isset($activeGarmentTna) || isset($activeGarmentPlans)) ? 'show' : '' }}">
                    <li class="{{ isset($activeGarmentBuyers) && $activeGarmentBuyers == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.garments.buyers.index') }}">{{ __('Buyers') }}</a>
                    </li>
                    <li class="{{ isset($activeGarmentStyles) && $activeGarmentStyles == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.garments.styles.index') }}">{{ __('Styles') }}</a>
                    </li>
                    <li class="{{ isset($activeGarmentOrders) && $activeGarmentOrders == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.garments.orders.index') }}">{{ __('Orders') }}</a>
                    </li>
                    <li class="{{ isset($activeGarmentCostings) && $activeGarmentCostings == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.garments.costings.index') }}">{{ __('Costing Sheets') }}</a>
                    </li>
                    <li class="{{ isset($activeGarmentTna) && $activeGarmentTna == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.garments.tna.index') }}">{{ __('TNA Calendar') }}</a>
                    </li>
                    <li class="{{ isset($activeGarmentPlans) && $activeGarmentPlans == 'active' ? 'active' : '' }}">
                        <a href="{{ route('admin.garments.plans.index') }}">{{ __('Production Planning') }}</a>
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
