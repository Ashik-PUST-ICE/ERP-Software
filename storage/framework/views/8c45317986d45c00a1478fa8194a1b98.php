<?php $__env->startPush('title'); ?>
<?php echo e($title); ?>

<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e($title); ?></h2>
</div>
<div class="settings-page-area">
    <?php echo $__env->make('auto_posts.super_admin.setting.partials.general-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="section-inner-title">
                <h3 class="title"><?php echo e(__('AI Settings')); ?></h3>
            </div>
            <div class="primary-form">
                <form id="ai-settings-form" class="ajax" action="<?php echo e(route('super_admin.setting.ai-settings.update')); ?>"
                    method="POST" data-handler="settingCommonHandler">
                    <?php echo csrf_field(); ?>
                    <?php
                    $openaiModel = getOption('openai_model', config('ai.openai_default_model'));
                    $models = config('ai.openai_models', ['gpt-4o-mini', 'gpt-4o', 'gpt-4-turbo', 'gpt-3.5-turbo']);
                    $labels = config('ai.openai_model_labels', []);
                    ?>
                    <div class="row gy-4">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group with-small-text">
                                <label for="openai_ai_status" class="form-label"><?php echo e(__('Open AI System')); ?> <span
                                        class="required">*</span></label>
                                <select name="openai_ai_status" id="openai_ai_status" class="select form-control wide">
                                    <option value="1" <?php echo e(getOption('openai_ai_status', 1) == 1 ? 'selected' : ''); ?>>
                                        <?php echo e(__('Active')); ?></option>
                                    <option value="0" <?php echo e(getOption('openai_ai_status', 1) == 0 ? 'selected' : ''); ?>>
                                        <?php echo e(__('Inactive')); ?></option>
                                </select>
                                <small
                                    class="text-muted"><?php echo e(__('Globally enable or disable AI features for all plans')); ?></small>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12" id="wrap-api-key">
                            <div class="form-group">
                                <label for="openai_api_key" class="form-label"><?php echo e(__('OpenAI API Key')); ?> <span
                                        class="required">*</span></label>
                                <input type="password" name="openai_api_key" id="openai_api_key" class="form-control"
                                    value="<?php echo e(getOption('openai_api_key')); ?>" placeholder="sk-..." autocomplete="off">
                                <small class="text-muted d-block mt-1"><?php echo e(__('Get key from')); ?> <a
                                        href="https://platform.openai.com/api-keys" target="_blank"
                                        rel="noopener">OpenAI</a>.</small>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="ai_model" class="form-label"><?php echo e(__('Select model')); ?> <span
                                        class="required">*</span></label>
                                <select name="ai_model" id="ai_model" class="select form-control wide">
                                    <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $aiModel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="openai__<?php echo e($aiModel); ?>"
                                        <?php echo e($openaiModel === $aiModel ? 'selected' : ''); ?>>
                                        <?php echo e($labels[$aiModel] ?? $aiModel); ?>

                                    </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small
                                    class="text-muted d-block mt-1"><?php echo e(__('Text generation uses this model. Image & video use OpenAI only.')); ?></small>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group">
                                <label for="openai_max_tokens" class="form-label"><?php echo e(__('Max Token')); ?> <span
                                        class="required">*</span></label>
                                <input type="number" id="openai_max_tokens" name="openai_max_tokens"
                                    value="<?php echo e(getOption('openai_max_tokens', config('ai.openai_default_max_tokens', 1000))); ?>"
                                    class="form-control" min="100" max="4096" placeholder="1000">
                                <small class="text-muted"><?php echo e(__('Max length of generated response')); ?></small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="btn-list">
                <button type="submit" form="ai-settings-form" class="primary-btn"><?php echo e(__('Save')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->startPush('script'); ?>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('auto_posts.super_admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\ai_settings\open-ai-settings.blade.php ENDPATH**/ ?>