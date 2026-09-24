<?php $__env->startPush('title'); ?> <?php echo e($title); ?> <?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="section-title">
    <h2 class="title"><?php echo e(__($title)); ?></h2>
    <div class="d-flex flex-wrap gap-2"><a href="<?php echo e(route('admin.garments.invoices.print-report')); ?>" target="_blank" class="primary-btn"><i class="fa fa-print me-1"></i><?php echo e(__('Print')); ?></a><div class="dropdown"><button class="primary-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"><i class="fa fa-download me-1"></i><?php echo e(__('Export')); ?></button><ul class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="<?php echo e(route('admin.garments.invoices.export')); ?>"><i class="fa-solid fa-file-csv me-2"></i><?php echo e(__('Export CSV')); ?></a></li></ul></div><button type="button" class="primary-btn" data-bs-toggle="modal" data-bs-target="#add-modal"><i class="fa fa-plus me-2"></i><?php echo e(__('Create Invoice')); ?></button></div>
</div>

<div class="row gy-4 mb-20 hrm-dashboard-kpis invoice-summary-kpis">
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6"><div class="card-box"><span class="card-icon"><i class="fa-solid fa-file-invoice"></i></span><div class="card-info"><h2><?php echo e($invoiceSummary['total']); ?></h2><h3><?php echo e(__('Total Invoices')); ?></h3></div></div></div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6"><div class="card-box"><span class="card-icon"><i class="fa-solid fa-circle-check"></i></span><div class="card-info"><h2><?php echo e($invoiceSummary['paid']); ?></h2><h3><?php echo e(__('Paid Invoices')); ?></h3></div></div></div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6"><div class="card-box"><span class="card-icon"><i class="fa-solid fa-clock"></i></span><div class="card-info"><h2><?php echo e($invoiceSummary['outstanding']); ?></h2><h3><?php echo e(__('Outstanding')); ?></h3></div></div></div>
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6"><div class="card-box"><span class="card-icon"><i class="fa-solid fa-triangle-exclamation"></i></span><div class="card-info"><h2><?php echo e($invoiceSummary['overdue']); ?></h2><h3><?php echo e(__('Overdue')); ?></h3></div></div></div>
</div>

