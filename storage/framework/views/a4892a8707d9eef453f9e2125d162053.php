<?php $__env->startPush('title'); ?>
    <?php echo e(__('Profile')); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="p-30">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-black pb-16"><?php echo e(__('Profile')); ?></h4>
            <div class="bg-white bd-half bd-c-ebedf0 bd-ra-25 p-30">
                <!-- Tab List -->
                <ul class="nav nav-tabs zTabHead" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane"
                                aria-selected="true"><?php echo e(__('Profile')); ?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="editProfile-tab" data-bs-toggle="tab"
                                data-bs-target="#editProfile-tab-pane" type="button" role="tab"
                                aria-controls="editProfile-tab-pane" aria-selected="false"><?php echo e(__('Edit Profile')); ?></button>
                    </li>
                </ul>
                <!-- Tab Content -->
                <div class="tab-content zTabContent" id="myTabContent">
                    <!-- Profile -->
                    <div class="tab-pane fade show active" id="profile-tab-pane" role="tabpanel"
                         aria-labelledby="profile-tab" tabindex="0">
                        <!-- User Photo ~ Social link -->
                        <div class="pt-30 pb-40 d-flex justify-content-between align-items-center flex-wrap rg-30">
                            <!-- User Photo ~ name -->
                            <div class="d-flex align-items-center flex-wrap g-18">

                                <div class="flex-shrink-0 w-110 h-110 rounded-circle overflow-hidden bd-three bd-c-cdef84">
                                    <img class="w-100" src="<?php echo e(asset(getFileUrl($user->image))); ?>"
                                         alt="<?php echo e($user->name); ?>" />
                                </div>

                                <div class="">
                                    <h4 class="fs-24 fs-sm-20 fw-500 lh-34 text-1b1c17"><?php echo e($user->name); ?></h4>
                                </div>
                            </div>
                            <!-- Social Link -->

                        </div>
                        <!-- Bio ~ Info -->
                        <div class="row rg-30">
                            <!-- Bio -->
                            <div class="col-lg-8">
                                <div class="py-20 px-25 bd-ra-10 bg-f9f9f9">
                                    <!-- Personal Info -->
                                    <ul class="zList-one">
                                        <li>
                                            <p><?php echo e(__('Full Name')); ?> :</p>
                                            <p><?php echo e($user->name); ?></p>
                                        </li>
                                        <li>
                                            <p><?php echo e(__('Nick Name')); ?> :</p>
                                            <p><?php echo e($user->nick_name); ?></p>
                                        </li>
                                        <?php if($user->show_email_in_public == STATUS_SUCCESS): ?>
                                            <li>
                                                <p><?php echo e(__('Email')); ?> :</p>
                                                <p><?php echo e($user->email); ?></p>
                                            </li>
                                        <?php endif; ?>
                                        <?php if($user->show_phone_in_public == STATUS_SUCCESS): ?>
                                            <li>
                                                <p><?php echo e(__('Phone')); ?> :</p>
                                                <p><?php echo e($user->mobile); ?></p>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Edit Profile -->
                    <div class="tab-pane fade" id="editProfile-tab-pane" role="tabpanel" aria-labelledby="editProfile-tab"
                         tabindex="0">
                        <div class="max-w-840">
                            <form method="POST" class="ajax" data-handler="commonResponseRedirect"
                                  data-redirect-url="<?php echo e(route('profile')); ?>" action="<?php echo e(route('profile_update')); ?>">
                                <?php echo csrf_field(); ?>
                                <!-- Photo -->
                                <div class="pb-40"></div>
                                <!-- Personal Info -->
                                <div class="pb-30">
                                    <h4 class="fs-18 fw-500 lh-22 text-1b1c17 pb-20 mt-3"><?php echo e(__('Personal Info')); ?></h4>
                                    <div class="row rg-25">
                                        <!-- Photo -->
                                        <div class="pb-40">
                                            <div class="upload-img-box profileImage-upload">
                                                <div class="icon"><img src="assets/images/icon/edit-2.svg" alt="" /></div>
                                                <img src="<?php echo e(getFileUrl($user->image)); ?>" />
                                                <input type="file" name="image" id="zImageUpload" accept="image/*,video/*"
                                                       onchange="previewFile(this)" />
                                            </div>
                                        </div>
                                        <!-- Personal Info -->
                                        <div class="col-md-6">
                                            <div class="primary-form-group">
                                                <div class="primary-form-group-wrap">
                                                    <label for="epFullName" class="form-label"><?php echo e(__('Full Name')); ?></label>
                                                    <input type="text" class="primary-form-control" id="epFullName"
                                                           value="<?php echo e($user->name); ?>" name="name"
                                                           placeholder="<?php echo e(__('Your Name')); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="primary-form-group">
                                                <div class="primary-form-group-wrap">
                                                    <label for="epNickName" class="form-label"><?php echo e(__('Nick Name')); ?></label>
                                                    <input type="text" class="primary-form-control" id="epNickName"
                                                           value="<?php echo e($user->nick_name); ?>" name="nick_name"
                                                           placeholder="<?php echo e(__('Your Nick Name')); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Contact Info -->
                                <div class="pb-30">
                                    <h4 class="fs-18 fw-500 lh-22 text-1b1c17 pb-20"><?php echo e(__('Contact Info')); ?></h4>
                                    <div class="row rg-25">
                                        <div class="col-md-6">
                                            <div class="primary-form-group">
                                                <div class="primary-form-group-wrap">
                                                    <label for="epPhoneNumber" class="form-label"><?php echo e(__('Phone Number')); ?></label>
                                                    <input type="mobile" value="<?php echo e($user->mobile); ?>" name="mobile"
                                                           class="primary-form-control" id="epPhoneNumber"
                                                           placeholder="eg: (+880) 1254 8593" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="primary-form-group">
                                                <div class="primary-form-group-wrap">
                                                    <label for="epEmail" class="form-label"><?php echo e(__('Personal Email Address')); ?></label>
                                                    <input type="email" value="<?php echo e($user->email); ?>" name="email" disabled
                                                           class="primary-form-control" id="epEmail"
                                                           placeholder="<?php echo e(__('Your Email')); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit"
                                        class="py-13 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one"><?php echo e(__('Save Changes')); ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add More Modal -->
    <div class="modal fade zModalTwo" id="addMoreModal" tabindex="-1" aria-labelledby="addMoreModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content zModalTwo-content">
                <div class="modal-body zModalTwo-body">
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center pb-30">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17"><?php echo e(__('Add Info')); ?></h4>
                        <div class="mClose">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                                    src="<?php echo e(asset('assets/images/icon/delete.svg')); ?>" alt="" /></button>
                        </div>
                    </div>
                    <!-- Body -->
                    <form method="POST" class="ajax" data-handler="commonResponseForModal"
                          action="<?php echo e(route('add_institution')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="pb-25">
                            <div class="row rg-25">
                                <div class="col-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap">
                                            <label for="epDegree1" class="form-label"><?php echo e(__('Degree')); ?></label>
                                            <input type="text" class="primary-form-control" id="epDegree1" name="degree"
                                                   placeholder="<?php echo e(__('Your Degree')); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap">
                                            <label for="epInstitute" class="form-label"><?php echo e(__('Institution')); ?></label>
                                            <input type="text" class="primary-form-control" id="epInstitute"
                                                   name="institute" placeholder="<?php echo e(__('Your Institution')); ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="primary-form-group">
                                        <div class="primary-form-group-wrap">
                                            <label for="epPassingYear1" class="form-label"><?php echo e(__('Passing Year')); ?></label>
                                            <input type="text" class="primary-form-control" id="epPassingYear1"
                                                   name="passing_year" placeholder="<?php echo e(__('Your Passing Year')); ?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                                class="py-13 px-26 bg-cdef84 border-0 bd-ra-12 fs-15 fw-500 lh-25 text-black hover-bg-one"><?php echo e(__('Save Now')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
    <script src="<?php echo e(asset('user/js/profile.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.user.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\user\profile.blade.php ENDPATH**/ ?>