<?php $__env->startPush('title'); ?>
    <?php echo e(__('Registration')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-area registration-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="<?php echo e(route('index')); ?>" class="brand-logo">
                    <img src="<?php echo e(getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png')); ?>" alt="<?php echo e(getOption('app_name')); ?>">
                </a>
                <div class="auth-top">
                    <h2><?php echo e(__('Sign Up')); ?></h2>
                    <h3><?php echo e(__('Already have an account?')); ?> <a href="<?php echo e(route('login')); ?>" class="auth-link"><?php echo e(__('Sign In')); ?></a></h3>
                </div>
                <form method="POST" action="<?php echo e(route('register')); ?>" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="auth-form">
                        <div class="form-group">
                            <label for="FullName" class="form-label"><?php echo e(__('Full Name')); ?></label>
                            <input type="text" class="form-control" id="FullName" name="name" 
                                value="<?php echo e(old('name')); ?>" placeholder="<?php echo e(__('John Doe')); ?>" required>
                            <?php $__errorArgs = ['name'];
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
                        <div class="form-group">
                            <label for="Password" class="form-label"><?php echo e(__('Password')); ?></label>
                            <input type="password" class="form-control" id="Password" name="password" 
                                placeholder="**********" required>
                            <?php $__errorArgs = ['password'];
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
                        <div class="form-group">
                            <label for="ConfirmPassword" class="form-label"><?php echo e(__('Confirm Password')); ?></label>
                            <input type="password" class="form-control" id="ConfirmPassword" name="password_confirmation" 
                                placeholder="**********" required>
                            <?php $__errorArgs = ['password_confirmation'];
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
                        <?php if(!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1): ?>
                            <div class="form-group<?php echo e($errors->has('g-recaptcha-response') ? ' has-error' : ''); ?>">
                                <?php echo RecaptchaV3::field('register'); ?>

                                <?php if($errors->has('g-recaptcha-response')): ?>
                                    <span class="help-block text-danger">
                                        <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="agree_policy" id="termsPrivacy" required>
                            <label class="form-check-label" for="termsPrivacy">
                                <?php echo e(__('By clicking Create account, I agree that I have read and accepted the Terms of Use and Privacy Policy.')); ?>

                            </label>
                        </div>
                        <button type="submit" class="auth-form-btn"><?php echo e(__('Sign Up')); ?></button>
                    </div>
                </form>
                <?php if(getOption('google_login_status') == 1 || getOption('facebook_login_status') == 1): ?>
                    <div class="auth-bottom">
                        <h3 class="or-platform-title"><span><?php echo e(__('Or Sign Up with')); ?></span></h3>
                        <ul class="auth-social-media">
                            <?php if(getOption('facebook_login_status') == 1): ?>
                                <li>
                                    <a href="<?php echo e(route('facebook-login')); ?>" class="auth-social-btn">
                                        <img src="<?php echo e(asset('assets/images/facebook.png')); ?>" alt="facebook">
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(getOption('google_login_status') == 1): ?>
                                <li>
                                    <a href="<?php echo e(route('google-login')); ?>" class="auth-social-btn">
                                        <img src="<?php echo e(asset('assets/images/google.png')); ?>" alt="google">
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- <div class="auth-bottom">
                    <h3 class="or-platform-title"><span>Or Sign Up with</span></h3>
                    <ul class="auth-social-media">
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/facebook.png" alt="facebook"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/instagram.png" alt="instagram"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/threads.png" alt="threads"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/twitter.png" alt="twitter"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/linkedin.png" alt="linkedin"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/tik-tok.png" alt="tik-tok"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/youtube.png" alt="youtube"></a></li>
                        <li><a href="#" class="auth-social-btn"><img src="assets/images/google.png" alt="google"></a></li>
                    </ul>
                </div> -->
            </div>
        </div>
        <div class="auth-right">
            <img class="img-fluid" src="<?php echo e(getSettingImage('login_left_image') ?: asset('assets/images/auth-image.jpg')); ?>" alt="auth-image">
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auth.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auth\register.blade.php ENDPATH**/ ?>