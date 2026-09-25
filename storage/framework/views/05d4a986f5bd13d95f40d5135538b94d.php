<?php
    $icons = [
        ['class' => 'facebook',  'src' => 'assets/images/hero/fb.svg',        'alt' => 'Facebook',   'dir' => 'right', 'delay' => 100],
        ['class' => 'threads',   'src' => 'assets/images/hero/threads.svg',    'alt' => 'Threads',    'dir' => 'right', 'delay' => 200],
        ['class' => 'linkedin',  'src' => 'assets/images/hero/linkedin.svg',   'alt' => 'LinkedIn',   'dir' => 'right', 'delay' => 300],
        ['class' => 'instagram', 'src' => 'assets/images/hero/instagram.svg',  'alt' => 'Instagram',  'dir' => 'left',  'delay' => 100],
        ['class' => 'tik-tok',   'src' => 'assets/images/hero/tik-tok.svg',    'alt' => 'TikTok',     'dir' => 'left',  'delay' => 200],
        ['class' => 'youtube',   'src' => 'assets/images/hero/youtube.svg',    'alt' => 'YouTube',    'dir' => 'left',  'delay' => 300],
    ];
?>

<div class="social-icons">
    <?php $__currentLoopData = $icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="icon <?php echo e($icon['class']); ?>"
             data-aos="fade-<?php echo e($icon['dir']); ?>"
             data-aos-delay="<?php echo e($icon['delay']); ?>"
             data-aos-duration="1000">
            <img src="<?php echo e(asset($icon['src'])); ?>" alt="<?php echo e($icon['alt']); ?>">
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\_social-icons.blade.php ENDPATH**/ ?>