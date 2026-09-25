<?php if(session('payment_form_html')): ?>
<div id="virtualPaymentForm" style="display: none;">
    <?php echo session('payment_form_html'); ?>

</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Find the form inside the hidden div and submit it automatically
    var virtualForm = document.getElementById('virtualPaymentForm').querySelector('form');
    if (virtualForm) {
        virtualForm.submit();
    }
});
</script>
<?php endif; ?>

<!-- js file  -->
<script src="<?php echo e(asset('assets/js/jquery-3.7.1.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/bootstrap.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/plugins.js')); ?>"></script>
<?php if(file_exists(public_path('assets/js/lc_select.min.js'))): ?>
<script src="<?php echo e(asset('assets/js/lc_select.min.js')); ?>"></script>
<?php endif; ?>
<script src="<?php echo e(asset('assets/js/main.js')); ?>"></script>
<script src="<?php echo e(asset('common/js/common.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/garment-card-loader.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/queue-assistant.js')); ?>"></script>
<script src="<?php echo e(asset('admin/js/admin-global-search.js')); ?>"></script>

<?php echo $__env->yieldPushContent('script'); ?>

<style>
<?php echo getOption('custom_css'); ?>

</style>

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
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\layouts\script.blade.php ENDPATH**/ ?>