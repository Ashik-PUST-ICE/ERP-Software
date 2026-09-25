<?php $__env->startPush('title'); ?>
<?php echo e(__('Edit Page')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="p-30">
    <div class="row gy-4">
        <div class="col-12">
            <div class="section-title">
                <h2 class="title"><?php echo e(__('Edit Page')); ?></h2>
                <a href="<?php echo e(route('super_admin.setting.page.index')); ?>" class="primary-btn"><?php echo e(__('Back')); ?></a>
            </div>

            <form action="<?php echo e(route('super_admin.setting.page.update', $page->uuid)); ?>" enctype="multipart/form-data"
                method="post" class="ajax reset" data-handler="commonResponseRedirect"
                data-redirect-url="<?php echo e(route('super_admin.setting.page.index')); ?>">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="section-wrap">
                            <div class="primary-form">
                                <div class="row gy-4">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Title')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="title" value="<?php echo e($page->en_title); ?>"
                                                placeholder="<?php echo e(__('Title')); ?>" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Description')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <textarea name="en_description" class="summernote"
                                                id="summernote"><?php echo e($page->en_description); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Meta Title')); ?></label>
                                            <input type="text" name="meta_title"
                                                value="<?php echo e(old('meta_title', $page->meta_title)); ?>"
                                                placeholder="<?php echo e(__('Meta title')); ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Meta Keywords')); ?></label>
                                            <input type="text" name="meta_keywords"
                                                value="<?php echo e(old('meta_keywords',  $page->meta_keywords)); ?>"
                                                placeholder="<?php echo e(__('meta keywords')); ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('Meta Description')); ?></label>
                                            <input type="text" name="meta_description"
                                                value="<?php echo e(old('meta_description', $page->meta_description)); ?>"
                                                placeholder="<?php echo e(__('meta description')); ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-label"><?php echo e(__('OG Image')); ?></label>
                                            <div class="zImage-upload-details mw-100">
                                                <div class="upload-img-box zImage-inside upload-image-box-new">
                                                    <?php if($page->og_image): ?>
                                                    <img src="<?php echo e(is_numeric($page->og_image) ? getFileUrl($page->og_image) : asset($page->og_image)); ?>"
                                                        alt="<?php echo e($page->en_title); ?>" class="preview-image">
                                                    <?php else: ?>
                                                    <img src="<?php echo e(asset('assets/images/no-image.jpg')); ?>"
                                                        alt="<?php echo e($page->en_title); ?>" class="preview-image">
                                                    <?php endif; ?>
                                                    <input type="file" class="form-control" name="og_image"
                                                        accept="image/*" onchange="previewFile(this)">
                                                </div>
                                            </div>
                                            <p><span class="text-black"><?php echo e(__('Accepted Files')); ?>:</span> PNG, JPG <br>
                                                <!-- <span class="text-black"><?php echo e(__('Recommend Size')); ?>:</span> 1200 x 627 -->
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="section-wrap my-plan-area h-100">
                            <div class="plan-head"
                                style="padding-bottom: 12px; margin-bottom: 12px; border-bottom: 1px solid #ebedf0;">
                                <h3 style="font-size: 14px; font-weight: 600; margin-bottom: 4px;">
                                    <?php echo e(__('Page Actions')); ?></h3>
                            </div>
                            <div class="plan-body" style="padding-top: 10px;">
                                <ul class="plan-features" style="gap: 8px; margin: 0; padding: 0; list-style: none;">
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <?php echo e(__('URL')); ?>: <strong><?php echo e(url($page->slug)); ?></strong>
                                    </li>
                                    <li
                                        style="font-size: 13px; display: flex; align-items: center; gap: 8px; color: #4b5563;">
                                        <svg width="12" height="12" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2.91675 8.45898C2.91675 8.45898 3.79175 8.45898 4.95841 10.5007C4.95841 10.5007 8.20105 5.15343 11.0834 4.08398"
                                                stroke="#4b5563" stroke-width="0.875" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        <?php echo e(__('Slug')); ?>: <strong><?php echo e($page->slug); ?></strong>
                                    </li>
                                </ul>
                            </div>
                            <div class="plan-footer"
                                style="padding-top: 15px; margin-top: 15px; border-top: 1px solid #ebedf0;">
                                <button type="submit" class="primary-btn w-100"><?php echo e(__('Update Page')); ?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\page\edit.blade.php ENDPATH**/ ?>