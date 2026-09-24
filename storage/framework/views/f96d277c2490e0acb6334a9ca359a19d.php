<div class="table-responsive zTable-responsive">
    <table class="table zTable">
        <thead>
            <tr>
                <th class="min-w-160">
                    <div><?php echo e(__('Key')); ?></div>
                </th>
                <th class="min-w-160">
                    <div><?php echo e(__('Value')); ?></div>
                </th>
                <th class="text-center w-28">
                    <div><?php echo e(__('Action')); ?></div>
                </th>
            </tr>
        </thead>
        <tbody id="append">
            <?php $__empty_1 = true; $__currentLoopData = $translators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td data-label="Key">
                    <textarea type="text" class="key form-control" readonly required><?php echo $key; ?></textarea>
                </td>
                <td data-label="Value">
                    <input type="hidden" value="0" class="is_new">
                    <textarea type="text" class="val form-control" required><?php echo $value; ?></textarea>
                </td>
                <td data-label="Action" class="text-end col-1">
                    <button type="button" class="primary-btn updateLangItem"><?php echo e(__('Update')); ?></button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="text-center"><?php echo e(__('No Data Found')); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<?php echo $__env->make('auto_posts.super_admin.pagination.common-pagination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\languages\partials\translations_table.blade.php ENDPATH**/ ?>