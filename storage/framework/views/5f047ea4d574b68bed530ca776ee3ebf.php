<?php if(getOption('features_section_status', 1) == 1): ?>
<section class="lp-featured-areas lp-section-padding" id="feature-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    <?php echo e(getOption('landing_features_title', __('Easily Manage Platform & Accounts.'))); ?>

                </h2>
            </div>
        </div>

        <?php
            $featureCardClasses = ['orange', 'sky', 'blue', 'pink', 'orange', 'sky'];
        ?>

        <div class="featured-card-grid">
            <?php for($i = 1; $i <= 6; $i++): ?>
                <?php
                    $titleKey = 'landing_feature_card' . $i . '_title';
                    $descKey  = 'landing_feature_card' . $i . '_description';
                    $imgKey   = 'landing_feature_card' . $i . '_image';
                    $hasTitle = trim((string) getOption($titleKey, '')) !== '';
                    $hasDesc  = trim((string) getOption($descKey, '')) !== '';
                    $hasImg   = (bool) getOption($imgKey);
                    $show     = $hasTitle || $hasDesc || $hasImg;
                ?>
                <?php if($show): ?>
                <div class="featured-card <?php echo e($featureCardClasses[$i - 1] ?? 'orange'); ?>"
                     data-aos="fade-up"
                     data-aos-delay="<?php echo e(300 + ($i - 1) * 100); ?>"
                     data-aos-duration="1000">
                    <?php if($hasTitle || $hasDesc): ?>
                    <div class="content">
                        <?php if($hasTitle): ?><h3 class="title"><?php echo e(getOption($titleKey)); ?></h3><?php endif; ?>
                        <?php if($hasDesc): ?><p><?php echo e(getOption($descKey)); ?></p><?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php if($hasImg): ?>
                    <img src="<?php echo e(getSettingImage($imgKey)); ?>"
                         alt="<?php echo e(getOption($titleKey)); ?>">
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\features.blade.php ENDPATH**/ ?>