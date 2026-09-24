<?php $__env->startPush('admin-style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/styles/main.css')); ?>">
<?php $__env->stopPush(); ?>
<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
</div>
<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <input type="hidden" id="statusChangeRoute"
                value="<?php echo e(route('super_admin.setting.configuration-settings.update')); ?>">
            <input type="hidden" id="configureUrl"
                value="<?php echo e(route('super_admin.setting.configuration-settings.configure')); ?>">

            <form class="ajax" action="<?php echo e(route('super_admin.setting.application-settings.update')); ?>" method="POST"
                enctype="multipart/form-data" data-handler="settingCommonHandler">
                <?php echo csrf_field(); ?>

                <div class="table-responsive">
                    <table class="display data-table primary-table">
                        <thead>
                            <tr>
                                <th class="keep-show"><?php echo e(__('Frontend Feature')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th class="keep-show text-end"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Homepage Hero Section')); ?></h4>
                                        <p>(<?php echo e(__('Control the visibility of the homepage hero section')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'homepage_hero_status')" value="1"
                                            <?php echo e(getOption('homepage_hero_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="homepage_hero_status" id="homepage_hero_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('homepage_hero_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Features Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the features section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'features_section_status')" value="1"
                                            <?php echo e(getOption('features_section_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="features_section_status" id="features_section_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('features_section_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Post Management Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the post management section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_post_management_status')"
                                            value="1"
                                            <?php echo e(getOption('landing_post_management_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_post_management_status" id="landing_post_management_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_post_management_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Campaign Calendar Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the campaign calendar section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_campaign_status')" value="1"
                                            <?php echo e(getOption('landing_campaign_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_campaign_status" id="landing_campaign_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_campaign_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Our Tool Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the tools section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_tools_status')" value="1"
                                            <?php echo e(getOption('landing_tools_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_tools_status" id="landing_tools_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_tools_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Pricing Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the pricing section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_pricing_status')" value="1"
                                            <?php echo e(getOption('landing_pricing_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_pricing_status" id="landing_pricing_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_pricing_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Testimonials Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the testimonials section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_testimonials_status')" value="1"
                                            <?php echo e(getOption('landing_testimonials_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_testimonials_status" id="landing_testimonials_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_testimonials_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Blog Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the blog section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_blog_status')" value="1"
                                            <?php echo e(getOption('landing_blog_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_blog_status" id="landing_blog_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <a href="<?php echo e(route('super_admin.setting.blogs.index')); ?>" class="primary-btn"
                                            title="<?php echo e(__('Manage Blogs')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('FAQ Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the FAQ section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_faq_status')" value="1"
                                            <?php echo e(getOption('landing_faq_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_faq_status" id="landing_faq_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_faq_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('CTA Section')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide the CTA section on homepage')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'landing_cta_status')" value="1"
                                            <?php echo e(getOption('landing_cta_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="landing_cta_status" id="landing_cta_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('landing_cta_status')"
                                            title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Footer Left Content')); ?></h4>
                                        <p>(<?php echo e(__('Set footer left text shown under logo')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'footer_left_status')" value="1"
                                            <?php echo e(getOption('footer_left_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="footer_left_status" id="footer_left_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('footer_content')" title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Footer Social Media')); ?></h4>
                                        <p>(<?php echo e(__('Configure social media links in footer')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'footer_social_status')" value="1"
                                            <?php echo e(getOption('footer_social_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="footer_social_status" id="footer_social_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <button type="button" class="primary-btn"
                                            onclick="configureModal('footer_social')" title="<?php echo e(__('Configure')); ?>">
                                            <?php echo e(__('Configure')); ?>

                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- <tr>
                                <td>
                                    <div class="email-extension">
                                        <h4><?php echo e(__('Footer Right Links')); ?></h4>
                                        <p>(<?php echo e(__('Show/hide footer right menu links')); ?>)</p>
                                    </div>
                                </td>
                                <td>
                                    <label class="toggle-switch">
                                        <input type="checkbox" class="form-check-input"
                                            onchange="changeSettingStatus(this,'footer_right_status')" value="1"
                                            <?php echo e(getOption('footer_right_status', 1)==STATUS_ACTIVE ? 'checked' : ''); ?>

                                            name="footer_right_status" id="footer_right_status">
                                        <span class="slider round"></span>
                                    </label>
                                </td>
                                <td>
                                    <div class="btn-list mt-0">
                                        <a href="<?php echo e(route('super_admin.setting.menu.footer-right')); ?>" class="primary-btn"
                                            title="<?php echo e(__('Manage Footer Right Menus')); ?>">
                                            <?php echo e(__('Manage Menus')); ?>

                                        </a>
                                    </div>
                                </td>
                            </tr> -->

                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Configure Modal -->
<div class="modal fade zModalTwo" id="configureModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content zModalTwo-content">

        </div>
    </div>
</div>


<?php $__env->startPush('style'); ?>
<link rel="stylesheet" href="<?php echo e(asset('super_admin/css/frontend.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/configuration.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\general_settings\frontend.blade.php ENDPATH**/ ?>