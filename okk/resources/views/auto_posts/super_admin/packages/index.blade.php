@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ __('Packages') }}
@endpush


@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="section-title">
    <h2 class="title">{{ __('Packages') }}</h2>
    <button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#package-modal"
        onclick="openPackageModal()">
        <i class="fa fa-plus me-2"></i>{{ __('Add New Package') }}
    </button>
</div>
<div class="section-wrap">
    <div class="table-waraper">
        <div class="search-input-wrap">
            <label class="icon" for="searchData">
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
            <input type="text" class="search-input" id="searchData" placeholder="{{ __('Search By Name...') }}" />
        </div>
        <input type="hidden" id="packages-data-route" value="{{ route('super_admin.packages.data') }}">
        <table class="display primary-table dataTable dtr-inline" id="packagesDataTable">
            <thead>
                <tr>
                    <th class="keep-show">{{ __('SL') }}</th>
                    <th class="keep-show">{{ __('Icon') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Provider') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Old Price') }}</th>
                    <th>{{ __('AI Access') }}</th>
                    <th>{{ __('Features') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="keep-show">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div id="packages-pagination-wrap" class="d-flex justify-content-center mt-20 tablePagi"></div>
    </div>
</div>

    <!-- Package Modal -->
  <div class="modal fade primary-modal" id="package-modal" tabindex="-1" aria-labelledby="packageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="packageModalLabel">{{ __('Add New Package') }}</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="package-form" method="POST" action="{{ route('super_admin.packages.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <div class="modal-body">
                        <div class="primary-form">
                            <div class="row gy-4">
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="name" class="form-label">{{ __('Package Name') }}<span
                                                class="required">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="{{ __('Enter package name') }}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="icon" class="form-label">{{ __('Package Image') }}</label>
                                        <input type="file" class="form-control" id="icon" name="icon" accept="image/*"
                                            onchange="previewImage(this)">
                                        <small class="text-muted">{{ __('Upload a package image (optional)') }}</small>
                                        <div id="image-preview" class="mt-2" style="display: none;">
                                            <img id="preview-img" src="" alt="Image Preview"
                                                style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                        </div>
                                        <div id="current-image-preview" class="mt-2" style="display: none;">
                                            <small class="text-muted">{{ __('Current image:') }}</small><br>
                                            <img id="current-img" src="" alt="Current Image"
                                                style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 4px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ __('Provider') }}<span
                                                class="required">*</span></label>
                                        <select class="multipleSelect2" multiple="true" name="provider_limit[]">
                                            @foreach(SOCIAL_MEDIA_PLATFORMS as $id => $name)
                                            <option value="{{ $id }}"> {{ $name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="ai_enabled" class="form-label">{{ __('AI Enabled') }}</label>
                                        <select class="select form-control wide sf-select-without-search" id="ai_enabled"
                                            name="ai_enabled">
                                            <option value="1">{{ __('Yes') }}</option>
                                            <option value="0">{{ __('No') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="post_limit" class="form-label">{{ __('Post Limit') }}</label>
                                        <input type="number" class="form-control" id="post_limit" name="post_limit"
                                            placeholder="{{ __('Enter post limit') }}" min="0">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="monthly_price" class="form-label">{{ __('Monthly Price') }}<span
                                                class="required">*</span></label>
                                        <input type="number" class="form-control" id="monthly_price" name="monthly_price"
                                            placeholder="{{ __('Enter monthly price') }}" step="0.01" min="0" required>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="old_monthly_price"
                                            class="form-label">{{ __('Old Monthly Price') }}</label>
                                        <input type="number" class="form-control" id="old_monthly_price"
                                            name="old_monthly_price" placeholder="{{ __('Enter old monthly price') }}"
                                            step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="yearly_price" class="form-label">{{ __('Yearly Price') }}</label>
                                        <input type="number" class="form-control" id="yearly_price" name="yearly_price"
                                            placeholder="{{ __('Enter yearly price') }}" step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="old_yearly_price"
                                            class="form-label">{{ __('Old Yearly Price') }}</label>
                                        <input type="number" class="form-control" id="old_yearly_price"
                                            name="old_yearly_price" placeholder="{{ __('Enter old yearly price') }}"
                                            step="0.01" min="0">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="status" class="form-label">{{ __('Status') }}<span
                                                class="required">*</span></label>
                                        <select class="select form-control wide sf-select-without-search" id="status"
                                            name="status">
                                            <option value="1">{{ __('Active') }}</option>
                                            <option value="0">{{ __('Inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <label for="description" class="form-label">{{ __('Description') }}</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            placeholder="{{ __('Enter package description') }}"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="form-label mb-0">{{ __('Features') }}</label>
                                            <button type="button" id="add-feature" class="primary-btn">
                                                <i class="fa fa-plus me-1"></i>{{ __('Add More Feature') }}
                                            </button>
                                        </div>
                                        <div id="features-container">
                                            <div class="feature-item mb-3">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="features[]"
                                                        placeholder="{{ __('Enter a feature, e.g., Social Media Posting') }}"
                                                        style="width: 95%;">
                                                    <button type="button" class="btn btn-outline-danger remove-feature"
                                                        style="display: none;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="create_plan_in_gateway"
                                                name="create_plan_in_gateway" value="1">
                                            <label class="form-check-label" for="create_plan_in_gateway">
                                                {{ __('Create plan in payment gateway') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="primary-btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn" id="submit-btn">{{ __('Create Package') }}</button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
window.superAdminPackageConfig = {
    routes: {
        store: '{{ route("super_admin.packages.store") }}',
        updateTemplate: '{{ route("super_admin.packages.update", ":id") }}',
        editTemplate: '{{ route("super_admin.packages.edit", ":id") }}',
    },
};
</script>
<script src="{{ asset('super_admin/js/packages.js') }}"></script>
<script>
$(document).ready(function() {
    //Select2
    $(".multipleSelect2").select2({
        placeholder: "Select Providers",
        allowClear: true,
    });
})
</script>
@endpush
