<?php

namespace App\Http\Services;

use App\Models\GatewayCurrency;
use App\Models\Package;
use App\Models\UserPackage;

class SubscriptionService
{
    public function getCurrentPlan($userId = null)
    {
        $userId = $userId == null ? auth()->id() : $userId;
        $ownerPackage = UserPackage::query()
            ->with('packageable')
            ->where('user_packages.user_id', $userId)
            ->where('user_packages.status', STATUS_ACTIVE)
            ->whereDate('user_packages.end_date', '>=', now())
            ->select('user_packages.*')
            ->first();

        return $ownerPackage?->makeHidden(['created_at', 'updated_at', 'deleted_at',  'order_id', 'package_id', 'user_id']);
    }

    public function getById($id)
    {
        $package = Package::query()->findOrFail($id);
        return $package?->makeHidden(['created_at', 'deleted_at', 'updated_at']);
    }

    public function getCurrencyByGatewayId($id)
    {
        $currencies = GatewayCurrency::where(['gateway_id' => $id])->get();
        foreach ($currencies as $currency) {
            $currency->symbol = $currency->symbol;
        }
        return $currencies?->makeHidden(['created_at', 'updated_at', 'deleted_at', 'gateway_id', 'owner_user_id']);
    }

    public function cancel()
    {
        return UserPackage::query()
            ->where(['user_id' => auth()->id(), 'status' => STATUS_ACTIVE])
            ->whereDate('end_date', '>=', now()->toDateTimeString())
            ->update(['status' => STATUS_DEACTIVATE]);
    }
}
