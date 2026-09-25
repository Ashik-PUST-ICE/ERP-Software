<?php $__env->startPush('title'); ?>
    <?php echo e(__('Version Update')); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="p-30">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-black pb-16"><?php echo e(__($title)); ?></h4>
            <div class="row gap-5">
                <div class="col-md-12">
                    <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                        <form action="<?php echo e(route('admin.store-script-file')); ?>" enctype="multipart/form-data" method="post">
                            <?php echo csrf_field(); ?>
                            <div class="gap-4 row">
                                <div class="col-md-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap">
                                            <label for="currentPassword" class="form-label"><?php echo e(__('File')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="file" class="primary-form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap">
                                            <label for="currentPassword" class="form-label"><?php echo e(__('Path')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="primary-form-control" name="path"
                                                   placeholder="<?php echo e(__('Path')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                        class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one"><?php echo e(__('Save')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                        <form action="<?php echo e(route('admin.load-script-file')); ?>" enctype="multipart/form-data"
                              method="post">
                            <?php echo csrf_field(); ?>
                            <div class="gap-4 row">
                                <div class="col-md-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap">
                                            <label for="currentPassword" class="form-label"><?php echo e(__('Path')); ?> <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="primary-form-control" name="path" placeholder="<?php echo e(__('Path')); ?>">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="py-10 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one"><?php echo e(__('Download')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content area end -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\version_update\update-path-file.blade.php ENDPATH**/ ?>