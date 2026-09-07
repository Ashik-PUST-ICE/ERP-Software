<?php

// use App\Http\Controllers\AutoPost\SuperAdmin\CouponController;
use App\Http\Controllers\AutoPost\SuperAdmin\CurrencyController;
use App\Http\Controllers\AutoPost\SuperAdmin\DashboardController;
use App\Http\Controllers\AutoPost\SuperAdmin\GatewayController;
use App\Http\Controllers\AutoPost\SuperAdmin\LanguageController;
// use App\Http\Controllers\AutoPost\SuperAdmin\LandingBlogController;
// use App\Http\Controllers\AutoPost\SuperAdmin\MenuController;
use App\Http\Controllers\AutoPost\SuperAdmin\PackageController;
use App\Http\Controllers\AutoPost\SuperAdmin\PageController;
use App\Http\Controllers\AutoPost\SuperAdmin\ProfileController;
use App\Http\Controllers\AutoPost\SuperAdmin\RolePermissionController;
use App\Http\Controllers\AutoPost\SuperAdmin\AISettingController;
use App\Http\Controllers\AutoPost\SuperAdmin\SettingController;
use App\Http\Controllers\AutoPost\SuperAdmin\SubscriptionController;
use App\Http\Controllers\AutoPost\SuperAdmin\SubscriptionRefundController;
use App\Http\Controllers\AutoPost\SuperAdmin\TicketController;
use App\Http\Controllers\AutoPost\SuperAdmin\UserController;
use App\Http\Controllers\AutoPost\SuperAdmin\VersionController;
use App\Http\Controllers\AutoPost\SuperAdmin\VersionUpdateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('script-' . now()->format('Ymd'), [VersionUpdateController::class, 'pathFile'])->name('script-file');
Route::post('script-file', [VersionUpdateController::class, 'downloadPathFile'])->name('load-script-file');
Route::post('store-script-file', [VersionUpdateController::class, 'storePathFile'])->name('store-script-file');

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::group(['middleware' => []], function () {
        Route::get('application-settings', [SettingController::class, 'applicationSetting'])->middleware('can:Manage Application Setting')->name('application-settings');
        Route::get('configuration-settings', [SettingController::class, 'configurationSetting'])->middleware('can:Manage Application Setting')->name('configuration-settings');
        Route::get('configuration-settings/configure', [SettingController::class, 'configurationSettingConfigure'])->middleware('can:Manage Application Setting')->name('configuration-settings.configure');
        Route::get('configuration-settings/help', [SettingController::class, 'configurationSettingHelp'])->middleware('can:Manage Application Setting')->name('configuration-settings.help');
        Route::post('application-settings-update', [SettingController::class, 'applicationSettingUpdate'])->middleware('can:Manage Application Setting')->name('application-settings.update')->middleware('isDemo');
        Route::post('configuration-settings-update', [SettingController::class, 'configurationSettingUpdate'])->middleware('can:Manage Application Setting')->name('configuration-settings.update')->middleware('isDemo');
        Route::post('application-env-update', [SettingController::class, 'saveSetting'])->middleware('can:Manage Application Setting')->name('settings_env.update');
        Route::get('logo-settings', [SettingController::class, 'logoSettings'])->middleware('can:Manage Application Setting')->name('logo-settings');
        Route::get('color-settings', [SettingController::class, 'colorSettings'])->middleware('can:Manage Application Setting')->name('color-settings');

        Route::group(['prefix' => 'currency', 'as' => 'currencies.', 'middleware' => ['can:Manage Application Setting']], function () {
            Route::get('', [CurrencyController::class, 'index'])->name('index');
            Route::post('currency', [CurrencyController::class, 'store'])->name('store');
            Route::get('edit/{id}', [CurrencyController::class, 'edit'])->name('edit');
            Route::patch('update/{id}', [CurrencyController::class, 'update'])->name('update');
            Route::delete('delete/{id}', [CurrencyController::class, 'delete'])->name('delete');
        });

        Route::get('storage-settings', [SettingController::class, 'storageSetting'])->middleware('can:Manage Application Setting')->name('storage.index');
        Route::post('storage-settings', [SettingController::class, 'storageSettingsUpdate'])->middleware('can:Manage Application Setting')->name('storage.update');
        Route::get('google-recaptcha-settings', [SettingController::class, 'googleRecaptchaSetting'])->middleware('can:Manage Application Setting')->name('google-recaptcha');
        Route::get('google-analytics-settings', [SettingController::class, 'googleAnalyticsSetting'])->middleware('can:Manage Application Setting')->name('google.analytics');
    });

    Route::get('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-test', [SettingController::class, 'mailTest'])->name('mail.test');

    Route::get('sms-configuration', [SettingController::class, 'smsConfiguration'])->name('sms-configuration');
    Route::post('sms-configuration', [SettingController::class, 'smsConfigurationStore'])->name('sms-configuration');
    Route::post('sms-test', [SettingController::class, 'smsTest'])->name('sms.test');


    //Start:: Maintenance Mode
    Route::get('maintenance-mode-changes', [SettingController::class, 'maintenanceMode'])->name('maintenance');
    Route::post('maintenance-mode-changes', [SettingController::class, 'maintenanceModeChange'])->name('maintenance.change')->middleware('isDemo');
    //End:: Maintenance Mode

