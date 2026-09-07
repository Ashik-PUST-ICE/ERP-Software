@extends('auto_posts.admin.layouts.admin')

@push('title')
{{ __($title) }}
@endpush
@section('content')

<!-- Page content area start -->
<div class="p-30">
    <input type="hidden" id="plan-history-route" value="{{ route('admin.billings.plan-history') }}">
    <input type="hidden" id="transaction-history-route" value="{{ route('admin.billings.transaction-history') }}">
    <div>
        <div class="section-title">
            <h2 class="title">My Plan</h2>

        </div>
        <div class="row gy-4 mb-20">
            <div class="col-lg-6">
                <div class="section-wrap my-plan-area h-100">
                    <div class="plan-head">
                        @if(!is_null($currentPackage) && !is_null($currentPackage->packageable))
                        <h3>Current Package</h3>
                        <h2>{{$currentPackage->packageable->name}}</h2>
                        <h3>Expired at {{$currentPackage->end_date}}</h3>
                        @else
                        <h3>Currently, You don't have any plan</h3>
                        @endif
                    </div>
                    @if(!is_null($currentPackage) && !is_null($currentPackage->packageable))
                    <div class="plan-body">
                        <h3>Package Info</h3>
                        <ul class="plan-features">
                            <li>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                        stroke="#141B34" stroke-width="0.875" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <strong>{{ __('Posts:') }}</strong>
                                {{$currentPackage->packageable->post_limit == 0 ? 'Unlimited' : $currentPackage->packageable->post_limit}}
                            </li>
                            <li>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                        stroke="#141B34" stroke-width="0.875" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <strong>{{ __('AI Enabled:') }}</strong>
                                {{$currentPackage->packageable->ai_enabled ? 'Yes' : 'No'}}
                            </li>
                            <li>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                        stroke="#141B34" stroke-width="0.875" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <strong>{{ __('Platfrom:') }}</strong>
                                @if($currentPackage->packageable->provider_limit &&
                                is_array($currentPackage->packageable->provider_limit) &&
                                count($currentPackage->packageable->provider_limit) > 0)
                                @php
                                $providers = SOCIAL_MEDIA_PLATFORMS;
                                $providerNames = [];
                                foreach($currentPackage->packageable->provider_limit as $providerId) {
                                if(isset($providers[$providerId])) {
                                $providerNames[] = $providers[$providerId];
                                }
                                }
                                @endphp
                                @if(count($providerNames) > 0)
                                @foreach($providerNames as $providerName)
                                <span class="badge package-badge me-1">{{ $providerName }}</span>
                                @endforeach
                                @else
                                <span class="text-muted">{{ __('N/A') }}</span>
                                @endif
                                @else
                                <span class="text-muted">{{ __('N/A') }}</span>
                                @endif
                            </li>
                        </ul>
                        @if($currentPackage->packageable->features && is_array($currentPackage->packageable->features)
                        && count($currentPackage->packageable->features) > 0)
                        <h3 class="mt-3">{{ __('Features') }}</h3>
                        <ul class="plan-features">
                            @foreach($currentPackage->packageable->features as $feature)
                            <li>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                        stroke="#141B34" stroke-width="0.875" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                {{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif
                    <div class="plan-footer">
                        <div class="btn-list">
                            <button type="button" class="primary-btn" data-bs-toggle="modal"
                                data-bs-target="#pricingModal">
                                @if(!is_null($currentPackage) && !is_null($currentPackage->packageable))
                                + Upgrade Plan
                                @else
                                + Buy a Plan
                                @endif
                            </button>
                            @if(!is_null($currentPackage) && !is_null($currentPackage->packageable))
                            <form id="cancelSubscriptionForm" method="POST"
                                action="{{ route('admin.billings.cancel') }}" class="d-inline"
                                data-confirm-title="{{ __('Sure! You want to cancel subscription?') }}"
                                data-confirm-text="{{ __("You won't be able to revert this!") }}"
                                data-confirm-btn="{{ __('Yes, Cancel It!') }}">
                                @csrf
                                <button type="button" class="primary-btn btn-outline"
                                    onclick="cancelSubscriptionModal()">{{ __('Cancel Subscription') }}</button>
                            </form>
                            @if(empty($refundRequest))
                            <button type="button" class="primary-btn btn-outline" data-bs-toggle="modal"
                                data-bs-target="#refundModal">{{ __('Request Refund') }}</button>
                            @else
                            @if($refundRequest->status == 0) {{-- STATUS_PENDING --}}
                            <button type="button" class="primary-btn btn-outline"
                                disabled>{{ __('Refund Pending') }}</button>
                            @elseif($refundRequest->status == 3) {{-- STATUS_REJECT --}}
                            <button type="button" class="primary-btn btn-outline" data-bs-toggle="modal"
                                data-bs-target="#refundModal">{{ __('Request Refund') }}</button>
                            @elseif($refundRequest->status == 1) {{-- STATUS_SUCCESS --}}
                            <button type="button" class="primary-btn btn-outline" disabled>{{ __('Refunded') }}</button>
                            @else
                            <button type="button" class="primary-btn btn-outline" data-bs-toggle="modal"
                                data-bs-target="#refundModal">{{ __('Request Refund') }}</button>
                            @endif
                            @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="section-wrap h-100">
                    <div class="section-small-title">
                        <h3 class="title">Plan History</h3>
                    </div>
                    <table class="display data-table primary-table" id="packageHistory">
                        <thead>
                            <tr>
                                <th class="keep-show">Plan Name</th>
                                <th>Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="section-wrap">
            <div class="section-small-title">
                <h3 class="title">Transaction History</h3>
            </div>
            <table class="display data-table primary-table" id="transactionHistory">
                <thead>
                    <tr>
                        <th class="keep-show">Transaction ID</th>
                        <th>Amount</th>
                        <th class="keep-show">Purpose</th>
                        <th>Payment Time</th>
                        <th class="keep-show">Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pricing Modal (Choose Plan) -->
<div class="modal fade primary-modal" id="pricingModal" tabindex="-1" aria-labelledby="pricingModalLabel"
    aria-hidden="true" data-current-subscription-type="{{ $currentPackage->subscription_type ?? '' }}">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h2 class="modal-title w-100 text-center" id="pricingModalLabel">{{ __('Choose A Plan') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="pricing-modal-body">
                    <div class="toggle-container">
                        <span class="toggle-label">{{ __('Monthly') }}</span>
                        <label class="switch">
                            <input type="checkbox" id="pricing-toggle"
                                data-monthly-type="{{ SUBSCRIPTION_TYPE_MONTHLY }}"
                                data-yearly-type="{{ SUBSCRIPTION_TYPE_YEARLY }}"
                                data-monthly-label="{{ __('Monthly') }}" data-yearly-label="{{ __('Yearly') }}">
                            <span class="slider round"></span>
                        </label>
                        <span class="toggle-label">{{ __('Yearly') }}</span>
                    </div>

                    <div class="pricing-grid">
                        @foreach($packages as $package)
                        @php
                        $cardClasses = 'pricing-card';
                        $headerGrad = 'basic-grad';
                        if ($loop->index === 1) {
                        $cardClasses .= ' featured';
                        $headerGrad = 'standard-grad';
                        } elseif ($loop->index === 2) {
                        $headerGrad = 'enterprise-grad';
                        }

                        $isCurrent = $currentPackage && $currentPackage->packageable && $currentPackage->packageable->id
                        == $package->id;
                        @endphp
                        <div class="{{ $cardClasses }} {{ $isCurrent ? 'current-plan' : '' }}"
                            data-package-id="{{ $package->id }}" data-package-name="{{ $package->name }}">
                            @if($loop->index === 1)
                            <span class="badge">{{ __('Popular') }}</span>
                            @endif
                            @if($isCurrent)
                            <span
                                class="badge bg-success">{{ $currentPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? __('Monthly') : __('Yearly') }}</span>
                            @endif
                            <div class="card-header {{ $headerGrad }}">
                                <h3>{{ $package->name }}</h3>
                                <p class="price">
                                    <span class="currency-symbol">{{ $defaultCurrencySymbol ?? '$' }}</span><span
                                        class="amount" data-monthly="{{ number_format($package->monthly_price, 2) }}"
                                        data-yearly="{{ number_format($package->yearly_price, 2) }}">
                                        {{ number_format($package->monthly_price, 2) }}
                                    </span>/<span class="cycle">{{ __('Monthly') }}</span>
                                </p>
                                @if($package->description)
                                <p class="mt-1 fs-14 text-muted">
                                    {{ \Illuminate\Support\Str::limit($package->description, 120) }}
                                </p>
                                @endif
                            </div>
                            <ul class="features">
                                @if($package->ai_enabled)
                                <li>{{ __('AI Features Enabled') }}</li>
                                @endif
                                @if($package->post_limit)
                                <li>{{ $package->post_limit }} {{ __('posts per day') }}</li>
                                @else
                                <li>{{ __('Unlimited posts') }}</li>
                                @endif
                                @if(isset($package->is_trail) && $package->is_trail == STATUS_ACTIVE)
                                <li>{{ __('Includes free trial') }}</li>
                                @endif
                                @if($package->provider_limit && is_array($package->provider_limit) &&
                                count($package->provider_limit) > 0)
                                @php
                                $providers = SOCIAL_MEDIA_PLATFORMS;
                                $providerNames = [];
                                foreach($package->provider_limit as $providerId) {
                                if(isset($providers[$providerId])) {
                                $providerNames[] = $providers[$providerId];
                                }
                                }
                                @endphp
                                @if(count($providerNames) > 0)
                                @foreach($providerNames as $providerName)
                                <li>{{ $providerName }}</li>
                                @endforeach
                                @endif
                                @endif
                                @if($package->features && is_array($package->features))
                                @foreach($package->features as $feature)
                                <li>{{ $feature }}</li>
                                @endforeach
                                @endif
                            </ul>

                            @if($isCurrent)
                            <button type="button" class="primary-btn btn-outline" disabled>
                                {{ __('Current Plan') }}
                            </button>
                            @else
                            <button type="button"
                                class="primary-btn btn-subscribe{{ $loop->index === 1 ? ' btn-outline' : '' }}">
                                {{ __('Subscribe Now') }}
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Method Modal -->
<div class="modal fade primary-modal" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h2 class="modal-title w-100 text-center">{{ __('Select Payment Method') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="billingPaymentForm" action="{{ route('admin.pricing.pay') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="payment-package-id">
                <input type="hidden" name="type" id="payment-subscription-type">
                <input type="hidden" id="amount" value="">
                <input type="hidden" id="getCurrencyByGatewayRoute" value="{{ route('admin.pricing.get.currency') }}">
                <input type="hidden" id="selectCurrencyLabel" value="{{ __('Select Currency') }}">
                <input type="hidden" id="currencyPlacement" value="{{ getCurrencyPlacement() }}">

                <div class="modal-body">
                    <div class="payment-container primary-form">
                        <div class="payment-details-card">
                            <h3>{{ __('Payment Details') }}</h3>
                            <div class="detail-row"><span>{{ __('Package Name') }}</span> <strong
                                    id="det-plan">-</strong>
                            </div>
                            <div class="detail-row"><span>{{ __('Package Type') }}</span> <strong
                                    id="det-type">-</strong>
                            </div>
                            <div class="detail-row"><span>{{ __('Amount') }}</span> <strong id="det-amount">-</strong>
                            </div>
                            <div id="currencyAppend" class="conversion-rates mt-3 pt-3"
                                style="border-top: 1px solid var(--border-color); display: none;">
                                <!-- Currencies loaded here when gateway selected -->
                            </div>
                            <div id="bankAppend" class="mt-3 pt-3 d-none"
                                style="border-top: 1px solid var(--border-color);">
                                <h4 class="mb-3 fs-16">{{ __('Bank Deposit') }}</h4>
                                <div class="form-group">
                                    <label for="payment-bank-select" class="form-label">{{ __('Bank Name') }}</label>
                                    <!-- form-select -->
                                    <select name="bank_id" id="payment-bank-select" class="form-control select2-active">
                                        <option value="">{{ __('Select Option') }}</option>
                                    </select>
                                </div>
                                <div id="bank-details-box" class="form-group mb-3 d-none">
                                    <label class="form-label">{{ __('Bank Details') }}</label>
                                    <div id="bank-details-content" class="text-muted detail-row" style="text-align: justify"></div>
                                </div>
                                <div class="form-group">
                                    <label for="deposit-slip-input" class="form-label">{{ __('Upload Deposit Slip') }}
                                        (image, pdf)</label>
                                    <input type="file" name="deposit_slip" id="deposit-slip-input" class="form-control"
                                        accept="image/*,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="gateway-grid" id="gateway-list">
                            @foreach($gateways as $gateway)
                            <div class="gateway-item gateway-option" data-gateway-id="{{ $gateway->id }}"
                                data-gateway-slug="{{ $gateway->slug }}">
                                <span class="gate-label">{{ $gateway->title }}</span>
                                @if($gateway->icon)
                                <img src="{{ $gateway->icon }}" alt="{{ $gateway->title }}">
                                @endif
                                <button type="button" class="select-gate-btn" data-select-label="{{ __('Select') }}"
                                    data-selected-label="{{ __('Selected') }}">
                                    {{ __('Select') }}
                                </button>
                                <input type="radio" name="gateway" value="{{ $gateway->id }}" class="d-none">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="submit" class="primary-btn btn-subscribe btn-outline">
                        {{ __('Pay Now') }} <span id="footer-amt">({{ $defaultCurrencySymbol ?? '$' }} 0.00)</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Refund Request Modal -->
<div class="modal fade primary-modal" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h2 class="modal-title w-100 text-center" id="refundModalLabel">{{ __('Request Refund') }}</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.subscription.refund-request') }}" method="POST" class="ajax"
                data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="reasons" class="form-label">{{ __('Reasons for Refund') }}</label>
                        <textarea name="reasons" id="reasons" class="form-control" rows="4"
                            placeholder="{{ __('Please explain why you are requesting a refund...') }}"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="submit" class="primary-btn">{{ __('Submit Request') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
@push('style')
<link rel="stylesheet" href="{{ asset('admin/css/billing.css') }}">
@endpush
@push('script')
<script src="{{ asset('admin/js/billings.js') }}"></script>
@endpush