<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0">{{ __('Mail Configuration') }}</h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<form class="ajax" action="{{ route('super_admin.setting.settings_env.update') }}" method="POST"
    enctype="multipart/form-data" data-handler="commonResponseForModal">
    @csrf
    
    <div class="primary-form">
        <div class="row gy-3">
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL MAILER') }}<span class="required">*</span></label>
                    <input type="text" name="MAIL_MAILER" value="{{ env('MAIL_MAILER') }}"
                        class="form-control" placeholder="{{ __('MAIL MAILER') }}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL HOST') }}<span class="required">*</span></label>
                    <input type="text" name="MAIL_HOST" value="{{ env('MAIL_HOST') }}"
                        class="form-control" placeholder="{{ __('MAIL HOST') }}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL PORT') }}<span class="required">*</span></label>
                    <input type="text" name="MAIL_PORT" value="{{ env('MAIL_PORT') }}"
                        class="form-control" placeholder="{{ __('MAIL PORT') }}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL USERNAME') }}<span class="required">*</span></label>
                    <input type="text" name="MAIL_USERNAME" value="{{ env('MAIL_USERNAME') }}"
                        class="form-control" placeholder="{{ __('MAIL USERNAME') }}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL PASSWORD') }}<span class="required">*</span></label>
                    <input type="password" name="MAIL_PASSWORD" value="{{ env('MAIL_PASSWORD') }}"
                        class="form-control" placeholder="{{ __('MAIL PASSWORD') }}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label for="MAIL_ENCRYPTION" class="form-label">{{ __('MAIL ENCRYPTION') }}<span class="required">*</span></label>
                    <select name="MAIL_ENCRYPTION" class="form-control">
                        <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>
                            {{ __('tls') }}
                        </option>
                        <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected' : '' }}>
                            {{ __('ssl') }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL FROM ADDRESS') }}<span class="required">*</span></label>
                    <input type="text" name="MAIL_FROM_ADDRESS" value="{{ env('MAIL_FROM_ADDRESS') }}"
                        class="form-control" placeholder="{{ __('MAIL FROM ADDRESS') }}">
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
                <div class="form-group">
                    <label class="form-label">{{ __('MAIL FROM NAME') }}<span class="required">*</span></label>
                    <input type="text" name="MAIL_FROM_NAME" value="{{ env('MAIL_FROM_NAME') }}"
                        class="form-control" placeholder="{{ __('MAIL FROM NAME') }}">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Buttons -->
    <div class="btn-list mt-4 pt-3 border-top">
        <button type="button" class="primary-btn" data-bs-dismiss="modal">{{ __('Close') }}</button>
        <button type="button" class="primary-btn" id="sendTestMailBtn">{{ __('Send Test Mail') }}</button>
        <button type="submit" class="primary-btn">{{ __('Save') }}</button>
    </div>
</form>
