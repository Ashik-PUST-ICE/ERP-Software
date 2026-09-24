<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e($title); ?></h2>
</div>
<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.general-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="title"><?php echo e(__('Payment Gateways')); ?></h3>
                    <a title="<?php echo e(__('Sync missing gateway')); ?>" href="<?php echo e(route('super_admin.setting.gateway.syncs')); ?>"
                        class="primary-btn d-none d-sm-inline-flex align-items-center"
                        onclick="return confirm('<?php echo e(__('Are you sure you want to sync gateways?')); ?>');">
                        <i class="fa fa-sync-alt me-2"></i><?php echo e(__('Sync Gateways')); ?>

                    </a>
                </div>
            </div>
            <input type="hidden" id="language-route" value="<?php echo e(route('super_admin.setting.languages.index')); ?>">
            <div class="row gy-4">
                <?php $__currentLoopData = $gateways; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                        data-id="<?php echo e($gateway->id); ?>">
                                        <i class="fa-solid fa-pen me-2"></i><?php echo e(__('Edit')); ?>

                                    </a>
                                </li>
                            </ul>
                        </div>
                        <img class="logo" src="<?php echo e(asset($gateway->image)); ?>" alt="<?php echo e($gateway->title); ?>">
                       <div class="bottom-status">
                         <span class="status <?php echo e($gateway->status == ACTIVE ? 'active' : 'deactivate'); ?>">
                            <?php echo e($gateway->status == ACTIVE ? __('Active') : __('Deactivate')); ?>

                        </span>
                        <?php if($gateway->slug != 'bank' && $gateway->mode): ?>
                        <span
                            class="status mode-status <?php echo e($gateway->mode == GATEWAY_MODE_LIVE ? 'active' : 'deactivate'); ?>">
                            <?php echo e($gateway->mode == GATEWAY_MODE_LIVE ? __('Live') : __('Sandbox')); ?>

                        </span>
                        <?php endif; ?>
                       </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade zModalTwo" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form class="ajax" action="<?php echo e(route('super_admin.setting.gateway.store')); ?>" method="POST"
                data-handler="responseOnGatewaStore">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="id" required>
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0" id="editModalLabel"><?php echo e(__('Edit Gateway')); ?>

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
                                <h6 class="text-muted mb-3 fw-600"><?php echo e(__('Basic Information')); ?></h6>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gateway-title" class="form-label"><?php echo e(__('Title')); ?></label>
                                    <input type="text" class="form-control title" id="gateway-title" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gateway-slug" class="form-label"><?php echo e(__('Slug')); ?></label>
                                    <input type="text" name="slug" class="form-control slug" id="gateway-slug" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label"><?php echo e(__('Status')); ?></label>
                                    <select name="status" id="status"
                                        class="select form-control wide sf-select-without-search">
                                        <option value="0"><?php echo e(__('Deactivate')); ?></option>
                                        <option value="1"><?php echo e(__('Active')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mode-div">
                                <div class="form-group">
                                    <label for="mode" class="form-label"><?php echo e(__('Mode')); ?></label>
                                    <select name="mode" id="mode"
                                        class="select form-control wide sf-select-without-search">
                                        <option value="1"><?php echo e(__('Live')); ?></option>
                                        <option value="2"><?php echo e(__('Sandbox')); ?></option>
                                    </select>
                                </div>
                            </div>

                            <!-- Bank Information -->
                            <div class="col-12 bank-div">
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-muted mb-0 fw-600"><?php echo e(__('Bank Information')); ?></h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-bank"
                                        title="<?php echo e(__('Add Bank')); ?>">
                                        <i class="fa fa-plus me-1"></i><?php echo e(__('Add Bank')); ?>

                                    </button>
                                </div>
                                <div class="bank-div-append"></div>
                            </div>

                            <!-- API Configuration -->
                            <div class="col-12 url-div key-secret-div">
                                <hr class="my-3">
                                <h6 class="text-muted mb-3 fw-600"><?php echo e(__('API Configuration')); ?></h6>
                                <div id="api-configuration-fields">
                                    <div class="form-group gateway-input d-none" id="gateway-url">
                                        <label for="gateway-url-input"
                                            class="form-label gateway-field-label"><?php echo e(__('Url')); ?>

                                            /<?php echo e(__('Hash')); ?></label>
                                        <input class="form-control" type="text" name="url" id="gateway-url-input"
                                            placeholder="<?php echo e(__('Enter API URL or Hash')); ?>">
                                    </div>
                                    <div class="form-group gateway-input d-none" id="gateway-key">
                                        <label for="gateway-key-input"
                                            class="form-label gateway-field-label"><?php echo e(__('Key')); ?></label>
                                        <input class="form-control" type="text" name="key" id="gateway-key-input"
                                            placeholder="<?php echo e(__('Enter API Key')); ?>">
                                        <small
                                            class="form-text text-muted d-none small"><?php echo e(__('Client id, Public Key, Key, Store id, Api Key')); ?></small>
                                    </div>
                                    <div class="form-group gateway-input d-none" id="gateway-secret">
                                        <label for="gateway-secret-input"
                                            class="form-label gateway-field-label"><?php echo e(__('Secret')); ?></label>
                                        <input class="form-control" type="password" name="secret"
                                            id="gateway-secret-input" placeholder="<?php echo e(__('Enter API Secret')); ?>">
                                        <small
                                            class="form-text text-muted d-none small"><?php echo e(__('Client Secret, Secret, Store Password, Auth Token')); ?></small>
                                    </div>
                                </div>
                            </div>

                            <!-- Currency Conversion -->
                            <div class="col-12">
                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-muted mb-0 fw-600"><?php echo e(__('Conversion Rate')); ?></h6>
                                    <button type="button" class="primary-btn btn-outline add-currency"
                                        title="<?php echo e(__('Add Currency')); ?>">
                                        <i class="fa fa-plus me-1"></i><?php echo e(__('Add Currency')); ?>

                                    </button>
                                </div>
                                <div id="currencyConversionRateSection"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"
                            title="<?php echo e(__('Cancel')); ?>"><?php echo e(__('Cancel')); ?></button>
                        <button type="submit" class="primary-btn" title="<?php echo e(__('Update')); ?>"><?php echo e(__('Update')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" id="getInfoRoute" value="<?php echo e(route('super_admin.setting.gateway.get.info')); ?>">
<input type="hidden" id="getCurrencySymbol" value="<?php echo e(getCurrencySymbol()); ?>">
<input type="hidden" id="allCurrency" value="<?php echo e(json_encode(getCurrency())); ?>">
<input type="hidden" id="gatewaySettings" value="<?php echo e(gatewaySettings()); ?>">
<input type="hidden" id="supportedCurrency" value="<?php echo e(json_encode(getGatewaySupportedCurrencies())); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('super_admin/css/gateway.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/gateway.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\gateway.blade.php ENDPATH**/ ?>