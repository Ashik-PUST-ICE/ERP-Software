<?php

use App\Http\Controllers\AutoPost\Admin\BillingController;
use App\Http\Controllers\AutoPost\Admin\CategoryController;
use App\Http\Controllers\AutoPost\Admin\SubscriptionRefundController;
use App\Http\Controllers\AutoPost\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\AutoPost\Admin\CurrencyController;
use App\Http\Controllers\AutoPost\Admin\GalleryController;
use App\Http\Controllers\AutoPost\Admin\GatewayController;
use App\Http\Controllers\AutoPost\Admin\PricingController;
use App\Http\Controllers\AutoPost\Admin\SocialMediaController;
use App\Http\Controllers\AutoPost\Admin\SocialMediaConfigController;
use App\Http\Controllers\AutoPost\Admin\ScheduledPostController;
use App\Http\Controllers\AutoPost\Admin\PublishPostController;
use App\Http\Controllers\AutoPost\Admin\VideoGalleryController;
use App\Http\Controllers\AutoPost\Admin\TemplateController;
use App\Http\Controllers\AutoPost\Admin\HashtagController;
use App\Http\Controllers\AutoPost\Admin\RolePermissionController as RoleController;
use App\Http\Controllers\AutoPost\Admin\ProfileController;
use App\Http\Controllers\AutoPost\Admin\ApplicationSettings\GatewayController as ApplicationGatewayController;
use App\Http\Controllers\AutoPost\Admin\ApplicationSettings\CurrencyController as ApplicationCurrencyController;
use App\Http\Controllers\AutoPost\Admin\ApplicationSettings\LanguageController as ApplicationLanguageController;
use App\Http\Controllers\AutoPost\Admin\SettingController;
use App\Http\Controllers\AutoPost\Admin\CampaignController;
use App\Http\Controllers\AutoPost\Admin\UserController;
use App\Http\Controllers\AutoPost\Admin\AnalyticsController;
use App\Http\Controllers\AutoPost\Admin\AIContentGenerationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "admin" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth', 'admin']], function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\AutoPost\Admin\DashboardController::class, 'index'])
        ->name('dashboard');
    Route::get('/dashboard/latest-posts', [App\Http\Controllers\AutoPost\Admin\DashboardController::class, 'latestPostsDatatable'])
        ->name('dashboard.latest-posts');

    // Analytics
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])
            ->name('index');

        // Platform-specific Analytics
        Route::get('/{platform}', [AnalyticsController::class, 'platformAnalytics'])
            ->name('platform');
    });

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

    // Gallery
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', [GalleryController::class, 'index'])
            ->name('index');

        Route::post('/', [GalleryController::class, 'store'])
            ->name('store');

        Route::put('/{id}', [GalleryController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [GalleryController::class, 'destroy'])
            ->name('destroy');
    });

    // Video Gallery
    Route::prefix('video-gallery')->name('video-gallery.')->group(function () {
        Route::get('/', [VideoGalleryController::class, 'index'])
            ->name('index');

        Route::post('/', [VideoGalleryController::class, 'store'])
            ->name('store');

        Route::put('/{id}', [VideoGalleryController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [VideoGalleryController::class, 'destroy'])
            ->name('destroy');
    });

    // Templates
    Route::prefix('templates')->name('template.')->group(function () {
        Route::get('/', [TemplateController::class, 'index'])->name('index');
        Route::get('/list', [TemplateController::class, 'list'])->name('list');
        Route::get('/datatable', [TemplateController::class, 'datatable'])->name('datatable');
        Route::get('/create', [TemplateController::class, 'create'])->name('create');
        Route::post('/', [TemplateController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [TemplateController::class, 'edit'])->name('edit');
        Route::put('/{id}', [TemplateController::class, 'update'])->name('update');
        Route::delete('/{id}', [TemplateController::class, 'destroy'])->name('destroy');
    });

    // Campaigns
    Route::prefix('campaigns')->name('campaign.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/list', [CampaignController::class, 'list'])->name('list');
        Route::get('/datatable', [CampaignController::class, 'datatable'])->name('datatable');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{id}', [CampaignController::class, 'update'])->name('update');
        Route::post('/{id}/publish', [CampaignController::class, 'publish'])->name('publish');
        Route::delete('/{id}', [CampaignController::class, 'destroy'])->name('destroy');
    });

    // Hashtags API

    // AI Content Generation (Admin)
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/generate', [AIContentGenerationController::class, 'generateContentPage'])->name('generate-content');
        Route::post('/generate', [AIContentGenerationController::class, 'generateContent'])->name('generate-content.submit');
        Route::get('/generated-content', [AIContentGenerationController::class, 'generateContentList'])->name('generated-content.list');
        Route::get('/generated-content/datatable', [AIContentGenerationController::class, 'datatable'])->name('generated-content.datatable');
        Route::get('/generated-content/{id}', [AIContentGenerationController::class, 'generateContentView'])->name('generated-content.view');
        Route::post('/generated-content/{id}/toggle-save', [AIContentGenerationController::class, 'toggleSave'])->name('generated-content.toggle-save');
    });

    // Categories
    Route::prefix('categories')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])
            ->name('index');

        Route::get('/datatable', [CategoryController::class, 'datatable'])
            ->name('datatable');

        Route::get('/create', [CategoryController::class, 'create'])
            ->name('create');

        Route::post('/', [CategoryController::class, 'store'])
            ->name('store');

        Route::get('/{id}', [CategoryController::class, 'show'])
            ->name('show');

        Route::get('/{id}/edit', [CategoryController::class, 'edit'])
            ->name('edit');

        Route::put('/{id}', [CategoryController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [CategoryController::class, 'destroy'])
            ->name('destroy');

    });

    // Hashtags
    Route::prefix('hashtags')->name('hashtag.')->group(function () {
        Route::get('/', [HashtagController::class, 'index'])->name('index');
        Route::post('/', [HashtagController::class, 'store'])->name('store');
    });


    // Social Media Management
    Route::prefix('social/account')->name('social.account.')->group(function () {
        Route::get('/list', [SocialMediaController::class, 'index'])
            ->name('index');

        Route::get('/create', [SocialMediaController::class, 'create'])
            ->name('create');

        Route::post('/', [SocialMediaController::class, 'store'])
            ->name('store');



        Route::get('/{account}/edit', [SocialMediaController::class, 'edit'])
            ->name('edit');

        Route::put('/{account}', [SocialMediaController::class, 'update'])
            ->name('update');

        Route::get('/{account}/edit-data', [SocialMediaController::class, 'editData'])
            ->name('edit-data');

        Route::delete('/{account}', [SocialMediaController::class, 'destroy'])
            ->name('destroy');

        // Facebook OAuth routes
        Route::get('/facebook/redirect', [SocialMediaController::class, 'redirectToFacebook'])
            ->name('facebook.redirect');

        Route::get('/list/facebook/callback', [SocialMediaController::class, 'handleFacebookCallback'])
            ->name('facebook.callback');

        // Instagram OAuth routes (uses Facebook Login)
        Route::get('/instagram/redirect', [SocialMediaController::class, 'redirectToInstagram'])
            ->name('instagram.redirect');

        Route::get('/list/instagram/callback', [SocialMediaController::class, 'handleInstagramCallback'])
            ->name('instagram.callback');

        // Threads OAuth routes (uses Facebook Login)
        Route::get('/threads/redirect', [SocialMediaController::class, 'redirectToThreads'])
            ->name('threads.redirect');

        Route::get('/list/threads/callback', [SocialMediaController::class, 'handleThreadsCallback'])
            ->name('threads.callback');

        // YouTube OAuth routes (Google)
        Route::get('/youtube/redirect', [SocialMediaController::class, 'redirectToYouTube'])
            ->name('youtube.redirect');
        Route::get('/list/youtube/callback', [SocialMediaController::class, 'handleYouTubeCallback'])
            ->name('youtube.callback');

        // LinkedIn OAuth routes
        Route::get('/linkedin/redirect', [SocialMediaController::class, 'redirectToLinkedin'])
            ->name('linkedin.redirect');
        Route::get('/list/linkedin/callback', [SocialMediaController::class, 'handleLinkedinCallback'])
            ->name('linkedin.callback');

        // Twitter OAuth routes
        Route::get('/twitter/redirect', [SocialMediaController::class, 'redirectToTwitter'])
            ->name('twitter.redirect');
        Route::get('/list/twitter/callback', [SocialMediaController::class, 'handleTwitterCallback'])
            ->name('twitter.callback');

        // TikTok OAuth routes
        Route::get('/tiktok/redirect', [SocialMediaController::class, 'redirectToTiktok'])
            ->name('tiktok.redirect');
        Route::get('/list/tiktok/callback', [SocialMediaController::class, 'handleTiktokCallback'])
            ->name('tiktok.callback');

        // Debug route to see TikTok Token on screen
        // Route::get('/callback', function (\Illuminate\Http\Request $request) {
        //     $code = $request->query('code');
        //     if (!$code) return "No code found in URL. Make sure you redirected from TikTok.";

        //     $clientKey = '7601734756837033996'; // From your .env
        //     $clientSecret = 'sbawsbs00j256hsnho'; // From your .env (Check if these are swapped)

        //     $response = \Illuminate\Support\Facades\Http::asForm()->post('https://open.tiktokapis.com/v2/oauth/token/', [
        //         'client_key' => $clientKey,
        //         'client_secret' => $clientSecret,
        //         'code' => $code,
        //         'grant_type' => 'authorization_code',
        //         'redirect_uri' => url('/autopost/admin/social-media/callback'),
        //     ]);

        //     return response()->json([
        //         'MESSAGE' => 'COPY THE ACCESS_TOKEN BELOW',
        //         'RESPONSE' => $response->json()
        //     ]);
        // })->name('callback');
    });

    // Custom route for creating post to match user requirement
    Route::get('/post/create', [ScheduledPostController::class, 'create'])
        ->name('all-posts.create');

    // Calendar
    Route::prefix('calendar')->name('calendar.')->group(function () {
        Route::get('/', [ScheduledPostController::class, 'calendar'])
            ->name('index');

        Route::get('/events', [ScheduledPostController::class, 'calendarEvents'])
            ->name('events');

        Route::post('/posts/{post}/update-date', [ScheduledPostController::class, 'updateDate'])
            ->name('update-date');

        Route::post('/posts/{post}/publish-now', [ScheduledPostController::class, 'publishNow'])
            ->name('publish-now');
    });

    // Direct route for calendar (backward compatibility)
    Route::get('/calendar', [ScheduledPostController::class, 'calendar'])
        ->name('calendar');

    // Scheduled Posts Management
    Route::prefix('all-posts')->name('all-posts.')->group(function () {
        Route::get('/datatable', [ScheduledPostController::class, 'datatable'])
            ->name('datatable');

        Route::get('/', [ScheduledPostController::class, 'index'])
            ->name('index');



        Route::post('/', [ScheduledPostController::class, 'store'])
            ->name('store');

        Route::get('/{post}/edit', [ScheduledPostController::class, 'edit'])
            ->name('edit');

        Route::put('/{post}', [ScheduledPostController::class, 'update'])
            ->name('update');

        Route::delete('/{post}', [ScheduledPostController::class, 'destroy'])
            ->name('destroy');

        Route::post('/{post}/cancel', [ScheduledPostController::class, 'cancel'])
            ->name('cancel');

        Route::post('/{post}/retry', [ScheduledPostController::class, 'retry'])
            ->name('retry');

        Route::post('/process-due', [ScheduledPostController::class, 'processDue'])
            ->name('process-due');

        Route::get('/statistics', [ScheduledPostController::class, 'statistics'])
            ->name('statistics');
    });

    // Publish Posts Management
    Route::prefix('publish-posts')->name('publish-posts.')->group(function () {
        Route::get('/', [PublishPostController::class, 'index'])
            ->name('index');

        Route::post('/publish', [PublishPostController::class, 'publish'])
            ->name('publish');
    });

