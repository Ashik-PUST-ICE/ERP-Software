<?php $__env->startPush('title'); ?>
    <?php echo e(__('Login')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <div class="auth-area">
        <div class="auth-left">
            <div class="auth-form-wrap">
                <a href="<?php echo e(route('index')); ?>" class="brand-logo">
                    <img src="<?php echo e(getSettingImage('app_black_logo') ?: asset('assets/images/logo-dark.png')); ?>" alt="<?php echo e(getOption('app_name')); ?>">
                </a>
                <div class="auth-top">
                    <h2><?php echo e(__('Sign In')); ?></h2>
                    <?php if(getOption('disable_registration') != 1): ?>
                        <h3><?php echo e(__("Don't have an account?")); ?> <a href="<?php echo e(route('register')); ?>" class="auth-link"><?php echo e(__('Sign Up')); ?></a></h3>
                    <?php endif; ?>
                </div>
                <form method="POST" action="<?php echo e(route('login')); ?>">
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
                        <div class="form-group">
                            <label for="Password" class="form-label">
                                <?php echo e(__('Password')); ?>

                                <a href="<?php echo e(route('password.request')); ?>" class="auth-link"><?php echo e(__('Forgot Password?')); ?></a>
                            </label>
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
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="Remember" name="remember">
                            <label class="form-check-label" for="Remember"><?php echo e(__('Remember Me')); ?></label>
                        </div>
                        <?php if(!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1): ?>
                            <div class="form-group<?php echo e($errors->has('g-recaptcha-response') ? ' has-error' : ''); ?>">
                                <div class="g-recaptcha" data-sitekey="<?php echo e(getOption('google_recaptcha_site_key')); ?>"></div>

                                <?php if($errors->has('g-recaptcha-response')): ?>
                                    <span class="help-block text-danger">
                                        <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <button type="submit" class="auth-form-btn"><?php echo e(__('Sign In')); ?></button>
                    </div>
                </form>
                <?php if(getOption('google_login_status') == 1 || getOption('facebook_login_status') == 1): ?>
                    <div class="auth-bottom">
                        <h3 class="or-platform-title"><span><?php echo e(__('Or Sign in with')); ?></span></h3>
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
                <?php if(env('LOGIN_HELP') == 'active'): ?>
                    <div class="mt-3">
                        <div id="superAdminCredentialShow" class="login-info text-center p-2 border rounded mb-2 cursor-pointer">
                            <b>Super Admin :</b> suadmin@gmail.com | 123456
                        </div>
                        <div id="adminCredentialShow" class="login-info text-center p-2 border rounded cursor-pointer">
                            <b>Admin :</b> admin@gmail.com | 123456
                        </div>
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

<?php $__env->startPush('script'); ?>
    <script>
        "use strict"
        <?php if(env('LOGIN_HELP') == 'active'): ?>
        $('#superAdminCredentialShow').on('click', function() {
            $('#EmailAddress').val('suadmin@gmail.com');
            $('#Password').val('123456');
        });
        $('#adminCredentialShow').on('click', function() {
            $('#EmailAddress').val('admin@gmail.com');
            $('#Password').val('123456');
        });
        <?php endif; ?>
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auth.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auth\login.blade.php ENDPATH**/ ?>