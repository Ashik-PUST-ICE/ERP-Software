@extends('auto_posts.super_admin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ $title }}</h2>
</div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title">{{ __('Application Settings') }}</h3>
            </div>
            <div class="primary-form">
                <form id="application-settings-form" class="ajax" action="{{ route('super_admin.setting.application-settings.update') }}"
                    method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                    @csrf
                    <div class="row gy-4">
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_name" class="form-label">{{ __('App Name') }}<span class="required">*</span></label>
                                <input type="text" id="app_name" name="app_name" value="{{ getOption('app_name') }}" class="form-control" placeholder="{{ __('App Name') }}" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="APP_URL" class="form-label">{{ __('App URL') }}<span class="required">*</span></label>
                                <input type="text" id="APP_URL" name="APP_URL" value="{{ getOption('APP_URL') }}" class="form-control" placeholder="{{ __('App URL') }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_email" class="form-label">{{ __('App Email') }}<span class="required">*</span></label>
                                <input type="email" id="app_email" name="app_email" value="{{ getOption('app_email') }}" class="form-control" placeholder="{{ __('App Email') }}" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_contact_number" class="form-label">{{ __('App Contact Number') }}<span class="required">*</span></label>
                                <input type="text" id="app_contact_number" name="app_contact_number" value="{{ getOption('app_contact_number') }}" class="form-control" placeholder="{{ __('App Contact Number') }}" required>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_location" class="form-label">{{ __('App Location') }}<span class="required">*</span></label>
                                <input type="text" id="app_location" name="app_location" value="{{ getOption('app_location') }}" class="form-control" placeholder="{{ __('App Location') }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_timezone" class="form-label">{{ __('Timezone') }}<span class="required">*</span></label>
                                <select name="app_timezone" class="select form-control wide" id="app_timezone" required>
                                    <option value="">{{ __('Select Timezone') }}</option>
                                    @foreach ($timezones as $timezone)
                                        <option value="{{ $timezone }}" {{ $timezone == getOption('app_timezone') ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_copyright" class="form-label">{{ __('App Copyright') }}<span class="required">*</span></label>
                                <input type="text" id="app_copyright" name="app_copyright" value="{{ getOption('app_copyright') }}" class="form-control" placeholder="{{ __('App Copyright') }}">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="app_developed" class="form-label">{{ __('Developed By') }}<span class="required">*</span></label>
                                <input type="text" id="app_developed" name="app_developed" value="{{ getOption('app_developed') }}" class="form-control" placeholder="{{ __('Developed By') }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="application-settings-form" class="primary-btn">{{ __('Save') }}</button>
            </div>

            <!-- Currency Settings Section -->
</div>
@endsection
