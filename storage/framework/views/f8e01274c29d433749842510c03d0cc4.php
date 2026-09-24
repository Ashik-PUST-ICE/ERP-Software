<?php if(getOption('landing_blog_status', 1) == 1 && isset($blogs) && $blogs->count()): ?>
<section class="blog-area lp-section-padding" id="blog-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <?php echo e(getOption('landing_blog_title', __('Latest From Our Blog.'))); ?>

                </h2>
            </div>
        </div>

        <div class="row">
            <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($blog->image): ?>
                <div class="col-lg-4 col-md-6 col-12"
                     data-aos="fade-up"
                     data-aos-delay="<?php echo e(100 + ($index * 100)); ?>"
                     data-aos-duration="1000">
                    <a href="<?php echo e(route('blog.show', $blog)); ?>"
                       target="_blank" rel="noopener"
                       class="blog-card">
                        <div class="image">
                            <img src="<?php echo e(getFileUrl($blog->image)); ?>" alt="<?php echo e($blog->title); ?>">
                        </div>
                        <?php if($blog->date): ?><span class="time"><?php echo e($blog->date); ?></span><?php endif; ?>
                        <h3 class="title"><?php echo e($blog->title); ?></h3>
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\blog.blade.php ENDPATH**/ ?>