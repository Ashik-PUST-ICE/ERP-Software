<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>

<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.landing-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <div class="section-wrap">
            <form class="ajax primary-form" action="<?php echo e(route('super_admin.setting.frontend.landing-page.update')); ?>"
                method="POST" enctype="multipart/form-data" data-handler="commonResponseForModal">
                <?php echo csrf_field(); ?>
                <div class="row gy-4">
                    <div class="col-12">
                        <h4 class="mb-3"><?php echo e(__('Section Status')); ?></h4>
                        <p class="text-muted small"><?php echo e(__('Show or hide sections on the landing page.')); ?></p>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Title')); ?></label>
                            <input type="text" name="landing_hero_title" class="form-control"
                                value="<?php echo e(getOption('landing_hero_title', 'AI-Powered Social Media Scheduling Made Simple.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Subtitle')); ?></label>
                            <input type="text" name="landing_hero_subtitle" class="form-control"
                                value="<?php echo e(getOption('landing_hero_subtitle', 'Plan, create, and publish smarter content automatically — all from one powerful, easy-to-use dashboard.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Primary Button Text')); ?></label>
                            <input type="text" name="landing_hero_button_text" class="form-control"
                                value="<?php echo e(getOption('landing_hero_button_text', 'Get Started For Free')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Primary Button URL')); ?></label>
                            <input type="text" name="landing_hero_button_url" class="form-control"
                                value="<?php echo e(getOption('landing_hero_button_url', route('register'))); ?>">
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <h4 class="mb-3"><?php echo e(__('Section Titles')); ?></h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Features Section Title')); ?></label>
                            <input type="text" name="landing_features_title" class="form-control"
                                value="<?php echo e(getOption('landing_features_title', 'Easily Manage Platform & Accounts.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Post Management Section Title')); ?></label>
                            <input type="text" name="landing_post_management_title" class="form-control"
                                value="<?php echo e(getOption('landing_post_management_title', 'Easy Post Creation With AI & Manage Posts.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Campaign Section Title')); ?></label>
                            <input type="text" name="landing_campaign_title" class="form-control"
                                value="<?php echo e(getOption('landing_campaign_title', 'Manage Campaign & Calendar for auto post scheduling.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Tools Section Title')); ?></label>
                            <input type="text" name="landing_tools_title" class="form-control"
                                value="<?php echo e(getOption('landing_tools_title', 'We Have Some Amazing Features For Team.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Pricing Section Title')); ?></label>
                            <input type="text" name="landing_pricing_title" class="form-control"
                                value="<?php echo e(getOption('landing_pricing_title', 'Affordable License For Every Budget.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Testimonials Section Title')); ?></label>
                            <input type="text" name="landing_testimonials_title" class="form-control"
                                value="<?php echo e(getOption('landing_testimonials_title', 'What Our Customers Are Saying.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('Blog Section Title')); ?></label>
                            <input type="text" name="landing_blog_title" class="form-control"
                                value="<?php echo e(getOption('landing_blog_title', 'Latest From Our Blog.')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('FAQ Section Title')); ?></label>
                            <input type="text" name="landing_faq_title" class="form-control"
                                value="<?php echo e(getOption('landing_faq_title', 'Frequently Asked Questions.')); ?>">
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <h4 class="mb-3"><?php echo e(__('CTA Section')); ?></h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('CTA Title')); ?></label>
                            <input type="text" name="landing_cta_title" class="form-control"
                                value="<?php echo e(getOption('landing_cta_title', 'Make Your Everyday Life More Easier!')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('CTA Button Text')); ?></label>
                            <input type="text" name="landing_cta_button_text" class="form-control"
                                value="<?php echo e(getOption('landing_cta_button_text', 'Get Started')); ?>">
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group">
                            <label class="form-label"><?php echo e(__('CTA Description')); ?></label>
                            <textarea name="landing_cta_description" class="form-control"
                                rows="3"><?php echo e(getOption('landing_cta_description', 'We believe in providing value without compromising on quality. Our flexible plans are designed.')); ?></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <h4 class="mb-3"><?php echo e(__('Images')); ?></h4>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block"><?php echo e(__('Hero Main Image')); ?></label>
                            <?php if(getOption('landing_hero_image')): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(getOption('landing_hero_image')); ?>" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="landing_hero_image" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block"><?php echo e(__('Hero Background Image')); ?></label>
                            <?php if(getOption('landing_hero_background')): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(getOption('landing_hero_background')); ?>" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="landing_hero_background" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label
                                class="form-label d-block"><?php echo e(__('Post Management - Manage All Posts Image')); ?></label>
                            <?php if(getOption('landing_post_manage_all_image')): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(getOption('landing_post_manage_all_image')); ?>" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="landing_post_manage_all_image" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block"><?php echo e(__('CTA Background Image')); ?></label>
                            <?php if(getOption('landing_cta_background')): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(getOption('landing_cta_background')); ?>" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="landing_cta_background" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block"><?php echo e(__('CTA Connect Image 1')); ?></label>
                            <?php if(getOption('landing_cta_connect_one')): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(getOption('landing_cta_connect_one')); ?>" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="landing_cta_connect_one" class="form-control">
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label d-block"><?php echo e(__('CTA Connect Image 2')); ?></label>
                            <?php if(getOption('landing_cta_connect_two')): ?>
                            <div class="mb-2">
                                <img src="<?php echo e(getOption('landing_cta_connect_two')); ?>" alt="" class="img-thumbnail"
                                    style="max-height: 120px;">
                            </div>
                            <?php endif; ?>
                            <input type="file" name="landing_cta_connect_two" class="form-control">
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="primary-btn">
                                <?php echo e(__('Save Changes')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\landing-page-settings.blade.php ENDPATH**/ ?>