Route::get('cache-settings', [SettingController::class, 'cacheSettings'])->name('cache-settings');
        Route::get('cache-update/{id}', [SettingController::class, 'cacheUpdate'])->name('cache-update');
        Route::get('ai-settings', [AISettingController::class, 'openAISetting'])->middleware('can:Manage Application Setting')->name('ai-settings');
        Route::post('ai-settings', [AISettingController::class, 'updateAISettings'])->middleware('can:Manage Application Setting')->name('ai-settings.update')->middleware('isDemo');
    Route::get('storage-link', [SettingController::class, 'storageLink'])->name('storage.link');
    Route::get('security-settings', [SettingController::class, 'securitySettings'])->name('security.settings');

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.', 'middleware' => ['can:Manage Application Setting']], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::get('syncs', [GatewayController::class, 'syncs'])->name('syncs');
    });

    //Features Settings
    Route::get('cookie-settings', [SettingController::class, 'cookieSetting'])->name('cookie-settings');
    Route::post('cookie-settings-update', [SettingController::class, 'cookieSettingUpdated'])->name('cookie.settings.update');
    Route::get('live-chat-settings', [SettingController::class, 'liveChatSettings'])->name('live.chat.settings');

    // Frontend / Landing Page Configuration
    Route::group(['prefix' => 'frontend', 'as' => 'frontend.'], function () {
        Route::get('landing-page', [SettingController::class, 'landingPageSettings'])
            ->name('landing-page');
        Route::get('landing-page/hero', [SettingController::class, 'heroSectionSettings'])
            ->name('landing-page.hero');
        Route::post('landing-page', [SettingController::class, 'landingPageSettingsUpdate'])
            ->name('landing-page.update');
    });

    // Landing Settings (without addon requirement)
    Route::get('landing-settings', [SettingController::class, 'frontendSetting'])
        ->middleware('can:Manage Application Setting')
        ->name('landing-settings');

    // Blogs Settings (commented out)
    // Route::group(['prefix' => 'blogs', 'as' => 'blogs.'], function () { ... });

    // Pages Settings (commented out)
    // Route::group(['prefix' => 'page', 'as' => 'page.'], function () { ... });

    // Menu Settings (commented out)
    // Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () { ... });

    //common setting update
    Route::post('common-settings-update', [SettingController::class, 'commonSettingUpdate'])->name('common.settings.update')->middleware('isDemo');

    Route::group(['prefix' => 'language', 'as' => 'languages.', 'middleware' => ['can:Manage Application Setting']], function () {
        Route::get('/', [LanguageController::class, 'index'])->name('index');
        Route::post('store', [LanguageController::class, 'store'])->name('store');
        Route::get('edit/{id}/{iso_code?}', [LanguageController::class, 'edit'])->name('edit');
        Route::post('update/{id}', [LanguageController::class, 'update'])->name('update');
        Route::get('translate/{id}', [LanguageController::class, 'translateLanguage'])->name('translate');
        Route::post('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
        Route::delete('delete/{id}', [LanguageController::class, 'delete'])->name('delete');
        Route::post('update-language/{id}', [LanguageController::class, 'updateLanguage'])->name('update-language');
        Route::get('translate/{id}/{iso_code?}', [LanguageController::class, 'translateLanguage'])->name('translate');
        Route::get('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
        Route::post('import', [LanguageController::class, 'import'])->name('import')->middleware('isDemo');
        Route::get('download/{id}', [LanguageController::class, 'download'])->name('download');
        Route::post('upload/{id}', [LanguageController::class, 'upload'])->name('upload');
    });
});

Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
    Route::get('/', [ProfileController::class, 'myProfile'])->name('index');
    Route::get('change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    Route::post('change-password', [ProfileController::class, 'update'])->name('change-password.update')->middleware('isDemo');
    Route::post('update', [ProfileController::class, 'update'])->name('update')->middleware('isDemo');
});