//    // Application Settings
//    Route::prefix('application-settings')->name('application-settings.')->group(function () {
//        // Gateway Settings
//        Route::prefix('gateway')->name('gateway.')->group(function () {
//            Route::get('/', [ApplicationGatewayController::class, 'index'])
//                ->name('index');
//
//            Route::get('/create', [ApplicationGatewayController::class, 'create'])
//                ->name('create');
//
//            Route::post('/', [ApplicationGatewayController::class, 'store'])
//                ->name('store');
//
//            Route::get('/{gateway}/edit', [ApplicationGatewayController::class, 'edit'])
//                ->name('edit');
//
//            Route::put('/{gateway}', [ApplicationGatewayController::class, 'update'])
//                ->name('update');
//
//            Route::delete('/{gateway}', [ApplicationGatewayController::class, 'destroy'])
//                ->name('destroy');
//
//            Route::post('/{gateway}/toggle-status', [ApplicationGatewayController::class, 'toggleStatus'])
//                ->name('toggle-status');
//
//            Route::get('/get-info', [ApplicationGatewayController::class, 'getInfo'])
//                ->name('get-info');
//
//            Route::get('/syncs', [ApplicationGatewayController::class, 'syncs'])
//                ->name('syncs');
//        });
//
//        // Currency Settings
//        Route::prefix('currency')->name('currency.')->group(function () {
//            Route::get('/', [ApplicationCurrencyController::class, 'index'])
//                ->name('index');
//
//            Route::get('/data', [ApplicationCurrencyController::class, 'data'])
//                ->name('data');
//
//            Route::get('/create', [ApplicationCurrencyController::class, 'create'])
//                ->name('create');
//
//            Route::post('/', [ApplicationCurrencyController::class, 'store'])
//                ->name('store');
//
//            Route::get('/edit/{id}', [ApplicationCurrencyController::class, 'edit'])
//                ->name('edit');
//
//            Route::patch('/update/{id}', [ApplicationCurrencyController::class, 'update'])
//                ->name('update');
//
//            Route::post('/delete/{id}', [ApplicationCurrencyController::class, 'destroy'])
//                ->name('delete');
//
//            Route::post('/{currency}/toggle-status', [ApplicationCurrencyController::class, 'toggleStatus'])
//                ->name('toggle-status');
//        });
//
//        // Language Settings
//        Route::prefix('languages')->name('languages.')->group(function () {
//            Route::get('/', [ApplicationLanguageController::class, 'index'])
//                ->name('index');
//
//            Route::post('/', [ApplicationLanguageController::class, 'store'])
//                ->name('store');
//
//            Route::get('/{language}/edit', [ApplicationLanguageController::class, 'edit'])
//                ->name('edit');
//
//            Route::put('/{language}', [ApplicationLanguageController::class, 'update'])
//                ->name('update');
//
//            Route::delete('/{language}', [ApplicationLanguageController::class, 'delete'])
//                ->name('delete');
//
//            Route::get('/{language}/translate', [ApplicationLanguageController::class, 'translateLanguage'])
//                ->name('translate');
//
//            Route::post('/{language}/update-language', [ApplicationLanguageController::class, 'updateLanguage'])
//                ->name('update-language');
//
//            Route::post('/{language}/import', [ApplicationLanguageController::class, 'import'])
//                ->name('import');
//
//            Route::post('/{language}/update-translate', [ApplicationLanguageController::class, 'updateTranslate'])
//                ->name('update-translate');
//
//            Route::get('/{language}/download', [ApplicationLanguageController::class, 'download'])
//                ->name('download');
//
//            Route::post('/{language}/upload', [ApplicationLanguageController::class, 'upload'])
//                ->name('upload');
//        });
//
//        // Color Settings
//        Route::prefix('color-settings')->name('color-settings.')->group(function () {
//            Route::get('/', [SettingController::class, 'colorSettings'])
//                ->name('index');
//        });
//
//        Route::post('/application-settings-update', [SettingController::class, 'applicationSettingUpdate'])
//            ->name('update');
//    });

    // Users
