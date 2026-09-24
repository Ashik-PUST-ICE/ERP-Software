<?php $__env->startPush('title'); ?>
    <?php echo e(__('Forget Password')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="<?php echo e(route('index')); ?>" class="brand-logo">
                    <img src="<?php echo e(getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png')); ?>" alt="<?php echo e(getOption('app_name')); ?>">
                </a>
                <div class="auth-top">
                    <h2><?php echo e(__('Forgot Password')); ?></h2>
                    <?php if(getOption('disable_registration') != 1): ?>
                        <h3><?php echo e(__("Don't have an account?")); ?> <a href="<?php echo e(route('register')); ?>" class="auth-link"><?php echo e(__('Sign Up')); ?></a></h3>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?php echo e(route('password.email')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="auth-form">
                        <div class="form-group">
                            <label for="EmailAddress" class="form-label"><?php echo e(__('Email Address')); ?></label>
                            <input type="email" class="form-control" id="EmailAddress" name="email" 
                                value="<?php echo e(old('email')); ?>" placeholder="<?php echo e(__('example@gmail.com')); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger fs-12"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <button type="submit" class="auth-form-btn"><?php echo e(__('Continue')); ?></button>
                    </div>
                </form>
            </div>
        </div>
        <div class="auth-right">
            <img class="img-fluid" src="<?php echo e(getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg')); ?>" alt="auth-image">
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auth.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auth\passwords\email.blade.php ENDPATH**/ ?>