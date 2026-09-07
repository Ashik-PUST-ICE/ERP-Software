@extends('auto_posts.admin.layouts.admin')
@push('title')
{{ $title ?? __('Timezone Settings') }}
@endpush
@section('content')
<div class="section-title">
    <h2 class="title">{{ __($title ?? 'Timezone Settings') }}</h2>
</div>
<div class="section-wrap">
    <div class="form-wrapper">
        <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
            <p class="text-muted mb-4">{{ __('Set the default timezone for scheduling posts and campaigns. All scheduled times will be stored and processed using this timezone.') }}</p>
            <form id="timezone-settings-form" class="ajax primary-form" method="POST" action="{{ route('admin.settings.timezone.update') }}" data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="row gy-4">
                    <div class="col-xl-8 col-lg-8 col-md-12">
                        <div class="form-group">
                            <label for="app_timezone" class="form-label">{{ __('Application Timezone') }} <span class="required">*</span></label>
                            <select name="app_timezone" id="app_timezone" class="form-control select wide" required>
                                @foreach($timezones as $tz)
                                    <option value="{{ $tz }}" {{ ($app_timezone ?? 'UTC') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">{{ __('This timezone is used when creating and displaying scheduled posts and campaign times.') }}</small>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="primary-btn">{{ __('Save Timezone') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
