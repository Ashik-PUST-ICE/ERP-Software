<div class="modal fade primary-modal" id="twitter-connect-modal" tabindex="-1"
    aria-labelledby="twitter-connect-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="twitter-connect-modal-label">{{ __('Connect Twitter Account') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="primary-form">
                    <nav class="primry-tabs mb-4">
                        <div class="nav nav-tabs justify-content-center" id="twitter-nav-tab" role="tablist">
                            <button class="nav-link active" id="twitter-nav-oauth-tab" data-bs-toggle="tab"
                                data-bs-target="#twitter-pane-oauth" type="button" role="tab"
                                aria-controls="twitter-pane-oauth" aria-selected="true">
                                {{ __('Twitter Auth') }}
                            </button>
                            <button class="nav-link" id="twitter-nav-manual-tab" data-bs-toggle="tab"
                                data-bs-target="#twitter-pane-manual" type="button" role="tab"
                                aria-controls="twitter-pane-manual" aria-selected="false">
                                {{ __('Twitter Manual Token') }}
                            </button>
                        </div>
                    </nav>

                    <div class="tab-content" id="twitter-nav-tabContent">
                        <div class="tab-pane fade show active" id="twitter-pane-oauth" role="tabpanel"
                            aria-labelledby="twitter-nav-oauth-tab" tabindex="0">
                            <div class="section-wrap py-5 text-center">
                                <a href="{{ route('admin.social.account.twitter.redirect') }}"
                                    class="btn btn-primary connect-acnt">{{ __('Connect via Twitter') }}</a>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="twitter-pane-manual" role="tabpanel"
                            aria-labelledby="twitter-nav-manual-tab" tabindex="0">
                            <div class="section-wrap p-4">
                                <form action="{{ route('admin.social.account.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="platform" value="twitter">

                                    <div class="row gy-4 justify-content-center">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="twitter-account-id"
                                                    class="form-label">{{ __('Account ID / Page ID') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" id="twitter-account-id"
                                                    name="account_id" placeholder="{{ __('Enter account ID') }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label for="twitter-username"
                                                    class="form-label">{{ __('Account Name / Username') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" id="twitter-username"
                                                    name="username" placeholder="{{ __('Enter username') }}" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="twitter-access-token"
                                                    class="form-label">{{ __('Access Token') }}<span
                                                        class="required">*</span></label>
                                                <input type="text" class="form-control" id="twitter-access-token"
                                                    name="access_token" placeholder="{{ __('Enter access token') }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label for="twitter-bearer-token"
                                                    class="form-label">{{ __('Bearer Token') }}</label>
                                                <input type="text" class="form-control" id="twitter-bearer-token"
                                                    name="bearer_token" placeholder="{{ __('Enter bearer token') }}">
                                                <small class="form-text text-muted">
                                                    {{ __('Required for Twitter API v2. Get from Twitter Developer Console.') }}
                                                </small>
                                            </div>

                                            <div class="form-group">
                                                <label for="twitter-status"
                                                    class="form-label">{{ __('Status') }}</label>
                                                <select class="form-control wide select" id="twitter-status" name="is_active">
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