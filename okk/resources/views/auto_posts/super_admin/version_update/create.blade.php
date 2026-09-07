@extends('auto_posts.super_admin.layouts.app')
@push('title')
{{ __('Version Update') }}
@endpush
@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/version-update.css') }}">
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

@endpush
@section('content')
<div class="p-30">
    <div class="">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-12">
                <div class="section-title">
                    <h2 class="title">{{ __($title) }}</h2>
                </div>
            </div>
        </div>
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-12">
                    <div class="billing-center-area bg-off-white theme-border radius-4">
                        @if (getCustomerCurrentBuildVersion() == $latestBuildVersion)
                        <div class="col-sm-12">
                            <div class="alert alert-info" type="info" icon="info-circle">
                                <i class="fa fa-info-circle"></i>
                                {{ __('You have the latest version of this app.') }}
                            </div>
                        </div>
                        @endif
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table zTable  dataTable  table-responsive primary-table">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                <div class="rounded-0">{{ __('System Details') }}</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ __('Current Version') }}</td>
                                            <td>
                                                @if (getCustomerCurrentBuildVersion() == $latestBuildVersion)
                                                {{ getOption('current_version') }} <i
                                                    class="fa  fa-check-circle text-success"></i>
                                                @else
                                                {{ getOption('current_version') }} <i data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="download latest from codecanyon"
                                                    class="fa fa-warning text-danger"></i>
                                                @endif
                                            </td>
                                        </tr>
                                        @if (getCustomerCurrentBuildVersion() < $latestBuildVersion) <tr>
                                            <td>
                                                {{ __('Latest Version') }}
                                                <a class="text-link" target="_blank"
                                                    href="https://codecanyon.net/item/zaialumni-alumni-association-laravel-script/48352405">{{
                                                    __('Download Latest') }}</a>
                                            </td>
                                            <td>{{ $latestVersion }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <td>{{ __('Laravel Version') }}</td>
                                                <td>{{ app()->version() }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ __('PHP Version') }}</td>
                                                <td>{{ phpversion() }}</td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if (getCustomerCurrentBuildVersion() < $latestBuildVersion) <div
                            class="col-md-8 mt-30 offset-md-2">
                            <div class="alert alert-danger" type="danger">
                                <ol class="mb-0">
                                    <li>{{ __('Do not click update button if the application is customised. Your changes
                                    will be
                                    lost') }}.
                                    </li>
                                    <li>{{ __('Take backup all the files and database before updating.') }}</li>
                                </ol>
                            </div>
                            <div class="table-wrapper">
                                <div class="table-responsive">
                                    <table class="table zTable  dataTable  table-responsive primary-table">
                                        <tbody class="align-baseline">
                                            <tr>
                                                <td colspan="2" class="border-0">
                                                    <div class="d-flex justify-content-center">
                                                        <span class="btn btn-success mb-4 p-2" id="dz-clickable">
                                                            <i class="fa fa-upload"></i>
                                                            <span>{{ __('Upload File') }}</span>
                                                        </span>
                                                        <div class="files" id="previews">

                                                            <div id="template" class="file-upload row">
                                                                <div class="col-md-12">
                                                                    <table class="table table-borderless mb-0">
                                                                        <tr>
                                                                            <td>
                                                                                <span class="preview text-danger"><i
                                                                                        class="fa fa-file-archive h1"></i></span>
                                                                            </td>
                                                                            <td>
                                                                                <p class="name" data-dz-name></p>
                                                                                <strong
                                                                                    class="error text-danger error-message"
                                                                                    data-dz-errormessage></strong>
                                                                            </td>
                                                                            <td>
                                                                                <p class="d-flex size" data-dz-size></p>
                                                                            </td>
                                                                            <td width="251px">
                                                                                <div id="actions">
                                                                                    <button
                                                                                        class="btn btn-blue start start-btn p-2">
                                                                                        <i class="fa fa-upload"></i>
                                                                                        <span>{{ __('Start') }}</span>
                                                                                    </button>
                                                                                    <button id="cancel-btn"
                                                                                        class="btn btn-warning cancel p-2">
                                                                                        <i class="fa fa-cancel"></i>
                                                                                        <span>{{ __('Cancel') }}</span>
                                                                                    </button>
                                                                                    <a data-url="{{ route('super_admin.version-update-execute') }}"
                                                                                        class="update-execute-btn btn btn-outline-success p-2 rounded-3 delete-btn">
                                                                                        <i
                                                                                            class="fa fa-download mr-1"></i>
                                                                                        {{ __('Update') }}
                                                                                    </a>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <div class="progress progress-striped active col-md-12 p-0"
                                                                    id="total-progress" role="progressbar"
                                                                    aria-valuemin="0" aria-valuemax="100"
                                                                    aria-valuenow="0">
                                                                    <div class="progress-bar progress-bar-success"
                                                                        style="width:0%;" data-dz-uploadprogress></div>
                                                                </div>
                                                                <div
                                                                    class="bold fw-bold text-center text-success upload-completed">
                                                                    <span>{{ __('Upload Completed') }}</span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        @if ($errors->has('update_file'))
                                                        <span class="text-danger"><i
                                                                class="fas fa-exclamation-triangle"></i>
                                                            {{ $errors->first('update_file') }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @if ($uploadedFile != '')
                                            <tr>
                                                <td>
                                                    {{ $uploadedFile }}
                                                    <a data-url="{{ route('super_admin.version-delete') }}"
                                                        data-reload="true"
                                                        class="btn btn-outline-danger p-1 rounded-3 delete">
                                                        <i class="fa fa-trash mr-1"></i>
                                                        {{ __('Delete') }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a data-url="{{ route('super_admin.version-update-execute') }}"
                                                        class="update-execute-btn btn btn-outline-success p-2 rounded-3">
                                                        <i class="fa fa-download mr-1"></i>
                                                        {{ __('Update') }}
                                                    </a>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content area end -->
@endsection
@push('script')
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script>
var versionUpdateStoreUrl = "{{ route('super_admin.version-update-store') }}";
</script>
<script src="{{ asset('super_admin/js/version-update.js') }}"></script>
@endpush