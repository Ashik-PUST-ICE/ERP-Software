<div class="modal fade primary-modal" id="youtube-connect-modal" tabindex="-1"
    aria-labelledby="youtube-connect-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="youtube-connect-modal-label">{{ __('Connect YouTube Account') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="primary-form">
                    <nav class="primry-tabs mb-4">
                        <div class="nav nav-tabs justify-content-center" id="youtube-nav-tab" role="tablist">
                            <button class="nav-link active" id="youtube-nav-oauth-tab" data-bs-toggle="tab"
                                data-bs-target="#youtube-pane-oauth" type="button" role="tab"
                                aria-controls="youtube-pane-oauth" aria-selected="true">
                                {{ __('YouTube Auth') }}
                            </button>
                            <button class="nav-link" id="youtube-nav-manual-tab" data-bs-toggle="tab"
                                data-bs-target="#youtube-pane-manual" type="button" role="tab"
                                aria-controls="youtube-pane-manual" aria-selected="false">
                                {{ __('YouTube Manual Token') }}
                            </button>
                        </div>
                    </nav>

                    <div class="tab-content" id="youtube-nav-tabContent">
                        <div class="tab-pane fade show active" id="youtube-pane-oauth" role="tabpanel"
                            aria-labelledby="youtube-nav-oauth-tab" tabindex="0">
                            <div class="section-wrap py-5 text-center">
                                <a href="{{ route('admin.social.account.youtube.redirect') }}"
                                    class="btn btn-primary connect-acnt">{{ __('Connect via YouTube') }}</a>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="youtube-pane-manual" role="tabpanel"
                            aria-labelledby="youtube-nav-manual-tab" tabindex="0">
                            <div class="section-wrap p-4">
                                <form action="{{ route('admin.social.account.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="platform" value="youtube">

                                    <div class="row gy-4 justify-content-center">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="youtube-account-id"
                                                    class="form-label">{{ __('Account ID / Page ID') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" id="youtube-account-id"
                                                    name="account_id" placeholder="{{ __('Enter account ID') }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label for="youtube-username"
                                                    class="form-label">{{ __('Account Name / Username') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" id="youtube-username"
                                                    name="username" placeholder="{{ __('Enter username') }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="youtube-access-token"
                                                    class="form-label">{{ __('Access Token') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" id="youtube-access-token"
                                                    name="access_token" placeholder="{{ __('Enter access token') }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label for="youtube-refresh-token"
                                                    class="form-label">{{ __('Refresh Token') }}</label>
                                                <input type="text" class="form-control" id="youtube-refresh-token"
                                                    name="refresh_token" placeholder="{{ __('Enter refresh token') }}">
                                                <small class="form-text text-muted">
                                                    {{ __('Required for YouTube API to refresh access token.') }}
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="youtube-status"
                                                    class="form-label">{{ __('Status') }}</label>
                                                <select class="form-control select wide" id="youtube-status" name="is_active">
                                                    <option value="1">{{ __('Active') }}</option>
                                                    <option value="0">{{ __('Inactive') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer d-flex justify-content-end mt-5">
                                        <button type="submit" class="primary-btn">{{ __('Save') }}</button>
                                        <button type="button" class="primary-btn btn-secondary"
                                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    style="background-color: #fce8e6; border-radius: 8px; padding: 15px; display: flex; align-items: flex-start; margin-top: 20px;">
                    <span
                        style="background-color: #e85d4d; color: white; font-weight: bold; padding: 2px 8px; border-radius: 4px; margin-right: 12px; font-size: 13px;">{{ __('Note') }}
                    </span>
                    <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.5;">
                        {{ __('Please be advised that the token obtained through the "Connect Auth" method has a limited expiration time. Ensure timely usage to avoid connection issues.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>