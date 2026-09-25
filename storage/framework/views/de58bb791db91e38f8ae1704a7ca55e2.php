<div class="table-responsive zTable-responsive">
    <table class="display search-datatable primary-table dataTable dtr-inline zTable">
        <thead>
            <tr>
                <th class="keep-show"><?php echo e(__("Code")); ?></th>
                <th><?php echo e(__("Symbol")); ?></th>
                <th><?php echo e(__("Placement")); ?></th>
                <th class="keep-show"><?php echo e(__("Action")); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td>
                    <?php echo e($currency->currency_code); ?>

                    <?php if($currency->current_currency == STATUS_ACTIVE): ?>
                    <span class="badge bg-success ms-2"><?php echo e(__('Default')); ?></span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($currency->symbol); ?></td>
                <td><?php echo e($currency->currency_placement == 'before' ? __('Before Amount') : __('After Amount')); ?>

                </td>
                <td>
                    <div class="inline-flex">
                        <div class="dropdown options-area">
                            <a class="options-btn" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fa-solid fa-ellipsis"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="openEditModal('<?php echo e(route('super_admin.setting.currencies.edit', $currency->id)); ?>', <?php echo e($currency->id); ?>)">
                                        <?php echo e(__('Edit')); ?>

                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0)"
                                        onclick="deleteItem('<?php echo e(route('super_admin.setting.currencies.delete', $currency->id)); ?>')">
                                        <?php echo e(__('Delete')); ?>

                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="4" class="text-center"><?php echo e(__('No currencies found')); ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php echo $__env->make('auto_posts.super_admin.pagination.common-pagination', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\auto_posts\super_admin\setting\currencies\partials\currencies_table.blade.php ENDPATH**/ ?>