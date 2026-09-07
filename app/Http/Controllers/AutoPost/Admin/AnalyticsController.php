<?php

namespace App\Http\Controllers\AutoPost\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostHistory;
use App\Models\ScheduledPost;
use App\Models\SocialMediaAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // --- Top stat cards ---
        $totalAccounts  = SocialMediaAccount::count();
        $totalPosts     = PostHistory::count();
        $pendingPosts   = ScheduledPost::where('status', 'pending')->count();
        $scheduledPosts = ScheduledPost::where('status', 'pending')
            ->where('scheduled_time', '>', now())
            ->count();
        $successPosts   = PostHistory::where('success', true)->count();
        $failedPosts    = PostHistory::where('success', false)->count();

        // --- Monthly data for Social Post area chart (last 12 months) ---
        $monthLabels      = [];
        $totalByMonth     = [];
        $pendingByMonth   = [];
        $successByMonth   = [];
        $scheduledByMonth = [];
        $failedByMonth    = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('M');

            $totalByMonth[] = PostHistory::whereYear('posted_at', $month->year)
                ->whereMonth('posted_at', $month->month)
                ->count();

            $successByMonth[] = PostHistory::where('success', true)
                ->whereYear('posted_at', $month->year)
                ->whereMonth('posted_at', $month->month)
                ->count();

            $failedByMonth[] = PostHistory::where('success', false)
                ->whereYear('posted_at', $month->year)
                ->whereMonth('posted_at', $month->month)
                ->count();

            $pendingByMonth[] = ScheduledPost::where('status', 'pending')
                ->whereYear('scheduled_time', $month->year)
                ->whereMonth('scheduled_time', $month->month)
                ->count();

            $scheduledByMonth[] = ScheduledPost::where('status', 'pending')
                ->where('scheduled_time', '>', now())
                ->whereYear('scheduled_time', $month->year)
                ->whereMonth('scheduled_time', $month->month)
                ->count();
        }

        // --- Social Accounts donut chart ---
        $platformColors = [
            'facebook'  => '#FF4F02',
            'instagram' => '#02BCFF',
            'twitter'   => '#FFC402',
            'linkedin'  => '#0FA958',
            'youtube'   => '#0D0D0D',
            'tiktok'    => '#6BD096',
            'threads'   => '#6B02FF',
        ];

        $accountStats = SocialMediaAccount::select('platform', DB::raw('count(*) as total'))
            ->groupBy('platform')
            ->get();

        $chartLabels  = [];
        $chartSeries  = [];
        $chartColors  = [];

        foreach ($accountStats as $stat) {
            $chartLabels[] = ucfirst($stat->platform);
            $chartSeries[] = (int) $stat->total;
            $chartColors[] = $platformColors[$stat->platform] ?? '#cccccc';
        }

        $platformCount = $accountStats->count();

        return view('auto_posts.admin.analytics.index', [
            'pageTitle'        => 'Analytics',
            'activeAnalytics'  => 'active',
            'totalAccounts'    => $totalAccounts,
            'totalPosts'       => $totalPosts,
            'pendingPosts'     => $pendingPosts,
            'scheduledPosts'   => $scheduledPosts,
            'successPosts'     => $successPosts,
            'failedPosts'      => $failedPosts,
            'monthLabels'      => $monthLabels,
            'totalByMonth'     => $totalByMonth,
            'pendingByMonth'   => $pendingByMonth,
            'successByMonth'   => $successByMonth,
            'scheduledByMonth' => $scheduledByMonth,
            'failedByMonth'    => $failedByMonth,
            'platformCount'    => $platformCount,
            'chartLabels'      => $chartLabels,
            'chartSeries'      => $chartSeries,
            'chartColors'      => $chartColors,
        ]);
    }

    /**
     * Show platform-specific analytics
     */
    public function platformAnalytics($platform)
    {
        // Validate platform
        $validPlatforms = ['facebook', 'instagram', 'twitter', 'linkedin', 'youtube', 'tiktok', 'threads'];
        if (!in_array($platform, $validPlatforms)) {
            return redirect()->route('admin.platform.index')->with('error', 'Invalid platform');
        }

        // Get platform display name
        $platformNames = [
            'facebook'  => 'Facebook',
            'instagram' => 'Instagram',
            'twitter'   => 'Twitter',
            'linkedin'  => 'LinkedIn',
            'youtube'   => 'YouTube',
            'tiktok'    => 'TikTok',
            'threads'   => 'Threads',
        ];

        // Get account IDs for this platform
        $accountIds = SocialMediaAccount::where('platform', $platform)->pluck('id');

        // --- Top stat cards for platform ---
        $totalAccounts  = SocialMediaAccount::where('platform', $platform)->count();
        $totalPosts     = PostHistory::where('platform', $platform)->count();
        $pendingPosts   = ScheduledPost::whereIn('social_media_account_id', $accountIds)
            ->where('status', 'pending')
            ->count();
        $scheduledPosts = ScheduledPost::whereIn('social_media_account_id', $accountIds)
            ->where('status', 'pending')
            ->where('scheduled_time', '>', now())
            ->count();
        $successPosts   = PostHistory::where('platform', $platform)->where('success', true)->count();
        $failedPosts    = PostHistory::where('platform', $platform)->where('success', false)->count();

        // --- Monthly data for Social Post area chart (last 12 months) ---
        $monthLabels      = [];
        $totalByMonth     = [];
        $pendingByMonth   = [];
        $successByMonth   = [];
        $scheduledByMonth = [];
        $failedByMonth    = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthLabels[] = $month->format('M');

            $totalByMonth[] = PostHistory::where('platform', $platform)
                ->whereYear('posted_at', $month->year)
                ->whereMonth('posted_at', $month->month)
                ->count();

            $successByMonth[] = PostHistory::where('platform', $platform)
                ->where('success', true)
                ->whereYear('posted_at', $month->year)
                ->whereMonth('posted_at', $month->month)
                ->count();

            $failedByMonth[] = PostHistory::where('platform', $platform)
                ->where('success', false)
                ->whereYear('posted_at', $month->year)
                ->whereMonth('posted_at', $month->month)
                ->count();

            $pendingByMonth[] = ScheduledPost::whereIn('social_media_account_id', $accountIds)
                ->where('status', 'pending')
                ->whereYear('scheduled_time', $month->year)
                ->whereMonth('scheduled_time', $month->month)
                ->count();

            $scheduledByMonth[] = ScheduledPost::whereIn('social_media_account_id', $accountIds)
                ->where('status', 'pending')
                ->where('scheduled_time', '>', now())
                ->whereYear('scheduled_time', $month->year)
                ->whereMonth('scheduled_time', $month->month)
                ->count();
        }

        // --- Single platform for donut chart (just show accounts for this platform) ---
        $platformColors = [
            'facebook'  => '#FF4F02',
            'instagram' => '#02BCFF',
            'twitter'   => '#FFC402',
            'linkedin'  => '#0FA958',
            'youtube'   => '#0D0D0D',
            'tiktok'    => '#6BD096',
            'threads'   => '#6B02FF',
        ];

        $chartLabels  = [$platformNames[$platform]];
        $chartSeries  = [(int) $totalAccounts];
        $chartColors  = [$platformColors[$platform] ?? '#cccccc'];

        $platformCount = 1;

        return view('auto_posts.admin.analytics.platform', [
            'pageTitle'        => $platformNames[$platform] . ' Analytics',
            'activeAnalytics'  => 'active',
            'platform'         => $platform,
            'platformName'     => $platformNames[$platform],
            'totalAccounts'    => $totalAccounts,
            'totalPosts'       => $totalPosts,
            'pendingPosts'     => $pendingPosts,
            'scheduledPosts'   => $scheduledPosts,
            'successPosts'     => $successPosts,
            'failedPosts'      => $failedPosts,
            'monthLabels'      => $monthLabels,
            'totalByMonth'     => $totalByMonth,
            'pendingByMonth'   => $pendingByMonth,
            'successByMonth'   => $successByMonth,
            'scheduledByMonth' => $scheduledByMonth,
            'failedByMonth'    => $failedByMonth,
            'platformCount'    => $platformCount,
            'chartLabels'      => $chartLabels,
            'chartSeries'      => $chartSeries,
            'chartColors'      => $chartColors,
        ]);
    }
}