//    Route::prefix('users')->name('users.')->group(function () {
//        Route::get('/', [UserController::class, 'index'])
//            ->name('index');
//        Route::get('data', [UserController::class, 'data'])
//            ->name('data');
//
//        Route::post('/', [UserController::class, 'store'])
//            ->name('store');
//
//        Route::get('/{id}/edit', [UserController::class, 'edit'])
//            ->name('edit');
//
//        Route::put('/{id}', [UserController::class, 'update'])
//            ->name('update');
//    });

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

    // Settings (Timezone for scheduled posts & campaigns)
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/timezone', [SettingController::class, 'timezoneSettings'])->name('timezone');
        Route::post('/timezone', [SettingController::class, 'timezoneSettingsUpdate'])->name('timezone.update');
    });

    // Platforms (Social Media Configurations)
    Route::prefix('platform')->name('platform.')->group(function () {
        Route::get('/list', [SocialMediaConfigController::class, 'index'])
            ->name('index');

        Route::get('/create', [SocialMediaConfigController::class, 'create'])
            ->name('create');

        Route::post('/', [SocialMediaConfigController::class, 'store'])
            ->name('store');

        Route::get('/{config}', [SocialMediaConfigController::class, 'show'])
            ->name('show');

        Route::get('/{config}/edit', [SocialMediaConfigController::class, 'edit'])
            ->name('edit');

        Route::get('/{config}/edit-form', [SocialMediaConfigController::class, 'editForm'])
            ->name('edit-form');

        Route::put('/{config}', [SocialMediaConfigController::class, 'update'])
            ->name('update');

        Route::delete('/{config}', [SocialMediaConfigController::class, 'destroy'])
            ->name('destroy');

        Route::post('/{config}/toggle-status', [SocialMediaConfigController::class, 'toggleStatus'])
            ->name('toggle-status');
    });
});
