@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ __('Footer Right Menus') }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __('Footer Right Menus') }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#addFooterSupportMenuModal">
        <i class="fa fa-plus me-2"></i>{{ __('Add Menu') }}
    </button>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap">
                    <label class="icon" for="searchMenus">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchMenus"
                        placeholder="{{ __('Search by menu name...') }}" />
                </div>

                <input type="hidden" id="footer-menu-data-route"
                    value="{{ route('super_admin.setting.menu.footer-right.list') }}">

                <table class="display primary-table dataTable dtr-inline" id="footerMenuDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show">{{ __('#') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('URL') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th class="keep-show">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <div id="footer-menu-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
        </div>
    </div>
</div>

<!-- Add Footer Support Menu Modal -->
<div class="modal fade" id="addFooterSupportMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form class="ajax reset" action="{{ route('super_admin.setting.menu.footer-right.store') }}" method="post"
                data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Add Menu') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{__('Name')}}<span class="required">*</span></label>
                                    <input type="text" name="name" id="footerSupportMenuName" value="{{ old('name') }}"
                                        placeholder="{{__('Menu Name')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('URL') }}</label>
                                    <input type="text" name="link" id="footerSupportMenuUrl" value="{{ old('link') }}"
                                        placeholder="{{__('e.g. /support')}}" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="footerSupportMenuStatus" class="form-control">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="2">{{ __('Deactivated') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Create') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Footer Support Menu Modal -->
<div class="modal fade" id="editFooterSupportMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form class="ajax reset" id="editFooterSupportMenuForm" method="post"
                data-handler="commonResponseWithPageLoad">
                @csrf
                @method('patch')
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Edit Menu') }}</h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{__('Name')}}<span class="required">*</span></label>
                                    <input type="text" name="name" id="editFooterSupportMenuName" value=""
                                        placeholder="{{__('Menu Name')}}" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('URL') }}</label>
                                    <input type="text" name="link" id="editFooterSupportMenuUrl" value=""
                                        placeholder="{{__('URL')}}" class="form-control" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="editFooterSupportMenuStatus" class="form-control">
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="2">{{ __('Deactivated') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script src="{{ asset('super_admin/js/footer-menu.js') }}"></script>
@endpush
@endsection