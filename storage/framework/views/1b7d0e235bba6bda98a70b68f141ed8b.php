<!-- Footer Area Start -->
<footer class="footer-section">
    <div class="footer-wrap">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-12 border-end-custom">
                    <div class="footer-brand">
                        <img src="<?php echo e(getSettingImage('app_logo')); ?>" alt="<?php echo e(getOption('app_name', 'PostBot')); ?> Logo" class="brand-logo">
                    </div>
                    <?php if(getOption('footer_left_status', 1) == STATUS_ACTIVE): ?>
                    <p class="footer-description">
                        <?php echo e(getOption('footer_left_text', 'Our AI-powered platform helps businesses schedule smarter, post consistently, and grow their audience faster. Manage all your social media in one place with ease.')); ?>

                    </p>
                    <?php endif; ?>
                </div>

                <div class="col-lg-7 col-md-12">
                    <div class="right-side">
                        <div class="row footer-right-side">
                            <div class="col-md-3 col-6">
                                <div class="footer-title"><?php echo e(__('Pages')); ?></div>
                                <ul class="list-unstyled footer-links">
                                    <li><a href="<?php echo e(url('/#home')); ?>"><?php echo e(__('Home')); ?></a></li>
                                    <li><a href="<?php echo e(url('/#feature-section')); ?>"><?php echo e(__('Features')); ?></a></li>
                                    <li><a href="<?php echo e(url('/#pricing-section')); ?>"><?php echo e(__('Pricing')); ?></a></li>
                                    <li><a href="<?php echo e(url('/#blog-section')); ?>"><?php echo e(__('Blog')); ?></a></li>
                                </ul>
                            </div>

                            <div class="col-md-4 col-6">
                                <div class="footer-title"><?php echo e(__('Utilities')); ?></div>
                                <ul class="list-unstyled footer-links">
                                    <li><a href="<?php echo e(route('register')); ?>"><?php echo e(__('Sign Up')); ?></a></li>
                                    <li><a href="<?php echo e(route('login')); ?>"><?php echo e(__('Sign In')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('Privacy Policy')); ?></a></li>
                                    <li><a href="#"><?php echo e(__('Terms & Condition')); ?></a></li>
                                </ul>
                            </div>

                            <div class="col-md-5 col-12">
                                <?php if(getOption('footer_social_status', 1) == 1): ?>
                                <div class="footer-title"><?php echo e(__('Social Media')); ?></div>
                                <ul class="social-grid">
                                    <?php for($i = 1; $i <= 4; $i++): ?>
                                        <?php
                                            $urlKey = 'social_media_' . $i . '_url';
                                            $nameKey = 'social_media_' . $i . '_name';
                                            $statusKey = 'social_media_' . $i . '_status';
                                            $socialUrl = getOption($urlKey);
                                            $socialName = getOption($nameKey);
                                            $socialStatus = getOption($statusKey, 1);
                                        ?>
                                        <?php if($socialUrl && $socialStatus == 1): ?>
                                            <li>
                                                <a href="<?php echo e($socialUrl); ?>" target="_blank" class="social-link">
                                                    <?php echo e($socialName); ?>

                                                    <span class="icon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                            <path d="M10.8994 0.999547L0.999954 10.899M10.8994 0.999547H2.41417M10.8994 0.999547V9.48483" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="copyright-text">
                <?php echo nl2br(getOption('app_copyright', 'Copyright &copy; ' . date('Y') . ' - All Rights Reserved By <a href="#" class="brand-link">' . getOption('app_name', 'PostBot') . '</a>')); ?>

            </p>
        </div>
    </div>
    <div class="bg-bottom"></div>
</footer>
<!-- Footer Area End -->

    </div>
</div><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\layouts\footer.blade.php ENDPATH**/ ?>