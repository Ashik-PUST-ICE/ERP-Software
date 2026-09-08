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
    });
});
