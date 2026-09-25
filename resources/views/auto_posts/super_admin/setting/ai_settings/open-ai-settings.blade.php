@extends('auto_posts.super_admin.layouts.app')
@push('title'){{ $title }}@endpush
@section('content')
<div class="section-title"><h2 class="title">{{ $title }}</h2></div>
<div class="settings-page-area">
    @include('auto_posts.super_admin.setting.partials.general-sidebar')
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title"><h3 class="title">{{ __('AI Chatbot') }}</h3></div>
            <div class="primary-form">
                <form id="ai-settings-form" class="ajax" action="{{ route('super_admin.setting.ai-settings.update') }}" method="POST" data-handler="settingCommonHandler">@csrf
                @php($selectedProvider = getOption('ai_provider', config('ai.default_provider', 'openai')))
                @php($selectedConfig = $providers[$selectedProvider] ?? reset($providers))
                @php($selectedModel = getOption('ai_model', getOption($selectedProvider . '_model', $selectedConfig['default_model'] ?? '')))
                <div class="row gy-4">
                    <div class="col-xl-4 col-lg-5 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="openai_ai_status">{{ __('Chatbot status') }} <span class="required">*</span></label>
                            <select name="openai_ai_status" id="openai_ai_status" class="form-control" required>
                                <option value="1" @selected(getOption('openai_ai_status', 1) == 1)>{{ __('Active') }}</option>
                                <option value="0" @selected(getOption('openai_ai_status', 1) == 0)>{{ __('Inactive') }}</option>
                            </select>
                            <small class="text-muted">{{ __('Enable or disable the AI chatbot for users.') }}</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-7 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="ai_provider">{{ __('AI provider') }} <span class="required">*</span></label>
                            <select name="ai_provider" id="ai_provider" class="form-control" required>
                                @foreach($providers as $key => $provider)
                                    <option value="{{ $key }}" @selected($selectedProvider === $key)>{{ $provider['label'] }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">{{ __('Choose which provider powers the chatbot.') }}</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="ai_model">{{ __('Chat model') }} <span class="required">*</span></label>
                            <select name="ai_model" id="ai_model" class="form-control" required></select>
                            <small class="text-muted">{{ __('The selected provider model will be used.') }}</small>
                        </div>
                    </div>
                    <div class="col-xl-8 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="provider_api_key">{{ __('Provider API key') }} <span class="required">*</span></label>
                            <div class="password-input-wrap">
                                <input type="password" name="provider_api_key" id="provider_api_key" class="form-control" value="{{ getOption($selectedConfig['api_key_option'], '') }}" placeholder="Paste selected provider API key" autocomplete="new-password">
                                <button type="button" class="password-toggle-btn" id="toggleApiKeyVisibility" title="{{ __('Show/Hide API key') }}">
                                    <i class="fa fa-eye" id="apiKeyEyeIcon"></i>
                                </button>
                            </div>
                            <small id="provider-help" class="text-muted">{{ __('The key stays on your server and is never shown to chatbot users.') }}</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="openai_temperature">{{ __('Temperature') }}</label>
                            <input type="number" name="openai_temperature" id="openai_temperature" class="form-control" value="{{ getOption('openai_temperature', config('ai.openai_default_temperature', 0.7)) }}" min="0" max="2" step="0.1">
                            <small class="text-muted">{{ __('Controls randomness. 0 = deterministic, 2 = very random.') }}</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="openai_max_tokens">{{ __('Max tokens') }}</label>
                            <input type="number" name="openai_max_tokens" id="openai_max_tokens" class="form-control" value="{{ getOption('openai_max_tokens', config('ai.openai_default_max_tokens', 1000)) }}" min="100" max="4096">
                            <small class="text-muted">{{ __('Maximum response length.') }}</small>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="openai_default_language">{{ __('Default language') }}</label>
                            <input type="text" name="openai_default_language" id="openai_default_language" class="form-control" value="{{ getOption('openai_default_language', '') }}" maxlength="20" placeholder="en">
                            <small class="text-muted">{{ __('ISO language code, e.g. en, bn.') }}</small>
                        </div>
                    </div>
                </div>
                <div class="row gy-4 mt-1">
                    <div class="col-12">
                        <div class="form-group">
                            <button type="submit" class="primary-btn">{{ __('Save settings') }}</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    .password-input-wrap {
        position: relative;
    }
    .password-input-wrap .form-control {
        padding-right: 48px;
    }
    .password-input-wrap .password-toggle-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #6c757d;
        padding: 6px;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
    }
    .password-input-wrap .password-toggle-btn:hover {
        color: #0f172a;
    }
