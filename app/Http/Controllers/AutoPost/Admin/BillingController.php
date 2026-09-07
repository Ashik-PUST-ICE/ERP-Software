<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\Admin\BillingService;
use App\Http\Services\PackageService;
use App\Http\Services\SubscriptionService;
use App\Models\Gateway;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    use ResponseTrait;

    protected BillingService $billingService;
    protected SubscriptionService $subscriptionService;
    protected PackageService $packageService;

    public function __construct()
    {
        $this->billingService = new BillingService();
        $this->subscriptionService = new SubscriptionService();
        $this->packageService = new PackageService();
    }

    public function index()
    {
        $userId = auth()->id();
        $currentPackage = $this->subscriptionService->getCurrentPlan($userId);
        $packages = $this->packageService->getActiveAll();
        $gateways = Gateway::where('status', STATUS_ACTIVE)->get();

        $data = [
            'activeBilling' => 'active',
            'showBillingMenu' => 'true',
            'title' => __('Billing'),
            'currentPackage' => $currentPackage,
            'packages' => $packages,
            'gateways' => $gateways,
            'defaultCurrencySymbol' => getCurrencySymbol(),
        ];

        return view('auto_posts.admin.billing.index', $data);
    }

    public function cancel(Request $request)
    {
        $response = $this->billingService->cancel();
        $data = $response->getData(true);

        if (!empty($data['status'])) {
            return redirect()->route('admin.billings.index')->with('success', $data['message']);
        }

        return redirect()->route('admin.billings.index')->with('error', $data['message'] ?? getMessage(SOMETHING_WENT_WRONG));
    }

    public function planHistory()
    {
        return $this->billingService->getUserPackage(auth()->id());
    }

    public function transactionHistory()
    {
        return $this->billingService->getUserTransactionHistory(auth()->id());
    }
}