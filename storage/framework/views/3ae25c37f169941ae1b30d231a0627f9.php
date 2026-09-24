<!-- js file  -->
<script src="<?php echo e(asset('assets/js/jquery-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/swiper-bundle.min.js')); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js"></script>
<script src="https://assets.codepen.io/16327/ScrollSmoother.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="<?php echo e(asset('assets/js/script.js')); ?>"></script>



<?php echo $__env->yieldPushContent('script'); ?>

<script>
    var currencySymbol = "<?php echo e(getCurrencySymbol()); ?>";
    var currencyPlacement = "<?php echo e(getCurrencyPlacement()); ?>";

    <?php if(Session::has('success')): ?>
        toastr.success("<?php echo e(session('success')); ?>");
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
        toastr.error("<?php echo e(session('error')); ?>");
    <?php endif; ?>
    <?php if(Session::has('info')): ?>
        toastr.info("<?php echo e(session('info')); ?>");
    <?php endif; ?>
    <?php if(Session::has('warning')): ?>
        toastr.warning("<?php echo e(session('warning')); ?>");
    <?php endif; ?>

    <?php if(@$errors->any()): ?>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            toastr.error("<?php echo e($error); ?>");
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
</script>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\layouts\script.blade.php ENDPATH**/ ?>