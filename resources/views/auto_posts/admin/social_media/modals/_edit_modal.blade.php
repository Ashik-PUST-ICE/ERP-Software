{{-- Edit Account Modal - include with @include('...modals._edit_modal', ['platform' => $platform]) --}}
<div class="modal fade primary-modal" id="{{ $platform }}-edit-modal" tabindex="-1"
    aria-labelledby="{{ $platform }}-edit-modal-label" aria-hidden="true" data-platform="{{ $platform }}">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="{{ $platform }}-edit-modal-label">{{ __('Edit') }} {{ ucfirst($platform) }}
                    {{ __('Account') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="primary-form">
                    <nav class="primry-tabs mb-4">
                        <div class="nav nav-tabs justify-content-center" id="{{ $platform }}-edit-nav-tab"
                            role="tablist">
                            <button class="nav-link active" id="{{ $platform }}-edit-nav-oauth-tab" data-bs-toggle="tab"
                                data-bs-target="#{{ $platform }}-edit-pane-oauth" type="button" role="tab"
                                aria-controls="{{ $platform }}-edit-pane-oauth" aria-selected="true">
                                {{ __(ucfirst($platform) . ' Auth') }}
                            </button>
                            <button class="nav-link" id="{{ $platform }}-edit-nav-manual-tab" data-bs-toggle="tab"
                                data-bs-target="#{{ $platform }}-edit-pane-manual" type="button" role="tab"
                                aria-controls="{{ $platform }}-edit-pane-manual" aria-selected="false">
                                {{ __(ucfirst($platform) . ' Manual Token') }}
                            </button>
                        </div>
                    </nav>

                    <div class="tab-content" id="{{ $platform }}-edit-nav-tabContent">
                        {{-- OAuth Reconnect Tab --}}
                        <div class="tab-pane fade show active" id="{{ $platform }}-edit-pane-oauth" role="tabpanel"
                            aria-labelledby="{{ $platform }}-edit-nav-oauth-tab" tabindex="0">
                            <div class="section-wrap py-5 text-center">
                                <div class="mb-4">
                                    <p class="text-muted">
                                        {{ __('Reconnect your account via OAuth to refresh your access token.') }}</p>
                                </div>
                                <a href="{{ route('admin.social.account.' . $platform . '.redirect') }}"
                                    class="btn btn-primary connect-acnt">{{ __('Reconnect via') }}
                                    {{ __(ucfirst($platform)) }}</a>
                            </div>

                            <div
                                style="background-color: #fce8e6; border-radius: 8px; padding: 15px; display: flex; align-items: flex-start; margin-top: 20px;">
                                <span
                                    style="background-color: #e85d4d; color: white; font-weight: bold; padding: 2px 8px; border-radius: 4px; margin-right: 12px; font-size: 13px;">{{ __('Note') }}</span>
                                <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.5;">
                                    {{ __('Please be advised that the token obtained through the "Connect Auth" method has a limited expiration time. Ensure timely usage to avoid connection issues.') }}
                                </p>
                            </div>
                        </div>

                        {{-- Manual Token Tab --}}
                        <div class="tab-pane fade" id="{{ $platform }}-edit-pane-manual" role="tabpanel"
                            aria-labelledby="{{ $platform }}-edit-nav-manual-tab" tabindex="0">
                            <div class="section-wrap p-4">
                                <form id="{{ $platform }}-edit-form" class="social-account-edit-form" method="POST"
                                    action=""
                                    data-update-url-template="{{ route('admin.social.account.update', ['account' => '__ID__']) }}">
                                    @csrf
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="platform" value="{{ $platform }}">

                                    <div class="row gy-4 justify-content-center">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Account ID / Page ID') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" name="account_id" required
                                                    placeholder="{{ __('Enter Account ID') }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Account Name / Username') }}</label>
                                                <input type="text" class="form-control" name="username"
                                                    placeholder="{{ __('Enter username') }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Access Token') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" name="access_token" required
                                                    placeholder="{{ __('Enter access token') }}">
                                            </div>

                                            @if($platform === 'twitter')
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Access Token Secret') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" name="access_token_secret"
                                                    required placeholder="{{ __('Enter access token secret') }}">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Bearer Token') }}</label>
                                                <input type="text" class="form-control" name="bearer_token"
                                                    placeholder="{{ __('Enter bearer token') }}">
                                            </div>
                                            @endif

                                            @if($platform === 'youtube')
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Refresh Token') }}</label>
                                                <input type="text" class="form-control" name="refresh_token"
                                                    placeholder="{{ __('Enter refresh token') }}">
                                            </div>
                                            @endif

                                            @if(in_array($platform, ['linkedin']))
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Page ID') }}</label>
                                                <input type="text" class="form-control" name="page_id"
                                                    placeholder="{{ __('Enter Page ID') }}">
                                            </div>
                                            @endif

                                            @if(in_array($platform, ['instagram', 'threads', 'tiktok', 'linkedin']))
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Email') }}</label>
                                                <input type="email" class="form-control" name="email"
                                                    placeholder="{{ __('Enter email') }}">
                                            </div>
                                            @endif

                                            <div class="form-group">
                                                <label class="form-label">{{ __('Status') }}</label>
                                                <select class="select form-control wide" name="is_active">
                                                    <option value="1">{{ __('Active') }}</option>
                                                    <option value="0">{{ __('Inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer justify-content-end mt-4">
                                        <button type="button" class="primary-btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                        <button type="submit" class="primary-btn">{{ __('Update Account') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>