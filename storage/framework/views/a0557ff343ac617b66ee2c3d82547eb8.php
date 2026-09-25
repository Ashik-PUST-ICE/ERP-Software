<?php $__env->startPush('title'); ?>
<?php echo e(__('Generate AI Content')); ?>

<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div id="ai-generate-wrap" class="content-wrapper"
    data-submit-url="<?php echo e(route('admin.ai.generate-content.submit')); ?>"
    data-csrf-token="<?php echo e(csrf_token()); ?>"
    data-toggle-save-url-pattern="<?php echo e(route('admin.ai.generated-content.toggle-save', ['id' => ':id'])); ?>"
    data-msg-generating-video="<?php echo e(__('Generating video (this may take a few minutes)...')); ?>"
    data-msg-generating="<?php echo e(__('Generating...')); ?>"
    data-msg-done="<?php echo e(__('Done.')); ?>"
    data-msg-success="<?php echo e(__('Content generated successfully.')); ?>"
    data-msg-generation-failed="<?php echo e(__('Generation failed.')); ?>"
    data-msg-request-failed="<?php echo e(__('Request failed. Try again.')); ?>">
    <div class="section-title">
        <h2 class="title"><?php echo e(__('Generate AI Content')); ?></h2>
        <a href="<?php echo e(route('admin.ai.generated-content.list')); ?>" class="primary-btn"><?php echo e(__('Generated List')); ?></a>
    </div>
    <div class="section-wrap">
        <div class="primary-form">
            <div class="row gy-4">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="content_type" class="form-label"><?php echo e(__('Content Type')); ?> <span
                                class="required">*</span></label>
                        <select id="content_type" name="content_type" class="select form-control wide" required>
                            <option value="text"><?php echo e(__('Text')); ?></option>
                            <option value="image"><?php echo e(__('Image')); ?></option>
                            <option value="video"><?php echo e(__('Video')); ?></option>
                        </select>
                        <small class="text-muted"><?php echo e(__('Choose what you want to generate.')); ?></small>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="tone" class="form-label"><?php echo e(__('Tone')); ?></label>
                        <select id="tone" name="tone" class="select form-control wide">
                            <option value=""><?php echo e(__('Default')); ?></option>
                            <?php $__currentLoopData = config('ai.tones', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e(__($label)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="language" class="form-label"><?php echo e(__('Language')); ?></label>
                        <select id="language" name="language" class="select form-control wide">
                            <option value=""><?php echo e(__('Default')); ?></option>
                            <?php $__currentLoopData = languageIsoCode(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($code); ?>"><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="max_tokens" class="form-label"><?php echo e(__('Max Tokens')); ?></label>
                        <input type="number" id="max_tokens" name="max_tokens" class="form-control" min="100" max="4096"
                            placeholder="<?php echo e(getOption('openai_max_tokens', 1000)); ?>" value="">
                        <small class="text-muted"><?php echo e(__('Leave empty to use default from settings')); ?></small>
                    </div>
                </div>
                <div class="col-12" id="wrap-manual-image" style="display: none;">
                    <div class="form-group">
                        <small class="text-muted d-block mb-1"><?php echo e(__('Describe the image you want in the prompt below. AI will generate an image from your text.')); ?></small>
                    </div>
                </div>
                <div class="col-12" id="wrap-manual-video" style="display: none;">
                    <div class="form-group">
                        <small class="text-muted d-block mb-1"><?php echo e(__('Describe the video you want in the prompt below. AI will generate a short video (may take a few minutes).')); ?></small>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="prompt" class="form-label"><?php echo e(__('Your prompt')); ?> <span
                                class="required">*</span></label>
                        <textarea id="prompt" name="prompt" class="summernote" rows="5"
                            placeholder="<?php echo e(__('e.g. Write a short social media post about...')); ?>" required></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <button type="button" id="btn-generate" class="primary-btn"><?php echo e(__('Generate')); ?></button>
                    <span id="generate-status" class="ms-3 text-muted"></span>
                </div>
            </div>
        </div>
        <div class="mt-4" id="result-wrap" style="display: none;" data-id="">
            <div class="section-inner-title d-flex align-items-center justify-content-between">
                <h4 class="title"><?php echo e(__('Generated content')); ?></h4>
                <button type="button" id="btn-save-content" class="primary-btn btn-sm">
                    <i class="fa-regular fa-bookmark me-1"></i> <?php echo e(__('Save to List')); ?>

                </button>
            </div>
            <div class="primary-form">
                <div class="form-group" id="result-text-wrap">
                    <textarea id="generated-text" class="form-control" rows="10"
                        placeholder="<?php echo e(__('Generated content will appear here. You can edit and copy.')); ?>"></textarea>
                    <small class="text-muted"><?php echo e(__('You can edit and copy this content for your posts.')); ?></small>
                </div>
                <div class="form-group" id="result-media-wrap" style="display: none;">
                    <div id="result-image-wrap" style="display: none;">
                        <img id="result-generated-image" src="" alt="" style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 8px;">
                        <p class="mt-2 mb-0"><a id="result-image-link" href="" target="_blank" rel="noopener"><?php echo e(__('Open image')); ?></a></p>
                    </div>
                    <div id="result-video-wrap" style="display: none;">
                        <video id="result-generated-video" controls style="max-width: 100%; max-height: 400px; border: 1px solid #ddd; border-radius: 8px;" src=""></video>
                        <p class="mt-2 mb-0"><a id="result-video-link" href="" target="_blank" rel="noopener"><?php echo e(__('Download video')); ?></a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/ai-generate-content.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\admin\ai\generate.blade.php ENDPATH**/ ?>