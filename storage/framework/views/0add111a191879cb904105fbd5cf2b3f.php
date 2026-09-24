<?php
$ctaTitle = trim((string) getOption('landing_cta_title', ''));
$ctaDesc = trim((string) getOption('landing_cta_description', ''));
$ctaBtn = trim((string) getOption('landing_cta_button_text', ''));
$ctaBg = getOption('landing_cta_background');
$ctaShow = $ctaTitle !== '' || $ctaDesc !== '' || $ctaBtn !== '' || $ctaBg;
?>
<?php if(getOption('landing_cta_status', 1) == 1 && $ctaShow): ?>
<section class="cta-area">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="cta-wrapper" <?php if($ctaBg): ?>
                    style="background-image: url(<?php echo e(getSettingImage('landing_cta_background')); ?>);" <?php endif; ?>
                    data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">

                    <?php if($ctaTitle !== '' || $ctaDesc !== '' || $ctaBtn !== ''): ?>
                    <div class="content">
                        <?php if($ctaTitle !== ''): ?><h2 class="title"><?php echo e($ctaTitle); ?></h2><?php endif; ?>
                        <?php if($ctaDesc !== ''): ?><p class="description"><?php echo e($ctaDesc); ?></p><?php endif; ?>
                        <?php if($ctaBtn !== ''): ?><a href="<?php echo e(route('register')); ?>" class="primary-btn"><?php echo e($ctaBtn); ?></a><?php endif; ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
    <div class="bg-bottom"></div>
</section>
<?php endif; ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\cta.blade.php ENDPATH**/ ?>