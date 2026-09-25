<?php $__env->startPush('title'); ?>
<?php echo e(__('Verify Reset Password')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="auth-area">
    <div class="auth-left">
        <div class="auth-form-wrap">
            <a href="<?php echo e(route('login')); ?>" class="brand-logo">
                <img src="<?php echo e(getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png')); ?>"
                    alt="<?php echo e(getOption('app_name')); ?>">
            </a>
            <div class="auth-top">
                <h2><?php echo e(__('Confirm Reset Password')); ?></h2>
                <h3><?php echo e(__('Please check your email')); ?> <span><?php echo e($resetPassword->email); ?></span>
                    <?php echo e(__('and enter the otp below to reset')); ?></h3>
            </div>
            <form action="<?php echo e(route('password.reset.verify', ['token' => $token, 'email' => $resetPassword->email])); ?>"
                method="POST" class="otp-form" name="otp-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="email" value="<?php echo e($resetPassword->email); ?>">
                <div class="auth-form">
                    <div class="form-group">
                        <label class="form-label text-center w-100 mb-3"><?php echo e(__('Enter OTP Code')); ?></label>
                        <div class="otp-input-fields d-flex justify-content-center gap-2" id="otp-block">
                            <input type="text" name="otp__field__1" id="otp__field__1" maxlength="1" required
                                class="otp__digit otp__field__1 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                            <input type="text" name="otp__field__2" id="otp__field__2" maxlength="1" required
                                class="otp__digit otp__field__2 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                            <input type="text" name="otp__field__3" id="otp__field__3" maxlength="1" required
                                class="otp__digit otp__field__3 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                            <input type="text" name="otp__field__4" id="otp__field__4" maxlength="1" required
                                class="otp__digit otp__field__4 form-control text-center"
                                style="width: 60px; font-size: 24px;" />
                        </div>
                    </div>
                    <p class="text-center fs-12 text-muted pt-2 pb-3"><?php echo e(__('Send the code again after')); ?> <span
                            id="send-after-timer"></span></p>
                    <div class="d-none" id="resent-div">
                        <button type="button"
                            onclick="event.preventDefault(); document.getElementById('resent-form').submit();"
                            class="auth-form-btn w-100 mb-3"
                            title="<?php echo e(__('Click here to request another')); ?>"><?php echo e(__('Click here to request another')); ?></button>
                    </div>
                    <button id="verify-btn" type="submit" class="auth-form-btn"><?php echo e(__('Continue')); ?></button>
                </div>
            </form>
            <form method="POST"
                action="<?php echo e(route('password.reset.verify_resend', ['token' => $token, 'email' => $resetPassword->email])); ?>"
                class="d-none" id="resent-form">
                <?php echo csrf_field(); ?>
            </form>
        </div>
    </div>
    <div class="auth-right">
        <img class="img-fluid" src="<?php echo e(getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg')); ?>"
            alt="auth-image">
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script>
// Set the date we're counting down to
var countDownDate = new Date('<?php echo e($resetPassword->otp_expiry); ?>').getTime();
var currentTime = new Date('<?php echo e(now()); ?>');
var oldTime = 0;
</script>

<script src="<?php echo e(asset('user/js/verify_timer.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auth.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auth\passwords\verify.blade.php ENDPATH**/ ?>