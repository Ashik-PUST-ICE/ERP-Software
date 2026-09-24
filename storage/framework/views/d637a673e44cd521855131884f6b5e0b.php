<?php
    $testimonialCount = max(1, min(10, (int) getOption('landing_testimonial_card_count', 2)));
    $testimonialSlides = [];
    for ($i = 1; $i <= $testimonialCount; $i++) {
        $quote = trim((string) getOption('landing_testimonial_card' . $i . '_quote', ''));
        if ($quote !== '') {
            $testimonialSlides[] = $i;
        }
    }
    $testimonialsTitle = trim((string) getOption('landing_testimonials_title', ''));
?>
<?php if(getOption('landing_testimonials_status', 1) == 1 && count($testimonialSlides) > 0): ?>
<section class="testimonial-section lp-section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <?php if($testimonialsTitle !== ''): ?>
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <?php echo e($testimonialsTitle); ?>

                </h2>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if(count($testimonialSlides) > 0): ?>
    <div class="swiper testimonialSwiper">
        <div class="swiper-wrapper">
            <?php $__currentLoopData = $testimonialSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $name        = trim((string) getOption('landing_testimonial_card' . $i . '_name', ''));
                    $designation = trim((string) getOption('landing_testimonial_card' . $i . '_designation', ''));
                    $rating      = max(0, min(5, (int) getOption('landing_testimonial_card' . $i . '_rating', 0)));
                    $imgKey      = 'landing_testimonial_card' . $i . '_image';
                    $hasImage    = (bool) getOption($imgKey);
                    $quote       = trim((string) getOption('landing_testimonial_card' . $i . '_quote', ''));
                ?>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <p class="feedback"><?php echo e($quote); ?></p>
                        <div class="user-info-wrapper">
                            <div class="user-details">
                                <?php if($hasImage): ?>
                                    <img src="<?php echo e(getSettingImage($imgKey)); ?>"
                                         alt="<?php echo e($name ?: 'Customer'); ?>"
                                         class="avatar">
                                <?php endif; ?>
                                <div class="meta">
                                    <?php if($name !== ''): ?>
                                        <div class="name"><?php echo e($name); ?></div>
                                    <?php endif; ?>
                                    <?php if($designation !== ''): ?>
                                        <p class="designation"><?php echo e($designation); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="rating">
                                <span class="stars">
                                    <?php echo e(str_repeat('★', $rating)); ?><?php echo e(str_repeat('☆', 5 - $rating)); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\testimonials.blade.php ENDPATH**/ ?>