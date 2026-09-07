<?php

use App\Http\Controllers\Frontend\AlumniController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\Frontend\MembershipController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\NewsSubscriptionLetterController;
use App\Http\Controllers\Frontend\NoticeController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\StoryController;
use App\Http\Controllers\Frontend\TicketVerifyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

Route::get('/', function () {
    return app(HomeController::class)->index(request());
})->name('index');

// Blog details
Route::get('/blog/{blog}', [HomeController::class, 'blogShow'])->name('blog.show');

// Notification routes
Route::get('notification/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notification.mark-all-as-read');
Route::get('notification/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notification.mark-as-read');
