<?php

namespace App\Http\Controllers\AutoPost\Admin;

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

    public function store(Request $request)
    {
        return $this->subscriptionRefundServices->refundSubscriptionRequest($request);
    }

}
