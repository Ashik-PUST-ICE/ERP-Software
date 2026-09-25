<?php $__env->startPush('title'); ?> <?php echo e(__('Garments Dashboard')); ?> <?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script src="<?php echo e(asset('admin/js/garment-dashboard.js')); ?>?ver=<?php echo e(config('app.version', 0)); ?>"></script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<input type="hidden" id="garment-dashboard-data-url" value="<?php echo e(route('admin.garments.dashboard.data')); ?>">

<div class="section-title">
    <h2 class="title"><?php echo e(__('Garments Dashboard')); ?></h2>
    <span class="text-muted" style="font-size:1.3rem;"><?php echo e(now()->format('l, d F Y')); ?></span>
</div>


<div class="row gy-4 mb-20 garment-dashboard-kpis">

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FF4F02"/>
                    <path d="M19 13V19L22 22" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M11 19C11 14.5817 14.5817 11 19 11C23.4183 11 27 14.5817 27 19C27 23.4183 23.4183 27 19 27C14.5817 27 11 23.4183 11 19Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiOverdueOrders"><?php echo e($overdueOrders); ?></h2>
                <h3><?php echo e(__('Overdue Orders')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.garments.orders.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('View All')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#FFC402"/>
                    <path d="M12 14H26M12 19H26M12 24H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11 13C11 11.8954 11.8954 11 13 11H25C26.1046 11 27 11.8954 27 13V25C27 26.1046 26.1046 27 25 27H13C11.8954 27 11 26.1046 11 25V13Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiLowStock"><?php echo e($lowStock); ?></h2>
                <h3><?php echo e(__('Low Stock Items')); ?></h3>
            </div>
            <span class="card-status <?php echo e($lowStock > 0 ? 'down' : 'up'); ?>">
                <a href="<?php echo e(route('admin.garments.materials.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('View Items')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($lowStock > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#4778c7"/>
                    <path d="M14 19C14 15.6863 16.6863 13 20 13C23.3137 13 26 15.6863 26 19C26 22.3137 23.3137 25 20 25H14C16.2091 25 18 23.2091 18 21C18 18.7909 16.2091 17 14 17V19Z" stroke="white" stroke-width="1.5"/>
                    <path d="M26 19L30 15M30 15L26 15M30 15V19" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiPendingFinishing"><?php echo e($pendingFinishing); ?></h2>
                <h3><?php echo e(__('Pending Finishing')); ?></h3>
            </div>
            <span class="card-status <?php echo e($pendingFinishing > 0 ? 'down' : 'up'); ?>">
                <a href="<?php echo e(route('admin.garments.finishing.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Review')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($pendingFinishing > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#2c9567"/>
                    <path d="M13 17L19 13L25 17" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 17V23L19 27L25 23V17" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M25 17H27C28.1046 17 29 17.8954 29 19V23" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiReadyShipments"><?php echo e($readyShipments); ?></h2>
                <h3><?php echo e(__('Ready Shipments')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.garments.shipment-documents.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('View')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

</div>


<div class="row gy-4 mb-20 garment-dashboard-kpis garment-dashboard-kpis-secondary">

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#4778c7"/>
                    <path d="M13 15H25M13 19H25M13 23H20" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M11 13C11 11.8954 11.8954 11 13 11H25C26.1046 11 27 11.8954 27 13V25C27 26.1046 26.1046 27 25 27H13C11.8954 27 11 26.1046 11 25V13Z" stroke="white" stroke-width="1.5"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTotalActiveOrders"><?php echo e($totalActiveOrders); ?></h2>
                <h3><?php echo e(__('Active Orders')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.garments.orders.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('View All')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#0FA958"/>
                    <path d="M19 19C21.2091 19 23 17.2091 23 15C23 12.7909 21.2091 11 19 11C16.7909 11 15 12.7909 15 15C15 17.2091 16.7909 19 19 19Z" stroke="white" stroke-width="1.5"/>
                    <path d="M11 27C11 23.134 14.134 20 18 20H20C23.866 20 27 23.134 27 27" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiTotalBuyers"><?php echo e($totalBuyers); ?></h2>
                <h3><?php echo e(__('Total Buyers')); ?></h3>
            </div>
            <span class="card-status up">
                <a href="<?php echo e(route('admin.garments.buyers.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Manage')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-up"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#bd8517"/>
                    <path d="M19 13V19L22 22" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14 25C14 23.3431 15.3431 22 17 22H21C22.6569 22 24 23.3431 24 25V26H14V25Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiPendingProductionPlans"><?php echo e($pendingProductionPlans); ?></h2>
                <h3><?php echo e(__('Pending Production Plans')); ?></h3>
            </div>
            <span class="card-status <?php echo e($pendingProductionPlans > 0 ? 'down' : 'up'); ?>">
                <a href="<?php echo e(route('admin.garments.plans.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Review')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($pendingProductionPlans > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

    
    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-6">
        <div class="card-box">
            <span class="card-icon">
                <svg width="38" height="38" viewBox="0 0 38 38" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="19" cy="19" r="19" fill="#6B02FF"/>
                    <path d="M15 15L23 23M23 15L15 23" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="card-info">
                <h2 id="kpiPendingQc"><?php echo e($pendingQc); ?></h2>
                <h3><?php echo e(__('Pending QC')); ?></h3>
            </div>
            <span class="card-status <?php echo e($pendingQc > 0 ? 'down' : 'up'); ?>">
                <a href="<?php echo e(route('admin.garments.inline-qc.index')); ?>" style="color:inherit; text-decoration:none;"><?php echo e(__('Review')); ?></a>
                <span class="arrow"><i class="fa-solid fa-arrow-<?php echo e($pendingQc > 0 ? 'down' : 'up'); ?>"></i></span>
            </span>
        </div>
    </div>

</div>


<div class="row gy-4">

    
    <div class="col-xl-8 col-lg-7">
        <div class="section-wrap h-100">
            <div class="section-small-title">
                <h3 class="title"><?php echo e(__('Recent Orders')); ?></h3>
                <a href="<?php echo e(route('admin.garments.orders.index')); ?>" class="text-primary" style="font-size:1.2rem;"><?php echo e(__('View All')); ?></a>
            </div>
            <table class="display primary-table w-100" id="garmentRecentOrdersTable" data-url="<?php echo e(route('admin.garments.orders.index')); ?>">
                <thead><tr><th><?php echo e(__('Order')); ?></th><th><?php echo e(__('Buyer')); ?></th><th><?php echo e(__('Quantity')); ?></th><th><?php echo e(__('Delivery')); ?></th></tr></thead>
                <tbody><tr><td colspan="4" class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> <?php echo e(__('Loading...')); ?></td></tr></tbody>
            </table>
        </div>
    </div>

    
    <div class="col-xl-4 col-lg-5">
        <div class="section-wrap h-100">
            <div class="section-small-title"><h3 class="title"><?php echo e(__('Latest Notifications')); ?></h3><i class="fa-regular fa-bell text-primary"></i></div>
            <div id="garmentNotifications"><div class="text-center text-muted py-3"><i class="fa fa-spinner fa-spin"></i> <?php echo e(__('Loading...')); ?></div></div>
        </div>
    </div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('auto_posts.admin.layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ashik\ERP-Software\resources\views\admin\garments\dashboard.blade.php ENDPATH**/ ?>