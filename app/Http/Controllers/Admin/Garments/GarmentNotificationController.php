<?php

namespace App\Http\Controllers\Admin\Garments;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Http\Services\NotificationService;

class GarmentNotificationController extends Controller
{
    public function index()
    {
        (new NotificationService())->generateGarmentAlerts();

        return view('admin.garments.notifications.index', [
            'title' => __('Garments Notifications'),
            'notifications' => Notification::where(function ($query) {
                $query->whereNull('user_id')->orWhere('user_id', auth()->id());
            })->latest()->paginate(20),
            'activeGarments' => 'active',
            'activeGarmentNotifications' => 'active',
            'showGarmentsMenu' => 'show',
        ]);
    }

    public function markRead($id)
    {
        Notification::whereKey($id)->where(function ($query) {
            $query->whereNull('user_id')->orWhere('user_id', auth()->id());
        })->update(['view_status' => 1]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where(function ($query) {
            $query->whereNull('user_id')->orWhere('user_id', auth()->id());
        })->where('view_status', 0)->update(['view_status' => 1]);

        return back()->with('success', __('All notifications marked as read'));
    }
}
