<?php if(getOption('landing_pricing_status', 1) == 1): ?>
<section class="pricing-section lp-section-padding" id="pricing-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <?php echo e(getOption('landing_pricing_title', __('Affordable License For Every Budget.'))); ?>

                </h2>
            </div>
        </div>

        <div class="text-center nav-btn-wrapper" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
            <div class="nav nav-pills billing-toggle justify-content-center" id="pricingTab" role="tablist">
                <button class="nav-link active" id="monthly-tab"
                        data-bs-toggle="pill" data-bs-target="#monthly-pane"
                        type="button" role="tab">
                    <?php echo e(__('Billed Monthly')); ?>

                </button>
                <button class="nav-link" id="yearly-tab"
                        data-bs-toggle="pill" data-bs-target="#yearly-pane"
                        type="button" role="tab">
                    <?php echo e(__('Billed Yearly')); ?>

                </button>
            </div>
        </div>

        <div class="tab-content" id="pricingTabContent">

            
            <div class="tab-pane fade show active" id="monthly-pane" role="tabpanel" tabindex="0">
                <div class="pricing-card-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $packages ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('auto_posts.frontend.partials.landing._pricing-card', [
                            'package' => $package,
                            'price'   => number_format((float) $package->monthly_price, 2),
                            'showExcluded' => false,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-center w-100"><?php echo e(__('No packages available right now.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="tab-pane fade" id="yearly-pane" role="tabpanel" tabindex="0">
                <div class="pricing-card-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $packages ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php echo $__env->make('auto_posts.frontend.partials.landing._pricing-card', [
                            'package' => $package,
                            'price'   => number_format((float) $package->yearly_price, 2),
                            'showExcluded' => true,
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-center w-100"><?php echo e(__('No packages available right now.')); ?></p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
    <div class="bg-bottom"></div>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\pricing.blade.php ENDPATH**/ ?>