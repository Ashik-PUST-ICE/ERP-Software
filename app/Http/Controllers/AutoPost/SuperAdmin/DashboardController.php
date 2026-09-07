<?php

namespace App\Http\Controllers\AutoPost\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Services\DashboardService;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserPackage;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use ResponseTrait;

    public $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index(Request $request)
    {
        // ---- Top-level SaaS metrics ----

        // Total customers (Admin users managed by Super Admin)
        $totalCustomers = User::where('role', USER_ROLE_ADMIN)->count();

        // Active subscriptions (UserPackage entries that are active and not expired)
        $activeSubscriptionsQuery = UserPackage::where('status', STATUS_ACTIVE)
            ->where('end_date', '>=', now());

        $currentSubscriptions = (clone $activeSubscriptionsQuery)->count();

        // Active packages (distinct packageable combos that currently have active subscriptions)
        $activePackages = (clone $activeSubscriptionsQuery)
            ->select('packageable_type', 'packageable_id')
            ->distinct()
            ->count();

        // Total earnings (all-time)
        $totalEarn = Transaction::sum('amount');

        // ---- Monthly summaries (last 12 months) ----

        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $endMonth   = Carbon::now()->endOfMonth();

        // Monthly subscription counts (based on start_date)
        $rawSubData = UserPackage::whereDate('start_date', '>=', $startMonth)
            ->whereDate('start_date', '<=', $endMonth)
            ->select(
                DB::raw("strftime('%Y-%m', start_date) as ym"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym')
            ->toArray();

        // Monthly sales (sum of transaction amounts)
        $rawSalesData = Transaction::whereDate('payment_time', '>=', $startMonth)
            ->whereDate('payment_time', '<=', $endMonth)
            ->select(
                DB::raw("strftime('%Y-%m', payment_time) as ym"),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym')
            ->toArray();

        $monthLabels          = [];
        $subscriptionSeries   = [];
        $salesSeries          = [];

        $cursor = $startMonth->copy();
        while ($cursor <= $endMonth) {
            $ymKey = $cursor->format('Y-m');
            $label = $cursor->format('M Y');

            $monthLabels[]        = $label;
            $subscriptionSeries[] = isset($rawSubData[$ymKey]) ? (int) $rawSubData[$ymKey] : 0;
            $salesSeries[]        = isset($rawSalesData[$ymKey]) ? (float) $rawSalesData[$ymKey] : 0.0;

            $cursor->addMonth();
        }

        $subscriptionChart = [
            'labels' => $monthLabels,
            'data'   => $subscriptionSeries,
        ];

        $salesChart = [
            'labels' => $monthLabels,
            'data'   => $salesSeries,
        ];

        return view('auto_posts.super_admin.dashboard', [
            'pageTitle'          => 'Dashboard',
            'activeDashboard'    => 'active',
            'totalCustomers'     => $totalCustomers,
            'activePackages'     => $activePackages,
            'currentSubscriptions' => $currentSubscriptions,
            'totalEarn'          => $totalEarn,
            'subscriptionChart'  => $subscriptionChart,
            'salesChart'         => $salesChart,
        ]);
    }
}
