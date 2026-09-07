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
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title">{{ __('Payment Gateways') }}</h3>
                    <a title="{{__('Sync missing gateway')}}" href="{{ route('super_admin.setting.gateway.syncs') }}"
                        class="primary-btn d-none d-sm-inline-flex align-items-center"
                        onclick="return confirm('{{ __('Are you sure you want to sync gateways?') }}');">
                        <i class="fa fa-sync-alt me-2"></i>{{ __('Sync Gateways') }}
                    </a>
                </div>
            </div>
            <input type="hidden" id="language-route" value="{{ route('super_admin.setting.languages.index') }}">
            <div class="row gy-4">
                @foreach ($gateways as $gateway)
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6">
                    <div class="single-payment">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item edit" href="javascript:void(0)"
                                        data-id="{{ $gateway->id }}">
                                        <i class="fa-solid fa-pen me-2"></i>{{ __('Edit') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <img class="logo" src="{{ asset($gateway->image) }}" alt="{{ $gateway->title }}">
                       <div class="bottom-status">
                         <span class="status {{ $gateway->status == ACTIVE ? 'active' : 'deactivate' }}">
                            {{ $gateway->status == ACTIVE ? __('Active') : __('Deactivate') }}
                        </span>
                        @if($gateway->slug != 'bank' && $gateway->mode)
                        <span
                            class="status mode-status {{ $gateway->mode == GATEWAY_MODE_LIVE ? 'active' : 'deactivate' }}">
                            {{ $gateway->mode == GATEWAY_MODE_LIVE ? __('Live') : __('Sandbox') }}
                        </span>
                        @endif
                       </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
{{-- Modal --}}
<div class="modal fade zModalTwo" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax" action="{{ route('super_admin.setting.gateway.store') }}" method="POST"
                data-handler="responseOnGatewaStore">
                @csrf
                <input type="hidden" name="id" id="id" required>
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0" id="editModalLabel">{{ __('Edit Gateway') }}
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="primary-form">
                        <div class="row gy-3">
                            <!-- Gateway Logo -->
                            <div class="col-12 text-center mb-3">
                                <div class="form-group mb-0">
                                    <div class="upload-profile-photo-box">
                                        <div class="profile-user position-relative d-inline-block">
                                            <img src="" class="image gateway-logo-preview" alt="Gateway Logo"
                                                style="max-width: 120px; max-height: 60px; object-fit: contain;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Basic Information -->
                            <div class="col-12">
                                <h6 class="text-muted mb-3 fw-600">{{ __('Basic Information') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gateway-title" class="form-label">{{ __('Title') }}</label>
                                    <input type="text" class="form-control title" id="gateway-title" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gateway-slug" class="form-label">{{ __('Slug') }}</label>
                                    <input type="text" name="slug" class="form-control slug" id="gateway-slug" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label">{{ __('Status') }}</label>
                                    <select name="status" id="status"
                                        class="select form-control wide sf-select-without-search">
                                        <option value="0">{{ __('Deactivate') }}</option>
                                        <option value="1">{{ __('Active') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mode-div">
                                <div class="form-group">
                                    <label for="mode" class="form-label">{{ __('Mode') }}</label>
                                    <select name="mode" id="mode"
                                        class="select form-control wide sf-select-without-search">
                                        <option value="1">{{ __('Live') }}</option>
                                        <option value="2">{{ __('Sandbox') }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="col-12 bank-div">
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-muted mb-0 fw-600">{{ __('Bank Information') }}</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-bank"
                                        title="{{ __('Add Bank') }}">
                                        <i class="fa fa-plus me-1"></i>{{ __('Add Bank') }}
                                    </button>
                                </div>
                                <div class="bank-div-append"></div>
                            </div>

                            <!-- API Configuration -->
                            <div class="col-12 url-div key-secret-div">
                                <hr class="my-3">
                                <h6 class="text-muted mb-3 fw-600">{{ __('API Configuration') }}</h6>
                                <div id="api-configuration-fields">
                                    <div class="form-group gateway-input d-none" id="gateway-url">
                                        <label for="gateway-url-input"
                                            class="form-label gateway-field-label">{{ __('Url') }}
                                            /{{ __('Hash') }}</label>
                                        <input class="form-control" type="text" name="url" id="gateway-url-input"
                                            placeholder="{{ __('Enter API URL or Hash') }}">
                                    </div>
                                    <div class="form-group gateway-input d-none" id="gateway-key">
                                        <label for="gateway-key-input"
                                            class="form-label gateway-field-label">{{ __('Key') }}</label>
                                        <input class="form-control" type="text" name="key" id="gateway-key-input"
                                            placeholder="{{ __('Enter API Key') }}">
                                        <small
                                            class="form-text text-muted d-none small">{{ __('Client id, Public Key, Key, Store id, Api Key') }}</small>
                                    </div>
                                    <div class="form-group gateway-input d-none" id="gateway-secret">
                                        <label for="gateway-secret-input"
                                            class="form-label gateway-field-label">{{ __('Secret') }}</label>
                                        <input class="form-control" type="password" name="secret"
                                            id="gateway-secret-input" placeholder="{{ __('Enter API Secret') }}">
                                        <small
                                            class="form-text text-muted d-none small">{{ __('Client Secret, Secret, Store Password, Auth Token') }}</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Currency Conversion -->
                            <div class="col-12">
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-muted mb-0 fw-600">{{ __('Conversion Rate') }}</h6>
                                    <button type="button" class="primary-btn btn-outline add-currency"
                                        title="{{ __('Add Currency') }}">
                                        <i class="fa fa-plus me-1"></i>{{ __('Add Currency') }}
                                    </button>
                                </div>
                                <div id="currencyConversionRateSection"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"
                            title="{{ __('Cancel') }}">{{ __('Cancel') }}</button>
                        <button type="submit" class="primary-btn" title="{{ __('Update') }}">{{ __('Update') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="getInfoRoute" value="{{ route('super_admin.setting.gateway.get.info') }}">
<input type="hidden" id="getCurrencySymbol" value="{{ getCurrencySymbol() }}">
<input type="hidden" id="allCurrency" value="{{ json_encode(getCurrency()) }}">
<input type="hidden" id="gatewaySettings" value="{{ gatewaySettings() }}">
<input type="hidden" id="supportedCurrency" value="{{json_encode(getGatewaySupportedCurrencies())}}">
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('super_admin/css/gateway.css') }}">
@endpush

@push('script')
<script src="{{ asset('admin/js/gateway.js') }}"></script>
@endpush