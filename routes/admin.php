<?php

use App\Http\Controllers\AutoPost\Admin\BillingController;
use App\Http\Controllers\AutoPost\Admin\SubscriptionRefundController;
use App\Http\Controllers\AutoPost\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\AutoPost\Admin\PricingController;
use App\Http\Controllers\AutoPost\Admin\RolePermissionController as RoleController;
use App\Http\Controllers\AutoPost\Admin\ProfileController;
use App\Http\Controllers\AutoPost\Admin\SettingController;
use App\Http\Controllers\AutoPost\Admin\UserController;
use App\Http\Controllers\AutoPost\Admin\AIContentGenerationController;
use App\Http\Controllers\Admin\HRM\HrmDashboardController;
use App\Http\Controllers\Admin\HRM\DepartmentController as HrmDepartmentController;
use App\Http\Controllers\Admin\HRM\DesignationController as HrmDesignationController;
use App\Http\Controllers\Admin\HRM\EmployeeController as HrmEmployeeController;
use App\Http\Controllers\Admin\HRM\AttendanceController as HrmAttendanceController;
use App\Http\Controllers\Admin\HRM\LeaveController as HrmLeaveController;
use App\Http\Controllers\Admin\HRM\PayrollController as HrmPayrollController;
use App\Http\Controllers\Admin\Garments\BuyerController as GarmentBuyerController;
use App\Http\Controllers\Admin\Garments\GarmentOrderController;
use App\Http\Controllers\Admin\Garments\StyleController;
use App\Http\Controllers\Admin\Garments\CostingController;
use App\Http\Controllers\Admin\Garments\TnaTaskController;
use App\Http\Controllers\Admin\Garments\ProductionPlanController;
use App\Http\Controllers\Admin\Garments\MaterialController;
use App\Http\Controllers\Admin\Garments\GrnController;
use App\Http\Controllers\Admin\Garments\StoreIssueController;
use App\Http\Controllers\Admin\Garments\CuttingController;
use App\Http\Controllers\Admin\Garments\SewingProductionController;
use App\Http\Controllers\Admin\Garments\EfficiencyController;
use App\Http\Controllers\Admin\Garments\InlineQcController;
use App\Http\Controllers\Admin\Garments\FinalInspectionController;
use App\Http\Controllers\Admin\Garments\DefectRejectionController;
use App\Http\Controllers\Admin\Garments\FinishingController;
use App\Http\Controllers\Admin\Garments\PackingListController;
use App\Http\Controllers\Admin\Garments\ShipmentDocumentController;
use App\Http\Controllers\Admin\Garments\OrderProfitLossController;
use App\Http\Controllers\Admin\Garments\AccountingEntryController;
use App\Http\Controllers\Admin\Garments\IncentiveController;
use App\Http\Controllers\Admin\Garments\ProductionAttendanceController;
use App\Http\Controllers\Admin\Garments\GarmentDashboardController;
use App\Http\Controllers\Admin\Garments\GarmentNotificationController;
use App\Http\Controllers\Admin\Garments\SupplierController;
use App\Http\Controllers\Admin\Garments\BuyerPortalController;
use App\Http\Controllers\Admin\Garments\PurchaseOrderController;
use App\Http\Controllers\Admin\Garments\ShipmentTrackingController;
use App\Http\Controllers\Admin\Garments\WarehouseController;
use App\Http\Controllers\Admin\Garments\AuditLogController;
use App\Http\Controllers\Admin\Garments\StockMovementController;
use App\Http\Controllers\Admin\Garments\WarehouseTransferController;
use App\Http\Controllers\Admin\Garments\MaterialScannerController;
use App\Http\Controllers\Admin\Garments\GarmentExportController;
use App\Http\Controllers\Admin\Garments\MerchandiserController;
use App\Http\Controllers\Admin\Garments\ApprovalController;
use App\Http\Controllers\Admin\Garments\CostingVarianceController;
use App\Http\Controllers\Admin\Garments\MerchandiserManagementController;
use App\Http\Controllers\Admin\Garments\MerchandiserInsightsController;
use App\Http\Controllers\Admin\Garments\ProductionAnalyticsController;
use App\Http\Controllers\Admin\Garments\InvoiceController;
use App\Http\Controllers\Admin\Garments\ApArController;
use App\Http\Controllers\Admin\Garments\GarmentPaymentGatewayController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group.
|
*/

