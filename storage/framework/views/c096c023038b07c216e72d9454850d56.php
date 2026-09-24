<?php $__env->startPush('title'); ?>
<?php echo e(__($title)); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>

<!-- Page content area start -->
<div class="p-30">
    <input type="hidden" id="plan-history-route" value="<?php echo e(route('admin.billings.plan-history')); ?>">
    <input type="hidden" id="transaction-history-route" value="<?php echo e(route('admin.billings.transaction-history')); ?>">
    <div>
        <div class="section-title">
            <h2 class="title">My Plan</h2>

        </div>
        <div class="row gy-4 mb-20">
            <div class="col-lg-6">
                <div class="section-wrap my-plan-area h-100">
                    <div class="plan-head">
                        <?php if(!is_null($currentPackage) && !is_null($currentPackage->packageable)): ?>
                        <h3>Current Package</h3>
                        <h2><?php echo e($currentPackage->packageable->name); ?></h2>
                        <h3>Expired at <?php echo e($currentPackage->end_date); ?></h3>
                        <?php else: ?>
                        <h3>Currently, You don't have any plan</h3>
                        <?php endif; ?>
                    </div>
                    <?php if(!is_null($currentPackage) && !is_null($currentPackage->packageable)): ?>
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
                                <strong><?php echo e(__('AI Enabled:')); ?></strong>
                                <?php echo e($currentPackage->packageable->ai_enabled ? 'Yes' : 'No'); ?>

                            </li>
                        </ul>
                        <?php if($currentPackage->packageable->features && is_array($currentPackage->packageable->features)
                        && count($currentPackage->packageable->features) > 0): ?>
                        <h3 class="mt-3"><?php echo e(__('Features')); ?></h3>
                        <ul class="plan-features">
                            <?php $__currentLoopData = $currentPackage->packageable->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                        stroke="#141B34" stroke-width="0.875" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                <?php echo e($feature); ?>

                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <div class="plan-footer">
                        <div class="btn-list">
                            <button type="button" class="primary-btn" data-bs-toggle="modal"
                                data-bs-target="#pricingModal">
                                <?php if(!is_null($currentPackage) && !is_null($currentPackage->packageable)): ?>
                                + Upgrade Plan
                                <?php else: ?>
                                + Buy a Plan
                                <?php endif; ?>
                            </button>
                            <?php if(!is_null($currentPackage) && !is_null($currentPackage->packageable)): ?>
                            <form id="cancelSubscriptionForm" method="POST"
                                action="<?php echo e(route('admin.billings.cancel')); ?>" class="d-inline"
                                data-confirm-title="<?php echo e(__('Sure! You want to cancel subscription?')); ?>"
                                data-confirm-text="<?php echo e(__("You won't be able to revert this!")); ?>"
                                data-confirm-btn="<?php echo e(__('Yes, Cancel It!')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="button" class="primary-btn btn-outline"
                                    onclick="cancelSubscriptionModal()"><?php echo e(__('Cancel Subscription')); ?></button>
                            </form>
                            <?php if(empty($refundRequest)): ?>
                            <button type="button" class="primary-btn btn-outline" data-bs-toggle="modal"
                                data-bs-target="#refundModal"><?php echo e(__('Request Refund')); ?></button>
                            <?php else: ?>
                            <?php if($refundRequest->status == 0): ?> 
                            <button type="button" class="primary-btn btn-outline"
                                disabled><?php echo e(__('Refund Pending')); ?></button>
                            <?php elseif($refundRequest->status == 3): ?> 
                            <button type="button" class="primary-btn btn-outline" data-bs-toggle="modal"
                                data-bs-target="#refundModal"><?php echo e(__('Request Refund')); ?></button>
                            <?php elseif($refundRequest->status == 1): ?> 
                            <button type="button" class="primary-btn btn-outline" disabled><?php echo e(__('Refunded')); ?></button>
                            <?php else: ?>
                            <button type="button" class="primary-btn btn-outline" data-bs-toggle="modal"
                                data-bs-target="#refundModal"><?php echo e(__('Request Refund')); ?></button>
                            <?php endif; ?>
                            <?php endif; ?>
                            <?php endif; ?>
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
    aria-hidden="true" data-current-subscription-type="<?php echo e($currentPackage->subscription_type ?? ''); ?>">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h2 class="modal-title w-100 text-center" id="pricingModalLabel"><?php echo e(__('Choose A Plan')); ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="pricing-modal-body">
                    <div class="toggle-container">
                        <span class="toggle-label"><?php echo e(__('Monthly')); ?></span>
                        <label class="switch">
                            <input type="checkbox" id="pricing-toggle"
                                data-monthly-type="<?php echo e(SUBSCRIPTION_TYPE_MONTHLY); ?>"
                                data-yearly-type="<?php echo e(SUBSCRIPTION_TYPE_YEARLY); ?>"
                                data-monthly-label="<?php echo e(__('Monthly')); ?>" data-yearly-label="<?php echo e(__('Yearly')); ?>">
                            <span class="slider round"></span>
                        </label>
                        <span class="toggle-label"><?php echo e(__('Yearly')); ?></span>
                    </div>

                    <div class="pricing-grid">
                        <?php $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
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
                        ?>
                        <div class="<?php echo e($cardClasses); ?> <?php echo e($isCurrent ? 'current-plan' : ''); ?>"
                            data-package-id="<?php echo e($package->id); ?>" data-package-name="<?php echo e($package->name); ?>">
                            <?php if($loop->index === 1): ?>
                            <span class="badge"><?php echo e(__('Popular')); ?></span>
                            <?php endif; ?>
                            <?php if($isCurrent): ?>
                            <span
                                class="badge bg-success"><?php echo e($currentPackage->subscription_type == SUBSCRIPTION_TYPE_MONTHLY ? __('Monthly') : __('Yearly')); ?></span>
                            <?php endif; ?>
                            <div class="card-header <?php echo e($headerGrad); ?>">
                                <h3><?php echo e($package->name); ?></h3>
                                <p class="price">
                                    <span class="currency-symbol"><?php echo e($defaultCurrencySymbol ?? '$'); ?></span><span
                                        class="amount" data-monthly="<?php echo e(number_format($package->monthly_price, 2)); ?>"
                                        data-yearly="<?php echo e(number_format($package->yearly_price, 2)); ?>">
                                        <?php echo e(number_format($package->monthly_price, 2)); ?>

                                    </span>/<span class="cycle"><?php echo e(__('Monthly')); ?></span>
                                </p>
                                <?php if($package->description): ?>
                                <p class="mt-1 fs-14 text-muted">
                                    <?php echo e(\Illuminate\Support\Str::limit($package->description, 120)); ?>

                                </p>
                                <?php endif; ?>
                            </div>
                            <ul class="features">
                                <?php if($package->ai_enabled): ?>
                                <li><?php echo e(__('AI Features Enabled')); ?></li>
                                <?php endif; ?>
                                <?php if(isset($package->is_trail) && $package->is_trail == STATUS_ACTIVE): ?>
                                <li><?php echo e(__('Includes free trial')); ?></li>
                                <?php endif; ?>
                                <?php if($package->features && is_array($package->features)): ?>
                                <?php $__currentLoopData = $package->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($feature); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>

                            <?php if($isCurrent): ?>
                            <button type="button" class="primary-btn btn-outline" disabled>
                                <?php echo e(__('Current Plan')); ?>

                            </button>
                            <?php else: ?>
                            <button type="button"
                                class="primary-btn btn-subscribe<?php echo e($loop->index === 1 ? ' btn-outline' : ''); ?>">
                                <?php echo e(__('Subscribe Now')); ?>

                            </button>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <h2 class="modal-title w-100 text-center"><?php echo e(__('Select Payment Method')); ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="billingPaymentForm" action="<?php echo e(route('admin.pricing.pay')); ?>" method="POST"
                enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="payment-package-id">
                <input type="hidden" name="type" id="payment-subscription-type">
                <input type="hidden" id="amount" value="">
                <input type="hidden" id="getCurrencyByGatewayRoute" value="<?php echo e(route('admin.pricing.get.currency')); ?>">
                <input type="hidden" id="selectCurrencyLabel" value="<?php echo e(__('Select Currency')); ?>">
                <input type="hidden" id="currencyPlacement" value="<?php echo e(getCurrencyPlacement()); ?>">

                <div class="modal-body">
                    <div class="payment-container primary-form">
                        <div class="payment-details-card">
                            <h3><?php echo e(__('Payment Details')); ?></h3>
                            <div class="detail-row"><span><?php echo e(__('Package Name')); ?></span> <strong
                                    id="det-plan">-</strong>
                            </div>
                            <div class="detail-row"><span><?php echo e(__('Package Type')); ?></span> <strong
                                    id="det-type">-</strong>
                            </div>
                            <div class="detail-row"><span><?php echo e(__('Amount')); ?></span> <strong id="det-amount">-</strong>
                            </div>
                            <div id="currencyAppend" class="conversion-rates mt-3 pt-3"
                                style="border-top: 1px solid var(--border-color); display: none;">
                                <!-- Currencies loaded here when gateway selected -->
                            </div>
                            <div id="bankAppend" class="mt-3 pt-3 d-none"
                                style="border-top: 1px solid var(--border-color);">
                                <h4 class="mb-3 fs-16"><?php echo e(__('Bank Deposit')); ?></h4>
                                <div class="form-group">
                                    <label for="payment-bank-select" class="form-label"><?php echo e(__('Bank Name')); ?></label>
                                    <!-- form-select -->
                                    <select name="bank_id" id="payment-bank-select" class="form-control select2-active">
                                        <option value=""><?php echo e(__('Select Option')); ?></option>
                                    </select>
                                </div>
                                <div id="bank-details-box" class="form-group mb-3 d-none">
                                    <label class="form-label"><?php echo e(__('Bank Details')); ?></label>
                                    <div id="bank-details-content" class="text-muted detail-row" style="text-align: justify"></div>
                                </div>
                                <div class="form-group">
                                    <label for="deposit-slip-input" class="form-label"><?php echo e(__('Upload Deposit Slip')); ?>

                                        (image, pdf)</label>
                                    <input type="file" name="deposit_slip" id="deposit-slip-input" class="form-control"
                                        accept="image/*,application/pdf">
                                </div>
                            </div>
                        </div>

                        <div class="gateway-grid" id="gateway-list">
                            <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="gateway-item gateway-option" data-gateway-id="<?php echo e($gateway->id); ?>"
                                data-gateway-slug="<?php echo e($gateway->slug); ?>">
                                <span class="gate-label"><?php echo e($gateway->title); ?></span>
                                <?php if($gateway->icon): ?>
                                <img src="<?php echo e($gateway->icon); ?>" alt="<?php echo e($gateway->title); ?>">
                                <?php endif; ?>
                                <button type="button" class="select-gate-btn" data-select-label="<?php echo e(__('Select')); ?>"
                                    data-selected-label="<?php echo e(__('Selected')); ?>">
                                    <?php echo e(__('Select')); ?>

                                </button>
                                <input type="radio" name="gateway" value="<?php echo e($gateway->id); ?>" class="d-none">
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="submit" class="primary-btn btn-subscribe btn-outline">
                        <?php echo e(__('Pay Now')); ?> <span id="footer-amt">(<?php echo e($defaultCurrencySymbol ?? '$'); ?> 0.00)</span>
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
                <h2 class="modal-title w-100 text-center" id="refundModalLabel"><?php echo e(__('Request Refund')); ?></h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.subscription.refund-request')); ?>" method="POST" class="ajax"
                data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="reasons" class="form-label"><?php echo e(__('Reasons for Refund')); ?></label>
                        <textarea name="reasons" id="reasons" class="form-control" rows="4"
                            placeholder="<?php echo e(__('Please explain why you are requesting a refund...')); ?>"
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="submit" class="primary-btn"><?php echo e(__('Submit Request')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>
<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/css/billing.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/billings.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\billing\index.blade.php ENDPATH**/ ?>