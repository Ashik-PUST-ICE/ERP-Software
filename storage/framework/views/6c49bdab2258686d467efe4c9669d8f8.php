<form class="ajax reset" action="<?php echo e($order ? route('admin.garments.orders.update', $order->id) : route('admin.garments.orders.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($order): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($order ? __('Edit Order') : __('Add Order')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>

        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Buyer')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="buyer_id" required>
                            <option value=""><?php echo e(__('Select Buyer')); ?></option>
                            <?php $__currentLoopData = $buyers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $buyerOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($buyerOption->id); ?>" <?php if(old('buyer_id', $order?->buyer_id) == $buyerOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($buyerOption->company_name); ?> (<?php echo e($buyerOption->buyer_code); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Style')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="style_id" required>
                            <option value=""><?php echo e(__('Select Style')); ?></option>
                            <?php $__currentLoopData = $styles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $styleOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($styleOption->id); ?>" <?php if(old('style_id', $order?->style_id) == $styleOption->id): echo 'selected'; endif; ?>>
                                    <?php echo e($styleOption->style_code); ?> - <?php echo e($styleOption->style_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order / PO No.')); ?> <span class="required">*</span></label>
                        <input type="text" class="form-control" name="order_number" value="<?php echo e(old('order_number', $order?->order_number)); ?>" placeholder="<?php echo e(__('e.g. PO-1001')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Quantity')); ?> <span class="required">*</span></label>
                        <input type="number" min="1" class="form-control" name="quantity" value="<?php echo e(old('quantity', $order?->quantity)); ?>" placeholder="<?php echo e(__('Units')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Unit Price')); ?> <span class="required">*</span></label>
                        <input type="number" min="0" step="0.0001" class="form-control" name="unit_price" value="<?php echo e(old('unit_price', $order?->unit_price ?? 0)); ?>" placeholder="<?php echo e(__('FOB price')); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="order_date" value="<?php echo e(old('order_date', $order?->order_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Delivery Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="delivery_date" value="<?php echo e(old('delivery_date', $order?->delivery_date?->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentOrderStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusValue => $statusData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($statusValue); ?>" <?php if(old('status', $order?->status ?? GARMENT_ORDER_STATUS_PENDING) == $statusValue): echo 'selected'; endif; ?>>
                                    <?php echo e(__($statusData[0])); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Product Description')); ?></label>
                        <textarea class="form-control" name="product_description" rows="2" placeholder="<?php echo e(__('Describe garment type, color, size range...')); ?>"><?php echo e(old('product_description', $order?->product_description)); ?></textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Internal Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="2" placeholder="<?php echo e(__('Add notes for merchandising or production...')); ?>"><?php echo e(old('notes', $order?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button type="submit" class="primary-btn"><?php echo e($order ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\orders\form.blade.php ENDPATH**/ ?>