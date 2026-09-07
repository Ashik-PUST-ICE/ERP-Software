@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __('Edit Account') }} - {{ ucfirst($account->platform) }}
@endpush

@section('content')
<div class="section-title">
    <h2 class="title">{{ __('Edit Account') }}</h2>
    <a href="{{ route('admin.social.account.index') }}" class="primary-btn btn-outline-secondary">{{ __('Back to List') }}</a>
</div>

<div class="section-wrap">
    <div class="primary-form">
        <form action="{{ route('admin.social.account.update', $account) }}" method="POST" id="socialAccountEditForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="platform" value="{{ $account->platform }}">

            <div class="row gy-4">
                <div class="col-12">
                    <div class="form-group">
                        <label for="platform_display" class="form-label">{{ __('Platform') }}</label>
                        <input type="text" class="form-control" id="platform_display" value="{{ ucfirst($account->platform) }}" readonly disabled>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="account_id" class="form-label">{{ __('Account ID / Page ID') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" id="account_id" name="account_id"
                            value="{{ old('account_id', $account->account_id) }}" placeholder="{{ __('Enter Account ID') }}" required>
                        @error('account_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="form-label">{{ __('Account Name / Username') }}</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="{{ old('username', $account->username) }}" placeholder="{{ __('Enter username') }}">
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group">
                        <label for="access_token" class="form-label">{{ __('Access Token') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" id="access_token" name="access_token"
                            value="{{ old('access_token', $account->access_token) }}" placeholder="{{ __('Enter access token') }}" required>
                        @error('access_token')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if($account->platform === 'twitter')
                <div class="col-12">
                    <div class="form-group">
                        <label for="access_token_secret" class="form-label">{{ __('Access Token Secret') }}<span class="required">*</span></label>
                        <input type="text" class="form-control" id="access_token_secret" name="access_token_secret"
                            value="{{ old('access_token_secret', $account->permissions['access_token_secret'] ?? '') }}"
                            placeholder="{{ __('Enter access token secret') }}" required>
                        @error('access_token_secret')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="bearer_token" class="form-label">{{ __('Bearer Token') }}</label>
                        <input type="text" class="form-control" id="bearer_token" name="bearer_token"
                            value="{{ old('bearer_token', $account->settings['bearer_token'] ?? '') }}"
                            placeholder="{{ __('Enter bearer token (Twitter API v2)') }}">
                    </div>
                </div>
                @endif

                @if(in_array($account->platform, ['youtube']))
                <div class="col-12">
                    <div class="form-group">
                        <label for="refresh_token" class="form-label">{{ __('Refresh Token') }}</label>
                        <input type="text" class="form-control" id="refresh_token" name="refresh_token"
                            value="{{ old('refresh_token', $account->settings['refresh_token'] ?? '') }}"
                            placeholder="{{ __('Enter refresh token') }}">
                    </div>
                </div>
                @endif

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="is_active" class="form-label">{{ __('Status') }}</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1" {{ old('is_active', $account->is_active) ? 'selected' : '' }}>{{ __('Active') }}</option>
                            <option value="0" {{ !old('is_active', $account->is_active) ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="btn-list mt-4">
                <button type="submit" class="primary-btn">{{ __('Update Account') }}</button>
                <a href="{{ route('admin.social.account.index') }}" class="primary-btn btn-secondary">{{ __('Cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/js/social-media.js') }}"></script>
@endpush
