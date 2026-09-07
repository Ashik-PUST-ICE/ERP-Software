<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\SubscriptionRefundService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SubscriptionRefundController extends Controller
{
    use ResponseTrait;

    public $subscriptionRefundServices;

    public function __construct()
    {
        $this->subscriptionRefundServices = new SubscriptionRefundService();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->subscriptionRefundServices->refundRequestList();
        }

        $data['pageTitle'] = __('Refund Request');
        $data['showAutoPostApp'] = 'show';
        $data['activeAutoPostRefundRequest'] = 'active';
        $data['breadcrumb'] = __('User Management') . ' / ' . __('Refund Request');

        return view('auto_posts.super_admin.refund-request.index', $data);
    }

    public function refundStatusChangeModel($id)
    {
        $data['statusChange'] = $this->subscriptionRefundServices->getById($id);
        return view('auto_posts.super_admin.refund-request.refund-status-change', $data);
    }

    public function refundStatusChange(Request $request, $id)
    {
        return $this->subscriptionRefundServices->getStatusChange($request, $id);
    }
}