<div class="settings-page-area">
    <div class="settings-page-right">
        <div class="section-wrap">
            <div class="table-waraper">
                <div class="search-input-wrap mb-3">
                    <label class="icon" for="searchData">
                        <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.625 10.625L11.6875 11.6875" stroke="#6E5858" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.9944 13.4762C11.5852 13.067 11.5852 12.4035 11.9944 11.9944C12.4035 11.5852 13.067 11.5852 13.4762 11.9944L14.9222 13.4405C15.3314 13.8497 15.3314 14.5131 14.9222 14.9222C14.5131 15.3314 13.8497 15.3314 13.4405 14.9222L11.9944 13.4762Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M11.6872 6.72982C11.6872 3.99141 9.46726 1.77148 6.72884 1.77148C3.99043 1.77148 1.77051 3.99141 1.77051 6.72982C1.77051 9.46824 3.99043 11.6882 6.72884 11.6882C9.46726 11.6882 11.6872 9.46824 11.6872 6.72982Z"
                                stroke="#6E5858" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </label>
                    <input type="text" class="search-input" id="searchData"
                        placeholder="<?php echo e(__('Search Invoices...')); ?>" />
                </div>
                <input type="hidden" id="garment-invoice-data-route" value="<?php echo e(route('admin.garments.invoices.index')); ?>">
                <table class="display primary-table dataTable dtr-inline" id="garmentInvoiceDataTable">
                    <thead>
                        <tr>
                            <th class="keep-show"><?php echo e(__('SL')); ?></th>
                            <th><?php echo e(__('Invoice')); ?></th>
                            <th><?php echo e(__('Order')); ?></th>
                            <th><?php echo e(__('Buyer')); ?></th>
                            <th><?php echo e(__('Issue Date')); ?></th>
                            <th><?php echo e(__('Due Date')); ?></th>
                            <th><?php echo e(__('Total')); ?></th>
                            <th><?php echo e(__('Paid')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th class="keep-show"><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- Payment Modal -->
<div class="modal fade zModalTwo" id="payment-modal-<?php echo e($invoice->id); ?>" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" class="ajax reset" action="<?php echo e(route('admin.garments.invoices.payments.store', $invoice->id)); ?>" data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Record Payment')); ?> - <?php echo e($invoice->invoice_number); ?></h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <p class="mb-0 fw-500 text-primary"><?php echo e(__('Outstanding')); ?>: <?php echo e($invoice->currency); ?> <?php echo e(number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Payment Date')); ?> <span class="text-danger">*</span></label>
                                    <input type="date" name="payment_date" class="form-control" value="<?php echo e(now()->toDateString()); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Amount')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.0001" min="0.0001" max="<?php echo e(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount)); ?>" value="<?php echo e(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount)); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Method')); ?> <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-control" required>
                                        <option value="bank_transfer"><?php echo e(__('Bank Transfer')); ?></option>
                                        <option value="lc"><?php echo e(__('L/C')); ?></option>
                                        <option value="cash"><?php echo e(__('Cash')); ?></option>
                                        <option value="other"><?php echo e(__('Other')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Reference')); ?></label>
                                    <input name="reference" class="form-control" placeholder="<?php echo e(__('Transaction / Check Ref')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button class="primary-btn" type="submit"><?php echo e(__('Save Payment')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<!-- Gateway Payment Modal -->
<div class="modal fade zModalTwo" id="gateway-payment-modal-<?php echo e($invoice->id); ?>" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" action="<?php echo e(route('admin.garments.invoices.payments.checkout', $invoice->id)); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Online Invoice Payment')); ?> - <?php echo e($invoice->invoice_number); ?></h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-12">
                                <p class="mb-0 fw-500 text-primary"><?php echo e(__('Outstanding')); ?>: <?php echo e($invoice->currency); ?> <?php echo e(number_format(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount), 2)); ?></p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Gateway')); ?> <span class="text-danger">*</span></label>
                                    <select name="gateway" class="form-control" required>
                                        <?php $__empty_1 = true; $__currentLoopData = $gateways->filter(fn($gateway) => $gateway->currencies->contains('currency', $invoice->currency)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gateway): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($gateway->slug); ?>"><?php echo e($gateway->title); ?> (<?php echo e($invoice->currency); ?>)</option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <option value="" disabled><?php echo e(__('No configured gateway supports this invoice currency')); ?></option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Amount')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" step="0.0001" min="0.0001" max="<?php echo e(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount)); ?>" value="<?php echo e(max(0, (float) $invoice->total_amount - (float) $invoice->paid_amount)); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button class="primary-btn" type="submit"><?php echo e(__('Continue to Payment')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<!-- Add Invoice Modal -->
<div class="modal fade zModalTwo" id="add-modal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content zModalTwo-content">
            <form method="post" class="ajax reset" action="<?php echo e(route('admin.garments.invoices.store')); ?>" data-handler="commonResponseWithPageLoad">
                <?php echo csrf_field(); ?>
                <div class="modal-body zModalTwo-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fs-20 fw-500 lh-38 text-1b1c17 mb-0"><?php echo e(__('Create Commercial Invoice')); ?></h4>
                        <div class="mClose"><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
                    </div>
                    <div class="primary-form">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Order')); ?> <span class="text-danger">*</span></label>
                                    <select name="order_id" class="form-control" required>
                                        <option value=""><?php echo e(__('Select order')); ?></option>
                                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($order->id); ?>"><?php echo e($order->order_number); ?> - <?php echo e($order->buyer?->company_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Invoice Number')); ?> <span class="text-danger">*</span></label>
                                    <input name="invoice_number" class="form-control" placeholder="<?php echo e(__('e.g. INV-2026-001')); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Issue Date')); ?> <span class="text-danger">*</span></label>
                                    <input type="date" name="issue_date" class="form-control" value="<?php echo e(now()->toDateString()); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Due Date')); ?></label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Currency')); ?> <span class="text-danger">*</span></label>
                                    <input name="currency" class="form-control" value="USD" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Amount')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" step="0.0001" min="0" name="amount" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Tax')); ?></label>
                                    <input type="number" step="0.0001" min="0" name="tax_amount" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Paid Amount')); ?></label>
                                    <input type="number" step="0.0001" min="0" name="paid_amount" class="form-control" value="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Status')); ?> <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="issued"><?php echo e(__('Issued')); ?></option>
                                        <option value="draft"><?php echo e(__('Draft')); ?></option>
                                        <option value="partially_paid"><?php echo e(__('Partially Paid')); ?></option>
                                        <option value="paid"><?php echo e(__('Paid')); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-label"><?php echo e(__('Notes')); ?></label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="<?php echo e(__('Additional terms, payment instructions...')); ?>"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-list mt-4 pt-3 border-top">
                        <button type="button" class="primary-btn" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                        <button class="primary-btn" type="submit"><?php echo e(__('Save Invoice')); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-invoices.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\invoices\index.blade.php ENDPATH**/ ?>