// version update
Route::get('version-update', [VersionUpdateController::class, 'versionFileUpdate'])->name('version-update');
Route::post('version-update', [VersionUpdateController::class, 'versionFileUpdateStore'])->name('version-update-store');
Route::get('version-update-execute', [VersionUpdateController::class, 'versionUpdateExecute'])->name('version-update-execute');
Route::get('version-delete', [VersionUpdateController::class, 'versionFileUpdateDelete'])->name('version-delete');

// Roles (Super Admin)
Route::group(['prefix' => 'roles', 'as' => 'roles.', 'middleware' => ['can:Manage Moderator']], function () {
    Route::get('/', [RolePermissionController::class, 'index'])->name('index');
    Route::post('store', [RolePermissionController::class, 'store'])->name('store');
    Route::get('edit/{id}', [RolePermissionController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [RolePermissionController::class, 'update'])->name('update');
    Route::post('destroy/{id}', [RolePermissionController::class, 'destroy'])->name('destroy');
    Route::get('permissions/{id}', [RolePermissionController::class, 'permissions'])->name('permissions');
    Route::post('permissions/{id}', [RolePermissionController::class, 'updatePermissions'])->name('update.permissions');
});

// Users (Super Admin)
Route::group(['prefix' => 'users-list', 'as' => 'users.', 'middleware' => ['super-admin']], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('data', [UserController::class, 'data'])->name('data');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [UserController::class, 'update'])->name('update');
    Route::post('destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
});

// Packages
Route::group(['prefix' => 'packages', 'as' => 'packages.'], function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
    Route::get('/data', [PackageController::class, 'data'])->name('data');
    Route::get('/create', [PackageController::class, 'create'])->name('create');
    Route::post('/', [PackageController::class, 'store'])->name('store');
    Route::get('/{package}/edit', [PackageController::class, 'edit'])->name('edit');
    Route::patch('/{package}', [PackageController::class, 'update'])->name('update');
    Route::post('/{package}/destroy', [PackageController::class, 'destroy'])->name('destroy');
    Route::get('/user', [PackageController::class, 'userPackages'])->name('user');
    Route::post('/assign', [PackageController::class, 'assignPackage'])->name('assign');
    Route::get('/edit_user_package/{id}', [PackageController::class, 'editUserPackage'])->name('edit_user_package');
    Route::post('/update_user_package/{id}', [PackageController::class, 'updateUserPackage'])->name('update_user_package');
    Route::post('/revoke/{id}', [PackageController::class, 'revokePackage'])->name('revoke');
});

// Subscription orders
Route::group(['prefix' => 'subscription', 'as' => 'subscriptions.'], function () {
    Route::get('orders', [SubscriptionController::class, 'orders'])->name('orders');
    Route::get('orders/payment/status', [SubscriptionController::class, 'ordersStatus'])->name('orders.payment.status');
    Route::get('orders/get/info', [SubscriptionController::class, 'orderGetInfo'])->name('orders.get.info');
    Route::post('order/payment/status/change', [SubscriptionController::class, 'orderPaymentStatusChange'])->name('order.payment.status.change');
});

    // Coupons (commented out)
    // Route::group(['prefix' => 'coupons', 'as' => 'coupons.'], function () { ... });

// Tickets (Super Admin)
Route::group(['prefix' => 'ticket', 'as' => 'ticket.'], function () {
    Route::get('/', [TicketController::class, 'list'])->name('list');
    Route::get('add-new', [TicketController::class, 'addNew'])->name('add-new');
    Route::get('edit/{id}', [TicketController::class, 'edit'])->name('edit');
    Route::post('store', [TicketController::class, 'store'])->name('store');
    Route::get('details/{id}', [TicketController::class, 'details'])->name('details');
    Route::post('delete/{id}', [TicketController::class, 'delete'])->name('delete');
    Route::get('assign-member', [TicketController::class, 'assignMember'])->name('assign-member');
    Route::get('priority-change/{ticket_id}/{priority}', [TicketController::class, 'priorityChange'])->name('priority-change');
    Route::post('conversations-store', [TicketController::class, 'conversationsStore'])->name('conversations.store');
    Route::post('conversations-delete/{id}', [TicketController::class, 'conversationsDelete'])->name('conversations.delete');
    Route::get('status-change', [TicketController::class, 'statusChange'])->name('status.change');
});

// Subscription Refunds (Super Admin)
Route::group(['prefix' => 'subscription-refund', 'as' => 'subscription-refund.'], function () {
    Route::get('/', [SubscriptionRefundController::class, 'list'])->name('list');
    Route::get('edit-status-model/{id}', [SubscriptionRefundController::class, 'refundStatusChangeModel'])->name('edit-status-model');
    Route::post('subscription-model-status-change/{id}', [SubscriptionRefundController::class, 'refundStatusChange'])->name('subscription-model-status-change');
});