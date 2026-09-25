<?php if(getOption('landing_tools_status', 1) == 1): ?>
<section class="our-tool-section lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <?php echo e(getOption('landing_tools_title', __('We Have Some Amazing Features For Team.'))); ?>

                </h2>
            </div>
        </div>

        <?php
            $toolIconClasses = [
                1 => 'orange', 2 => 'green',       3 => 'blue',         4 => 'pink',
                5 => 'purple', 6 => 'blue-alt',    7 => 'teal',         8 => 'yellow',
                9 => 'blue-vibrant', 10 => 'green-bright', 11 => 'indigo', 12 => 'coral',
            ];

            $toolCount = 0;
            for ($i = 1; $i <= 16; $i++) {
                if (trim((string) getOption('landing_tool_card' . $i . '_name', '')) !== '') {
                    $toolCount = $i;
                }
            }
        ?>

        <div class="tool-grid">
            <?php for($i = 1; $i <= $toolCount; $i++): ?>
                <?php
                    $nameKey   = 'landing_tool_card' . $i . '_name';
                    $imgKey    = 'landing_tool_card' . $i . '_image';
                    $title     = trim((string) getOption($nameKey, ''));
                    $hasImg    = (bool) getOption($imgKey);
                    $iconClass = $toolIconClasses[$i] ?? $toolIconClasses[(($i - 1) % 12) + 1];
                ?>
                <?php if($title !== '' || $hasImg): ?>
                <div class="tool-card"
                     data-aos="fade-up"
                     data-aos-delay="<?php echo e(100 + ($i - 1) * 100); ?>"
                     data-aos-duration="1000">
                    <?php if($hasImg): ?>
                    <div class="icon-box <?php echo e($iconClass); ?>">
                        <img src="<?php echo e(getSettingImage($imgKey)); ?>" alt="<?php echo e($title); ?>">
                    </div>
                    <?php endif; ?>
                    <?php if($title !== ''): ?><span class="tool-name"><?php echo e($title); ?></span><?php endif; ?>
                </div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\tools.blade.php ENDPATH**/ ?>