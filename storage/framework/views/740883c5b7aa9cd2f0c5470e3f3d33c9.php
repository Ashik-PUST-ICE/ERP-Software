<!-- Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Testimonials Section Configuration')); ?></h4>
    <div class="mClose">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<?php

$testimonialCount = (int) getOption('landing_testimonial_card_count', 2);
$testimonialCount = max(1, min(10, $testimonialCount));
?>

<div class="primary-form">
    <form class="ajax" action="<?php echo e(route('super_admin.setting.frontend.landing-page.update')); ?>" method="POST"
        enctype="multipart/form-data" data-handler="commonResponseForModal" id="testimonials-config-form">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="landing_testimonial_card_count" id="landing_testimonial_card_count"
            value="<?php echo e($testimonialCount); ?>">
        <div class="row gy-4">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="form-label"><?php echo e(__('Section Title')); ?></label>
                    <input type="text" name="landing_testimonials_title" class="form-control"
                        value="<?php echo e(getOption('landing_testimonials_title', 'What Our Customers Are Saying.')); ?>"
                        placeholder="<?php echo e(__('Enter testimonials section title')); ?>">
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><?php echo e(__('Testimonial Cards')); ?></h5>
                    <button type="button" class="primary-btn" id="testimonials-add-more-btn"
                        onclick="testimonialsAddMore()">
                        <?php echo e(__('Add More')); ?>

                    </button>
                </div>
            </div>

            <div class="col-12" id="testimonials-cards-wrapper">
                <?php for($n = 1; $n <= 10; $n++): ?> <div class="testimonial-card-block border rounded p-3 mb-3"
                    data-card-num="<?php echo e($n); ?>" style="<?php echo e($n > $testimonialCount ? 'display:none' : ''); ?>">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0"><?php echo e(__('Testimonial')); ?> #<?php echo e($n); ?></h6>
                        <?php if($n > 1): ?>
                        <button type="button" class="btn btn-sm btn-danger testimonials-remove-btn"
                            onclick="testimonialsRemoveCard(<?php echo e($n); ?>)">&times;</button>
                        <?php endif; ?>
                    </div>
                    <div class="row gy-2">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Quote')); ?> (<?php echo e(__('Testimonial')); ?> <?php echo e($n); ?>)</label>
                                <textarea name="landing_testimonial_card<?php echo e($n); ?>_quote" class="form-control" rows="3"
                                    placeholder="<?php echo e(__('Enter testimonial text')); ?>"><?php echo e(getOption('landing_testimonial_card' . $n . '_quote', '')); ?></textarea>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Name')); ?> (<?php echo e(__('Testimonial')); ?> <?php echo e($n); ?>)</label>
                                <input type="text" name="landing_testimonial_card<?php echo e($n); ?>_name" class="form-control"
                                    value="<?php echo e(getOption('landing_testimonial_card' . $n . '_name', '')); ?>"
                                    placeholder="<?php echo e(__('Enter customer name')); ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Designation')); ?> (<?php echo e(__('Testimonial')); ?> <?php echo e($n); ?>)</label>
                                <input type="text" name="landing_testimonial_card<?php echo e($n); ?>_designation"
                                    class="form-control"
                                    value="<?php echo e(getOption('landing_testimonial_card' . $n . '_designation', '')); ?>"
                                    placeholder="<?php echo e(__('Enter designation')); ?>">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="form-label"><?php echo e(__('Rating (1–5 stars)')); ?> (<?php echo e(__('Testimonial')); ?> <?php echo e($n); ?>)</label>
                                <input type="number" name="landing_testimonial_card<?php echo e($n); ?>_rating" class="form-control"
                                    min="1" max="5"
                                    value="<?php echo e(getOption('landing_testimonial_card' . $n . '_rating', '')); ?>"
                                    placeholder="<?php echo e(__('Enter star rating 1-5')); ?>">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="form-label d-block"><?php echo e(__('Avatar Image')); ?> (<?php echo e(__('Testimonial')); ?> <?php echo e($n); ?>)</label>
                                <div class="zImage-upload-details mw-100">
                                    <div class="upload-img-box">
                                        <img
                                            src="<?php if(getOption('landing_testimonial_card' . $n . '_image')): ?><?php echo e(getSettingImage('landing_testimonial_card' . $n . '_image')); ?><?php else: ?><?php echo e(asset('assets/images/testimoni/albert.png')); ?><?php endif; ?>" />
                                        <input type="file" name="landing_testimonial_card<?php echo e($n); ?>_image"
                                            accept="image/*" onchange="previewFile(this)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <?php endfor; ?>
        </div>
</div>

<!-- Footer Buttons -->
<div class="btn-list mt-4 pt-3 border-top">
    <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
    <button type="submit" class="primary-btn"><?php echo e(__('Save')); ?></button>
</div>
</form>
</div>

<script src="<?php echo e(asset('super_admin/js/landing/testimonials-config.js')); ?>"></script><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\frontend\testimonials_configuration.blade.php ENDPATH**/ ?>