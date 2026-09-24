<?php
    $faqItems = [];
    $faqRaw   = getOption('landing_faq_items');
    if (!empty($faqRaw)) {
        foreach (explode(PHP_EOL, $faqRaw) as $line) {
            $parts = explode('|||', trim($line), 2);
            if (count($parts) === 2 && trim($parts[0]) !== '' && trim($parts[1]) !== '') {
                $faqItems[] = ['q' => trim($parts[0]), 'a' => trim($parts[1])];
            }
        }
    }
    $faqTitle = trim((string) getOption('landing_faq_title', ''));
?>
<?php if(getOption('landing_faq_status', 1) == 1 && count($faqItems) > 0): ?>
<section class="faq-section lp-section-padding" id="faq-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <?php if($faqTitle !== ''): ?>
                <h2 class="lp-title" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1000">
                    <?php echo e($faqTitle); ?>

                </h2>
                <?php endif; ?>

                <?php if(count($faqItems) > 0): ?>
                <div class="accordion accordion-flush custom-faq" id="faqAccordion">
                    <?php $__currentLoopData = $faqItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $collapseId = 'collapseFaq' . $index;
                            $isFirst    = $index === 0;
                        ?>
                        <div class="accordion-item"
                             data-aos="fade-up"
                             data-aos-delay="<?php echo e(100 + ($index * 100)); ?>"
                             data-aos-duration="1000">
                            <h2 class="accordion-header">
                                <button class="accordion-button <?php echo e($isFirst ? '' : 'collapsed'); ?>"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#<?php echo e($collapseId); ?>"
                                        aria-expanded="<?php echo e($isFirst ? 'true' : 'false'); ?>"
                                        aria-controls="<?php echo e($collapseId); ?>">
                                    <?php echo e($item['q']); ?>

                                </button>
                            </h2>
                            <div id="<?php echo e($collapseId); ?>"
                                 class="accordion-collapse collapse <?php echo e($isFirst ? 'show' : ''); ?>"
                                 data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?php echo e($item['a']); ?>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\frontend\partials\landing\faq.blade.php ENDPATH**/ ?>