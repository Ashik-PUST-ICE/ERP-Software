<?php if(getOption('landing_post_management_status', 1) == 1): ?>
<section class="post-management lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                    <?php echo e(getOption('landing_post_management_title', __('Easy Post Creation With AI & Manage Posts.'))); ?>

                </h2>
            </div>
        </div>

        <?php
            $postCards = [
                1 => ['bgClass' => ''],          2 => ['bgClass' => 'bg-orange'],
                3 => ['bgClass' => 'bg-teal'],   4 => ['bgClass' => 'bg-orange'],
            ];
        ?>

        <div class="post-management-grid">
            <?php $__currentLoopData = $postCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n => $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $titleKey = 'landing_post_card' . $n . '_title';
                    $descKey  = 'landing_post_card' . $n . '_description';
                    $imgKey   = 'landing_post_card' . $n . '_image';
                    $hasTitle = trim((string) getOption($titleKey, '')) !== '';
                    $hasDesc  = trim((string) getOption($descKey, '')) !== '';
                    $hasImg   = (bool) getOption($imgKey);
                    $show     = $hasTitle || $hasDesc || $hasImg;
                ?>
                <?php if($show): ?>
                    <article class="post-management-card"
                             data-aos="fade-up"
                             data-aos-delay="<?php echo e(200 + ($n - 1) * 100); ?>"
                             data-aos-duration="1000">
                        <?php if($hasImg): ?>
                        <div class="card-image <?php echo e($card['bgClass']); ?>">
                            <img src="<?php echo e(getSettingImage($imgKey)); ?>"
                                 alt="<?php echo e(getOption($titleKey)); ?>"
                                 style="max-width:100%;height:auto;">
                        </div>
                        <?php endif; ?>
                        <?php if($hasTitle || $hasDesc): ?>
                        <div class="card-content">
                            <?php if($hasTitle): ?><h3><?php echo e(getOption($titleKey)); ?></h3><?php endif; ?>
                            <?php if($hasDesc): ?><p><?php echo e(getOption($descKey)); ?></p><?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </article>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php
            $manageAllImg   = getOption('landing_post_manage_all_image');
            $manageAllTitle = trim((string) getOption('landing_post_manage_all_title', ''));
            $manageAllDesc  = trim((string) getOption('landing_post_manage_all_description', ''));
            $showManageAll  = $manageAllImg || $manageAllTitle !== '' || $manageAllDesc !== '';
        ?>
        <?php if($showManageAll): ?>
        <div class="manage-posts-banner" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1000">
            <?php if($manageAllTitle !== '' || $manageAllDesc !== ''): ?>
            <div class="banner-text">
                <?php if($manageAllTitle !== ''): ?><h3><?php echo e(getOption('landing_post_manage_all_title')); ?></h3><?php endif; ?>
                <?php if($manageAllDesc !== ''): ?><p><?php echo e(getOption('landing_post_manage_all_description')); ?></p><?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if($manageAllImg): ?>
            <div class="banner-image">
                <img src="<?php echo e(getSettingImage('landing_post_manage_all_image')); ?>"
                     alt="<?php echo e($manageAllTitle ?: __('Management Table')); ?>"
                     style="max-width:100%;height:auto;">
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="bg-bottom"></div>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\post-management.blade.php ENDPATH**/ ?>