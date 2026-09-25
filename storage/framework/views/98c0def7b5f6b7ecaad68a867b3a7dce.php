<?php if(getOption('landing_campaign_status', 1) == 1): ?>
<?php
    $cardClasses = ['pink', 'purple', 'yallow'];
    $hasAnyCard = false;
    for ($n = 1; $n <= 12; $n++) {
        $tk = 'landing_campaign_card' . $n . '_title';
        $dk = 'landing_campaign_card' . $n . '_description';
        $ik = 'landing_campaign_card' . $n . '_image';
        if (trim((string) getOption($tk, '')) !== '' || trim((string) getOption($dk, '')) !== '' || getOption($ik)) {
            $hasAnyCard = true;
            break;
        }
    }
    $sectionTitle = trim((string) getOption('landing_campaign_title', ''));
?>
<?php if($hasAnyCard || $sectionTitle !== ''): ?>
<section class="campaing-calender lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <?php if($sectionTitle !== ''): ?>
            <div class="col-lg-8">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    <?php echo e(getOption('landing_campaign_title')); ?>

                </h2>
            </div>
            <?php endif; ?>

            <div class="col-lg-12">
                <div class="wrapper">
                    <?php for($n = 1; $n <= 12; $n++): ?>
                        <?php
                            $titleKey = 'landing_campaign_card' . $n . '_title';
                            $descKey  = 'landing_campaign_card' . $n . '_description';
                            $imgKey   = 'landing_campaign_card' . $n . '_image';
                            $hasTitle = trim((string) getOption($titleKey, '')) !== '';
                            $hasDesc  = trim((string) getOption($descKey, '')) !== '';
                            $hasImg   = (bool) getOption($imgKey);
                            $showCard = $hasTitle || $hasDesc || $hasImg;
                        ?>
                        <?php if($showCard): ?>
                        <div class="image-box <?php echo e($cardClasses[($n - 1) % 3]); ?>">
                            <?php if($hasTitle || $hasDesc): ?>
                            <div class="content">
                                <?php if($hasTitle): ?><h3><?php echo e(getOption($titleKey)); ?></h3><?php endif; ?>
                                <?php if($hasDesc): ?><p><?php echo e(getOption($descKey)); ?></p><?php endif; ?>
                            </div>
                            <?php endif; ?>
                            <?php if($hasImg): ?>
                            <div class="right-image">
                                <img src="<?php echo e(getSettingImage($imgKey)); ?>" alt="<?php echo e(getOption($titleKey)); ?>" style="max-width:100%;height:auto;">
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<?php endif; ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\campaign.blade.php ENDPATH**/ ?>