Route::group(['middleware' => ['auth', 'admin']], function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\AutoPost\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Pricing
    Route::prefix('pricing')->name('pricing.')->group(function () {
        Route::get('/', [PricingController::class, 'index'])->name('index');
        Route::get('/checkout', [PricingController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [PricingController::class, 'processCheckout'])->name('process-checkout');
        Route::post('/pay', [PricingController::class, 'pay'])->name('pay');
        Route::get('/get-currency', [PricingController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::get('/payment/verify', [PricingController::class, 'verify'])->name('payment.verify');
        Route::get('/payment/stripe-success', [PricingController::class, 'stripePay'])->name('payment.stripe_success');
        Route::get('/checkout/success', [PricingController::class, 'checkoutSuccess'])->name('checkout.success');
    });

    Route::get('/checkout-success', function () {
        return view('auto_posts.admin.checkout-success');
    })->name('checkout-success');

    // Billing
    Route::prefix('billing')->name('billings.')->group(function () {
        Route::get('/', [BillingController::class, 'index'])->name('index');
        Route::post('/cancel', [BillingController::class, 'cancel'])->name('cancel');
        Route::get('/plan-history', [BillingController::class, 'planHistory'])->name('plan-history');
        Route::get('/transaction-history', [BillingController::class, 'transactionHistory'])->name('transaction-history');
    });

    // Subscription Refund
    Route::post('/subscription/refund-request', [SubscriptionRefundController::class, 'store'])
        ->name('subscription.refund-request');

    // Ticket / Support
    Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
        Route::get('/', [AdminTicketController::class, 'list'])->name('list');
        Route::get('add-new', [AdminTicketController::class, 'addNew'])->name('add-new');
        Route::get('edit/{id}', [AdminTicketController::class, 'edit'])->name('edit');
        Route::match(['post', 'put'], 'store', [AdminTicketController::class, 'store'])->name('store');
        Route::get('details/{id}', [AdminTicketController::class, 'details'])->name('details');
        Route::post('delete/{id}', [AdminTicketController::class, 'delete'])->name('delete');
        Route::get('priority-change/{ticket_id}/{priority}', [AdminTicketController::class, 'priorityChange'])->name('priority-change');
        Route::post('conversations-store', [AdminTicketController::class, 'conversationsStore'])->name('conversations.store');
        Route::post('conversations-delete/{id}', [AdminTicketController::class, 'conversationsDelete'])->name('conversations.delete');
        Route::get('status-change', [AdminTicketController::class, 'statusChange'])->name('status.change');
    });

    // AI Content Generation (Admin)
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/generate', [AIContentGenerationController::class, 'generateContentPage'])->name('generate-content');
        Route::post('/generate', [AIContentGenerationController::class, 'generateContent'])->name('generate-content.submit');
        Route::get('/generated-content', [AIContentGenerationController::class, 'generateContentList'])->name('generated-content.list');
        Route::get('/generated-content/datatable', [AIContentGenerationController::class, 'datatable'])->name('generated-content.datatable');
        Route::get('/generated-content/{id}', [AIContentGenerationController::class, 'generateContentView'])->name('generated-content.view');
        Route::post('/generated-content/{id}/toggle-save', [AIContentGenerationController::class, 'toggleSave'])->name('generated-content.toggle-save');
    });

    // Roles
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])
            ->name('index');

        Route::post('/', [RoleController::class, 'store'])
            ->name('store');

        Route::get('/{id}/edit', [RoleController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [RoleController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [RoleController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{id}/permissions', [RoleController::class, 'permissions'])
            ->name('permissions');

        Route::put('/{id}/permissions', [RoleController::class, 'updatePermissions'])
            ->name('update.permissions');
    });

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'myProfile'])
            ->name('index');

        Route::post('/update', [ProfileController::class, 'changePasswordUpdate'])
            ->name('update');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/timezone', [SettingController::class, 'timezoneSettings'])->name('timezone');
        Route::post('/timezone', [SettingController::class, 'timezoneSettingsUpdate'])->name('timezone.update');
    });

    // =============================================
    // HRM - Human Resource Management
    // =============================================
    Route::prefix('hrm')->name('hrm.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [HrmDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/data', [HrmDashboardController::class, 'data'])->name('dashboard.data');

        // Departments
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [HrmDepartmentController::class, 'index'])->name('index');
            Route::post('/', [HrmDepartmentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [HrmDepartmentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HrmDepartmentController::class, 'update'])->name('update');
            Route::delete('/{id}', [HrmDepartmentController::class, 'destroy'])->name('destroy');
        });

        // Designations
        Route::prefix('designations')->name('designations.')->group(function () {
            Route::get('/', [HrmDesignationController::class, 'index'])->name('index');
            Route::post('/', [HrmDesignationController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [HrmDesignationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HrmDesignationController::class, 'update'])->name('update');
            Route::delete('/{id}', [HrmDesignationController::class, 'destroy'])->name('destroy');
        });

        // Employees
        Route::prefix('employees')->name('employees.')->group(function () {
            Route::get('/', [HrmEmployeeController::class, 'index'])->name('index');
            Route::get('/create', [HrmEmployeeController::class, 'create'])->name('create');
            Route::post('/', [HrmEmployeeController::class, 'store'])->name('store');
            Route::get('/{id}', [HrmEmployeeController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [HrmEmployeeController::class, 'edit'])->name('edit');
            Route::put('/{id}', [HrmEmployeeController::class, 'update'])->name('update');
            Route::delete('/{id}', [HrmEmployeeController::class, 'destroy'])->name('destroy');
            Route::get('/get-designations', [HrmEmployeeController::class, 'getDesignations'])->name('getDesignations');
            Route::get('/{id}/attendance/data', [HrmEmployeeController::class, 'attendanceData'])->name('attendance.data');
            Route::get('/{id}/leaves/data', [HrmEmployeeController::class, 'leavesData'])->name('leaves.data');
            Route::get('/{id}/payrolls/data', [HrmEmployeeController::class, 'payrollsData'])->name('payrolls.data');
        });

        // Attendance
        Route::prefix('attendance')->name('attendance.')->group(function () {
            Route::get('/', [HrmAttendanceController::class, 'index'])->name('index');
            Route::post('/mark', [HrmAttendanceController::class, 'markAttendance'])->name('mark');
            Route::post('/bulk-mark', [HrmAttendanceController::class, 'bulkMark'])->name('bulkMark');
        });

        // Leave Requests
        Route::prefix('leaves')->name('leaves.')->group(function () {
            Route::get('/', [HrmLeaveController::class, 'index'])->name('index');
            Route::post('/', [HrmLeaveController::class, 'store'])->name('store');
            Route::get('/{id}/approve', [HrmLeaveController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [HrmLeaveController::class, 'reject'])->name('reject');
        });

        // Payroll
        Route::prefix('payroll')->name('payroll.')->group(function () {
            Route::get('/', [HrmPayrollController::class, 'index'])->name('index');
            Route::post('/generate', [HrmPayrollController::class, 'generate'])->name('generate');
            Route::get('/{id}/mark-paid', [HrmPayrollController::class, 'markPaid'])->name('markPaid');
            Route::post('/bulk-pay', [HrmPayrollController::class, 'bulkPay'])->name('bulkPay');
        });
    });

    // Garments ERP
    Route::prefix('garments')->name('garments.')->group(function () {
        Route::get('/dashboard', [GarmentDashboardController::class, 'index'])->name('dashboard');
        Route::get('/merchandiser', [MerchandiserController::class, 'index'])->name('merchandiser.index');
        Route::get('/merchandiser/management', [MerchandiserManagementController::class, 'index'])->name('merchandiser.management');
        Route::get('/merchandiser/insights', [MerchandiserInsightsController::class, 'index'])->name('merchandiser.insights');
        Route::post('/merchandiser/handover', [MerchandiserInsightsController::class, 'handover'])->name('merchandiser.handover');
        Route::post('/merchandiser/assign', [MerchandiserManagementController::class, 'assign'])->name('merchandiser.assign');
        Route::post('/merchandiser/tasks', [MerchandiserManagementController::class, 'task'])->name('merchandiser.task');
        Route::post('/merchandiser/communications', [MerchandiserManagementController::class, 'communication'])->name('merchandiser.communication');
        Route::post('/purchase-orders/{id}/approve', [ApprovalController::class, 'purchaseOrder'])->name('purchase-orders.approve');
        Route::post('/shipment-documents/{id}/approve', [ApprovalController::class, 'shipment'])->name('shipment-documents.approve');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/buyer-portal', [BuyerPortalController::class, 'index'])->name('buyer-portal.index');
        Route::get('/buyer-portal/orders/{id}', [BuyerPortalController::class, 'show'])->name('buyer-portal.orders.show');
        Route::get('/warehouses', [WarehouseController::class, 'index'])->name('warehouses.index');
        Route::get('/materials/scanner', [MaterialScannerController::class, 'index'])->name('materials.scanner');
        Route::post('/materials/scanner/lookup', [MaterialScannerController::class, 'lookup'])->name('materials.scanner.lookup');
        Route::get('/exports/stock-movements', [GarmentExportController::class, 'stockMovements'])->name('exports.stock-movements');
        Route::post('/warehouses', [WarehouseController::class, 'store'])->name('warehouses.store');
        Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');
        Route::get('/warehouse-transfers', [WarehouseTransferController::class, 'index'])->name('warehouse-transfers.index');
        Route::post('/warehouse-transfers', [WarehouseTransferController::class, 'store'])->name('warehouse-transfers.store');
        Route::get('/shipment-documents/{id}/tracking', [ShipmentTrackingController::class, 'show'])->name('shipment-documents.tracking');
        Route::get('/shipment-documents/{id}/tracking/view', [ShipmentTrackingController::class, 'page'])->name('shipment-documents.tracking.page');
        Route::post('/shipment-documents/{id}/tracking', [ShipmentTrackingController::class, 'store'])->name('shipment-documents.tracking.store');
        Route::get('/costing-variance', [CostingVarianceController::class, 'index'])->name('costing-variance.index');
        Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
            Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
            Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
            Route::get('/{id}/print', [PurchaseOrderController::class, 'print'])->name('print');
            Route::put('/{id}', [PurchaseOrderController::class, 'update'])->name('update');
            Route::delete('/{id}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        });
        Route::get('/analytics', [ProductionAnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/data', [ProductionAnalyticsController::class, 'data'])->name('analytics.data');
        Route::get('/notifications', [GarmentNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [GarmentNotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [GarmentNotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::prefix('buyers')->name('buyers.')->group(function () {
            Route::get('/', [GarmentBuyerController::class, 'index'])->name('index');
            Route::post('/', [GarmentBuyerController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [GarmentBuyerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GarmentBuyerController::class, 'update'])->name('update');
            Route::delete('/{id}', [GarmentBuyerController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [GarmentOrderController::class, 'index'])->name('index');
            Route::post('/', [GarmentOrderController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [GarmentOrderController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GarmentOrderController::class, 'update'])->name('update');
            Route::delete('/{id}', [GarmentOrderController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('styles')->name('styles.')->group(function () {
            Route::get('/', [StyleController::class, 'index'])->name('index');
            Route::post('/', [StyleController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [StyleController::class, 'edit'])->name('edit');
            Route::put('/{id}', [StyleController::class, 'update'])->name('update');
            Route::delete('/{id}', [StyleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('costings')->name('costings.')->group(function () {
            Route::get('/', [CostingController::class, 'index'])->name('index');
            Route::post('/', [CostingController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CostingController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CostingController::class, 'update'])->name('update');
            Route::delete('/{id}', [CostingController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('tna')->name('tna.')->group(function () {
            Route::get('/', [TnaTaskController::class, 'index'])->name('index');
            Route::post('/', [TnaTaskController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [TnaTaskController::class, 'edit'])->name('edit');
            Route::put('/{id}', [TnaTaskController::class, 'update'])->name('update');
            Route::delete('/{id}', [TnaTaskController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('plans')->name('plans.')->group(function () {
            Route::get('/', [ProductionPlanController::class, 'index'])->name('index');
            Route::post('/', [ProductionPlanController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProductionPlanController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductionPlanController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductionPlanController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('materials')->name('materials.')->group(function () {
            Route::get('/', [MaterialController::class, 'index'])->name('index');
            Route::post('/', [MaterialController::class, 'store'])->name('store');
            Route::get('/{id}/label', [MaterialController::class, 'label'])->name('label');
            Route::get('/{id}/edit', [MaterialController::class, 'edit'])->name('edit');
            Route::put('/{id}', [MaterialController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('suppliers')->name('suppliers.')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('index');
            Route::post('/', [SupplierController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
            Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('grns')->name('grns.')->group(function () {
            Route::get('/', [GrnController::class, 'index'])->name('index');
            Route::post('/', [GrnController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [GrnController::class, 'edit'])->name('edit');
            Route::put('/{id}', [GrnController::class, 'update'])->name('update');
            Route::delete('/{id}', [GrnController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('issues')->name('issues.')->group(function () {
            Route::get('/', [StoreIssueController::class, 'index'])->name('index');
            Route::post('/', [StoreIssueController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [StoreIssueController::class, 'edit'])->name('edit');
            Route::put('/{id}', [StoreIssueController::class, 'update'])->name('update');
            Route::delete('/{id}', [StoreIssueController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('cutting')->name('cutting.')->group(function () {
            Route::get('/', [CuttingController::class, 'index'])->name('index');
            Route::post('/', [CuttingController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [CuttingController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CuttingController::class, 'update'])->name('update');
            Route::delete('/{id}', [CuttingController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('sewing')->name('sewing.')->group(function () {
            Route::get('/', [SewingProductionController::class, 'index'])->name('index');
            Route::post('/', [SewingProductionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [SewingProductionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [SewingProductionController::class, 'update'])->name('update');
            Route::delete('/{id}', [SewingProductionController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('efficiency')->name('efficiency.')->group(function () {
            Route::get('/', [EfficiencyController::class, 'index'])->name('index');
            Route::post('/', [EfficiencyController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [EfficiencyController::class, 'edit'])->name('edit');
            Route::put('/{id}', [EfficiencyController::class, 'update'])->name('update');
            Route::delete('/{id}', [EfficiencyController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('inline-qc')->name('inline-qc.')->group(function () {
            Route::get('/', [InlineQcController::class, 'index'])->name('index');
            Route::post('/', [InlineQcController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [InlineQcController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InlineQcController::class, 'update'])->name('update');
            Route::delete('/{id}', [InlineQcController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('final-inspections')->name('final-inspections.')->group(function () {
            Route::get('/', [FinalInspectionController::class, 'index'])->name('index');
            Route::post('/', [FinalInspectionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [FinalInspectionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FinalInspectionController::class, 'update'])->name('update');
            Route::delete('/{id}', [FinalInspectionController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('defects')->name('defects.')->group(function () {
            Route::get('/', [DefectRejectionController::class, 'index'])->name('index');
            Route::post('/', [DefectRejectionController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [DefectRejectionController::class, 'edit'])->name('edit');
            Route::put('/{id}', [DefectRejectionController::class, 'update'])->name('update');
            Route::delete('/{id}', [DefectRejectionController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('finishing')->name('finishing.')->group(function () {
            Route::get('/', [FinishingController::class, 'index'])->name('index');
            Route::post('/', [FinishingController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [FinishingController::class, 'edit'])->name('edit');
            Route::put('/{id}', [FinishingController::class, 'update'])->name('update');
            Route::delete('/{id}', [FinishingController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('packing-lists')->name('packing-lists.')->group(function () {
            Route::get('/', [PackingListController::class, 'index'])->name('index');
            Route::post('/', [PackingListController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [PackingListController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PackingListController::class, 'update'])->name('update');
            Route::delete('/{id}', [PackingListController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('shipment-documents')->name('shipment-documents.')->group(function () {
            Route::get('/', [ShipmentDocumentController::class, 'index'])->name('index');
            Route::post('/', [ShipmentDocumentController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ShipmentDocumentController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ShipmentDocumentController::class, 'update'])->name('update');
            Route::delete('/{id}', [ShipmentDocumentController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('invoices')->name('invoices.')->group(function () {
            Route::get('/', [InvoiceController::class, 'index'])->name('index');
            Route::post('/', [InvoiceController::class, 'store'])->name('store');
            Route::post('/{id}/payments', [InvoiceController::class, 'payment'])->name('payments.store');
            Route::post('/{id}/checkout', [InvoiceController::class, 'checkout'])->name('payments.checkout');
            Route::match(['get', 'post'], '/{invoice}/payments/{payment}/callback', [InvoiceController::class, 'paymentCallback'])->name('payment-callback');
            Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
            Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('print');
        });
        Route::get('/ap-ar', [ApArController::class, 'index'])->name('ap-ar.index');
        Route::get('/payment-gateways', [GarmentPaymentGatewayController::class, 'index'])->name('payment-gateways.index');
        Route::post('/payment-gateways', [GarmentPaymentGatewayController::class, 'store'])->name('payment-gateways.store');
        Route::delete('/payment-gateways/{id}', [GarmentPaymentGatewayController::class, 'destroy'])->name('payment-gateways.destroy');
        Route::prefix('profit-loss')->name('profit-loss.')->group(function () {
            Route::get('/', [OrderProfitLossController::class, 'index'])->name('index');
            Route::post('/', [OrderProfitLossController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [OrderProfitLossController::class, 'edit'])->name('edit');
            Route::put('/{id}', [OrderProfitLossController::class, 'update'])->name('update');
            Route::delete('/{id}', [OrderProfitLossController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('accounting')->name('accounting.')->group(function () {
            Route::get('/', [AccountingEntryController::class, 'index'])->name('index');
            Route::post('/', [AccountingEntryController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [AccountingEntryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [AccountingEntryController::class, 'update'])->name('update');
            Route::delete('/{id}', [AccountingEntryController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('incentives')->name('incentives.')->group(function () {
            Route::get('/', [IncentiveController::class, 'index'])->name('index');
            Route::post('/', [IncentiveController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [IncentiveController::class, 'edit'])->name('edit');
            Route::put('/{id}', [IncentiveController::class, 'update'])->name('update');
            Route::delete('/{id}', [IncentiveController::class, 'destroy'])->name('destroy');
        });
        Route::prefix('production-attendance')->name('production-attendance.')->group(function () {
            Route::get('/', [ProductionAttendanceController::class, 'index'])->name('index');
            Route::post('/', [ProductionAttendanceController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [ProductionAttendanceController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ProductionAttendanceController::class, 'update'])->name('update');
            Route::delete('/{id}', [ProductionAttendanceController::class, 'destroy'])->name('destroy');
        });
    });
});
