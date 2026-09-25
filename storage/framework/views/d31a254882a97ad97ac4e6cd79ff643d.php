<?php if($package->icon): ?>
<div class="pricing-card" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
    <div class="text-center">
        <div class="icon-circle">
            <img src="<?php echo e(getFileUrl($package->icon)); ?>" alt="<?php echo e($package->name); ?>">
        </div>
        <h3><?php echo e($package->name); ?></h3>
        <div class="price">$<?php echo e($price); ?></div>
    </div>

    <div class="pricing-body">
        <ul class="features-list">
            <?php if(!empty($package->description)): ?>
            <li class="included"><?php echo e($package->description); ?></li>
            <?php endif; ?>

            <?php if(is_array($package->features) && count($package->features)): ?>
            <?php $__currentLoopData = $package->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="included"><?php echo e($feature); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if($package->ai_enabled): ?>
            <li class="included"><?php echo e(__('AI Features Included')); ?></li>
            <?php endif; ?>

        </ul>


    </div>

    <a href="<?php echo e(route('register')); ?>" class="primary-btn"><?php echo e(__('Purchase Now')); ?></a>
</div>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\_pricing-card.blade.php ENDPATH**/ ?>