<form class="ajax reset" action="<?php echo e($document ? route('admin.garments.shipment-documents.update', $document->id) : route('admin.garments.shipment-documents.store')); ?>" method="post" data-handler="commonResponseWithPageLoad">
    <?php echo csrf_field(); ?>
    <?php if($document): ?>
        <?php echo method_field('put'); ?>
    <?php endif; ?>
    <div class="modal-body zModalTwo-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e($document ? __('Edit Shipment Document') : __('Add Shipment Document')); ?></h4>
            <div class="mClose">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </div>
        <div class="primary-form">
            <div class="row gy-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Order')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="order_id" required>
                            <option value=""><?php echo e(__('Select Order')); ?></option>
                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($order->id); ?>" <?php if(old('order_id', $document?->order_id) == $order->id): echo 'selected'; endif; ?>><?php echo e($order->order_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Document Type')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="document_type" required>
                            <?php $__currentLoopData = garmentShipmentDocumentTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('document_type', $document?->document_type) == $value): echo 'selected'; endif; ?>><?php echo e(__($label)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Document Number')); ?> <span class="required">*</span></label>
                        <input class="form-control" name="document_number" value="<?php echo e(old('document_number', $document?->document_number)); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Document Date')); ?> <span class="required">*</span></label>
                        <input type="date" class="form-control" name="document_date" value="<?php echo e(old('document_date', $document?->document_date?->format('Y-m-d') ?? now()->format('Y-m-d'))); ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Shipper')); ?></label>
                        <input class="form-control" name="shipper" value="<?php echo e(old('shipper', $document?->shipper)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Consignee')); ?></label>
                        <input class="form-control" name="consignee" value="<?php echo e(old('consignee', $document?->consignee)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Port of Loading')); ?></label>
                        <input class="form-control" name="port_of_loading" value="<?php echo e(old('port_of_loading', $document?->port_of_loading)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Port of Discharge')); ?></label>
                        <input class="form-control" name="port_of_discharge" value="<?php echo e(old('port_of_discharge', $document?->port_of_discharge)); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Carrier')); ?></label>
                        <input class="form-control" name="carrier" value="<?php echo e(old('carrier', $document?->carrier)); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Shipment Date')); ?></label>
                        <input type="date" class="form-control" name="shipment_date" value="<?php echo e(old('shipment_date', $document?->shipment_date?->format('Y-m-d'))); ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Status')); ?> <span class="required">*</span></label>
                        <select class="form-control" name="status" required>
                            <?php $__currentLoopData = garmentShipmentStatuses(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php if(old('status', $document?->status ?? GARMENT_SHIPMENT_STATUS_DRAFT) == $value): echo 'selected'; endif; ?>><?php echo e(__($status[0])); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label"><?php echo e(__('Notes')); ?></label>
                        <textarea class="form-control" name="notes" rows="3"><?php echo e(old('notes', $document?->notes)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="btn-list mt-4 pt-3 border-top">
            <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
            <button class="primary-btn" type="submit"><?php echo e($document ? __('Update') : __('Save')); ?></button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\shipment-documents\form.blade.php ENDPATH**/ ?>