</style>
@endpush

@push('script')
<script>
(function() {
    'use strict';

    const providers = @json($providers);
    const providerSelect = document.getElementById('ai_provider');
    const modelSelect = document.getElementById('ai_model');
    const apiKeyInput = document.getElementById('provider_api_key');
    const toggleBtn = document.getElementById('toggleApiKeyVisibility');
    const eyeIcon = document.getElementById('apiKeyEyeIcon');
    const temperatureInput = document.getElementById('openai_temperature');
    const maxTokensInput = document.getElementById('openai_max_tokens');
    const languageInput = document.getElementById('openai_default_language');
    const selectedProvider = '{{ $selectedProvider }}';
    const selectedModel = @json($selectedModel);
    let lastProviderValue = providerSelect ? providerSelect.value : null;

    function rebuildNiceSelect(selectEl) {
        if (!window.jQuery || !jQuery().niceSelect) {
            return;
        }
        const $select = jQuery(selectEl);
        if ($select.next('.nice-select').length) {
            $select.niceSelect('destroy');
        }
        $select.niceSelect();
    }

    function update() {
        if (!providerSelect || !modelSelect) {
            return;
        }

        const providerKey = providerSelect.value;
        const providerConfig = providers[providerKey] || {};
        const models = providerConfig.models || [];

        modelSelect.innerHTML = '';
        models.forEach(function(modelName) {
            const option = document.createElement('option');
            option.value = modelName;
            option.textContent = modelName;
            modelSelect.appendChild(option);
        });

        const defaultModel = providerKey === selectedProvider ? selectedModel : (providerConfig.default_model || '');
        modelSelect.value = defaultModel;

        if (apiKeyInput) {
            if (providerKey !== lastProviderValue) {
                apiKeyInput.value = '';
            }
            apiKeyInput.placeholder = 'Paste ' + (providerConfig.label || 'provider') + ' API key';
        }

        if (temperatureInput && providerConfig.default_temperature !== undefined) {
            temperatureInput.value = providerConfig.default_temperature;
        }
        if (maxTokensInput && providerConfig.default_max_tokens !== undefined) {
            maxTokensInput.value = providerConfig.default_max_tokens;
        }
        if (languageInput && providerConfig.default_language !== undefined) {
            languageInput.value = providerConfig.default_language;
        }

        rebuildNiceSelect(modelSelect);
    }

    function bindProviderChange() {
        if (!providerSelect) {
            return;
        }

        jQuery(providerSelect).off('change.aiProvider').on('change.aiProvider', function() {
            lastProviderValue = providerSelect.value;
            update();
        });
    }

    function bindPollingFallback() {
        if (!providerSelect) {
            return;
        }
        setInterval(function() {
            if (providerSelect.value !== lastProviderValue) {
                lastProviderValue = providerSelect.value;
                update();
            }
        }, 150);
    }

    function bindApiKeyToggle() {
        if (!toggleBtn || !apiKeyInput || !eyeIcon) {
            return;
        }

        toggleBtn.addEventListener('click', function() {
            const isPassword = apiKeyInput.type === 'password';
            apiKeyInput.type = isPassword ? 'text' : 'password';
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    }

    function init() {
        bindProviderChange();
        bindPollingFallback();
        bindApiKeyToggle();
        update();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
